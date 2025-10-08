<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'reservation.table', 'orderItems.menu'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Hitung statistik
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price');
        $pendingOrders = Order::whereHas('reservation', function($query) {
            $query->where('status', 'pending');
        })->count();
        $completedOrders = Order::whereHas('reservation', function($query) {
            $query->where('status', 'completed');
        })->count();

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'completedOrders'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'reservation.table', 'orderItems.menu']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $reservations = Reservation::with(['user', 'table'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereDoesntHave('order')
            ->get();
        
        $menus = Menu::where('is_available', true)->get();
        
        return view('admin.orders.create', compact('reservations', 'menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $reservation = Reservation::with('user')->find($validated['reservation_id']);
            
            // Calculate total price
            $totalPrice = 0;
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                $totalPrice += $menu->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'user_id' => $reservation->user_id,
                'reservation_id' => $validated['reservation_id'],
                'total_price' => $totalPrice,
                'notes' => $validated['notes'],
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                
                $order->orderItems()->create([
                    'menu_id' => $item['menu_id'],
                    'qty' => $item['quantity'],
                    'price' => $menu->price,
                ]);
            }
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    public function edit(Order $order)
    {
        $order->load(['orderItems.menu', 'reservation']);
        $menus = Menu::where('is_available', true)->get();
        
        return view('admin.orders.edit', compact('order', 'menus'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($order, $validated) {
            // Calculate new total price
            $totalPrice = 0;
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                $totalPrice += $menu->price * $item['quantity'];
            }

            // Update order
            $order->update([
                'total_price' => $totalPrice,
                'notes' => $validated['notes'],
            ]);

            // Delete existing order items
            $order->orderItems()->delete();

            // Create new order items
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                
                $order->orderItems()->create([
                    'menu_id' => $item['menu_id'],
                    'qty' => $item['quantity'],
                    'price' => $menu->price,
                ]);
            }
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->orderItems()->delete();
            $order->delete();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    public function printInvoice(Order $order)
    {
        $order->load(['user', 'reservation.table', 'orderItems.menu']);
        
        return view('admin.orders.invoice', compact('order'));
    }
}