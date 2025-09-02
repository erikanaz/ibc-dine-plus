<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        // $menus = Menu::where('is_available', true)->get()->groupBy('category');
        // return view('pemesanan.index', compact('menus'));
        $menus = Menu::all()->groupBy('category');
        return view('customer.order.index', compact('menus'));
    }

    public function checkout()
    {
        // return view('customer.order.checkout');
        $orderData = json_decode(request()->cookie('order_data'), true) ?? [];
        return view('customer.order.checkout', compact('orderData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id', // Wajib ada
            'items.*.price' => 'required|integer|min:0',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string|max:255', // Optional
            'order_type' => 'required|in:dine-in,takeaway',
            'payment_method' => 'required|in:cash,qris,bank_transfer',
            'total' => 'required|integer|min:0',
            'order_note' => 'nullable|string|max:500' // Catatan umum pesanan
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_code' => 'ORD-' . Str::random(8),
                'user_id' => Auth::id(),
                'order_type' => $validated['order_type'],
                'payment_method' => $validated['payment_method'],
                'total_price' => $validated['total'],
                'note' => $validated['order_note'] ?? null,
                'status' => 'pending'
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'], // Wajib
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'notes' => $item['note'] ?? null // Optional
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pay(Request $request)
    {
        // Validasi sama seperti store
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.qty' => 'required|integer',
            'order_type' => 'required|in:dine-in,takeaway',
            'payment_method' => 'required|in:cash,qris,bank_transfer',
            'total' => 'required|numeric',
            'note' => 'nullable|string|max:255'
        ]);

        try {
            // Pertama buat order dulu
            $order = Order::create([
                'order_code' => 'ORD-' . Str::random(8),
                'order_type' => $validated['order_type'],
                'payment_method' => $validated['payment_method'],
                'total_price' => $validated['total'],
                'status' => 'waiting_payment'
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['qty']
                ]);
            }

            // Generate Snap Token (Midtrans)
            $snapToken = $this->generateSnapToken($order);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function generateSnapToken($order)
    {
        // Implementasi Midtrans disini
        // Contoh sederhana:
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => 'Customer',
                'email' => 'customer@example.com',
                'phone' => '08123456789',
            ],
        ];

        return \Midtrans\Snap::getSnapToken($params);
    }

    public function success($order)
    {
        $order = Order::findOrFail($order);
        return view('customer.order.success', compact('order'));
    }

    public function pending($order)
    {
        $order = Order::findOrFail($order);
        return view('customer.order.pending', compact('order'));
    }

}
