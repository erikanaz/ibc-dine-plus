<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Promo;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function index()
    {
        $tables = Table::where('status', 'available')->get();
        $menus = Menu::where('is_available', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get()
                ->groupBy('category');

        $promos = Promo::where(function($query) {
                $query->where('start_date', '<=', now())
                    ->orWhereNull('start_date');
            })
            ->where(function($query) {
                $query->where('end_date', '>=', now())
                    ->orWhereNull('end_date');
            })
            ->get();

        return view('customer.reservation.index', compact('tables', 'menus', 'promos'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'jumlah_tamu' => 'required|integer|min:1'
        ]);

        $tanggal = $request->tanggal;
        $waktu = $request->waktu;
        $jumlahTamu = $request->jumlah_tamu;

        // Cari meja yang tersedia pada tanggal dan waktu tertentu
        $reservedTables = Reservation::where('reservation_date', $tanggal)
            ->where('reservation_time', $waktu)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('table_id');

        $availableTables = Table::where('status', 'available')
            ->where('capacity', '>=', $jumlahTamu)
            ->whereNotIn('id', $reservedTables)
            ->get();

        return response()->json([
            'success' => true,
            'available_tables' => $availableTables
        ]);
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string'
        ]);

        $kode = strtoupper(trim($request->kode_promo));
        $today = now();

        $promo = Promo::where('promo_code', $kode)
            ->where(function($query) use ($today) {
                $query->where('start_date', '<=', $today)
                    ->orWhereNull('start_date');
            })
            ->where(function($query) use ($today) {
                $query->where('end_date', '>=', $today)
                    ->orWhereNull('end_date');
            })
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid'
            ]);
        }

        // Validasi usage limit
        if ($promo->usage_limit !== null && $promo->usage_limit <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Promo sudah habis kuota'
            ]);
        }

        return response()->json([
            'success' => true,
            'promo' => [
                'id' => $promo->id,
                'nama' => $promo->promo_code,
                'deskripsi' => $promo->description,
                'type' => $promo->type,
                'discount' => $promo->discount,
                'diskon_text' => $promo->type === 'percent' ? 
                    $promo->discount . '%' : 
                    'Rp ' . number_format($promo->discount, 0, ',', '.')
            ]
        ]);
    }

    public function calculatePrice(Request $request)
    {
        $request->validate([
            'pesan_menu' => 'required|boolean',
            'menu_items' => 'nullable|array',
            'promo_id' => 'nullable|exists:promos,id',
            'promo_type' => 'nullable|string',
            'promo_discount' => 'nullable|numeric'
        ]);

        $pesanMenu = $request->pesan_menu;
        $menuItems = $request->menu_items ?? [];
        $promo = $request->promo_id ? [
            'id' => $request->promo_id,
            'type' => $request->promo_type,
            'discount' => $request->promo_discount
        ] : null;

        // Hitung subtotal pesanan menu
        $subtotalPesanan = 0;
        if ($pesanMenu && !empty($menuItems)) {
            $menuIds = array_column($menuItems, 'menu_id');
            $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
            
            foreach ($menuItems as $item) {
                $menu = $menus[$item['menu_id']] ?? null;
                if ($menu) {
                    $subtotalPesanan += $item['jumlah'] * $menu->price;
                }
            }
        }

        // Hitung diskon promo untuk pesanan menu
        $diskonPromo = 0;
        if ($promo && $pesanMenu) {
            if ($promo['type'] === 'percent') {
                $diskonPromo = $subtotalPesanan * ($promo['discount'] / 100);
            } else {
                $diskonPromo = min($promo['discount'], $subtotalPesanan);
            }
        }

        $totalPesanan = max(0, $subtotalPesanan - $diskonPromo);

        // Hitung DP
        if ($pesanMenu) {
            $dp = $totalPesanan * 0.3; // 30% dari total pesanan
        } else {
            $dp = 300000; // DP fixed untuk reservasi tanpa makanan
        }

        // Hitung diskon promo untuk DP (hanya jika tidak pesan menu)
        $diskonDP = 0;
        if ($promo && !$pesanMenu) {
            if ($promo['type'] === 'percent') {
                $diskonDP = $dp * ($promo['discount'] / 100);
            } else {
                $diskonDP = min($promo['discount'], $dp);
            }
        }

        $totalDP = max(0, $dp - $diskonDP);

        return response()->json([
            'success' => true,
            'calculations' => [
                'subtotal_pesanan' => $subtotalPesanan,
                'diskon_promo' => $diskonPromo,
                'total_pesanan' => $totalPesanan,
                'dp' => $dp,
                'diskon_dp' => $diskonDP,
                'total_dp' => $totalDP
            ]
        ]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'reservation_date' => 'required|date',
        'reservation_time' => 'required',
        'guest_count' => 'required|integer|min:1',
        'table_id' => 'required|exists:tables,id',
        'notes' => 'nullable|string',
        'with_preorder' => 'required|boolean',
        'down_payment' => 'required|numeric|min:0', // Ini sudah total DP setelah diskon
        'promo_id' => 'nullable|exists:promos,id',
        'bukti_transfer' => 'required|file|image|max:2048',
        'menu_items' => 'nullable|array',
        'menu_items.*.menu_id' => 'required_with:menu_items|exists:menus,id',
        'menu_items.*.jumlah' => 'required_with:menu_items|integer|min:1',
    ]);

    DB::beginTransaction();

    try {
        // Handle file upload
        $filePath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bukti_transfer', $filename, 'public');
        }

        // Hitung DP awal (sebelum diskon) untuk keperluan tampilan
        $dpAwal = 0;
        if ($validated['with_preorder'] && isset($validated['menu_items']) && !empty($validated['menu_items'])) {
            // Untuk pre-order: DP awal = 30% dari total pesanan sebelum diskon
            $totalPesanan = 0;
            $menuIds = array_column($validated['menu_items'], 'menu_id');
            $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
            
            foreach ($validated['menu_items'] as $item) {
                $menu = $menus[$item['menu_id']] ?? null;
                if ($menu) {
                    $totalPesanan += $item['jumlah'] * $menu->price;
                }
            }
            $dpAwal = $totalPesanan * 0.3;
        } else {
            // Untuk tanpa pre-order: DP awal = 300.000
            $dpAwal = 300000;
        }

        // Buat reservasi
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'table_id' => $validated['table_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'guest_count' => $validated['guest_count'],
            'notes' => $validated['notes'] ?? null,
            'promo_id' => $validated['promo_id'] ?? null,
            'total_DP' => $validated['down_payment'], // Sudah termasuk diskon
            'status' => 'pending',
        ]);

        // Update usage limit promo
        if ($validated['promo_id']) {
            $promo = Promo::find($validated['promo_id']);
            if ($promo && $promo->usage_limit !== null) {
                $promo->decrement('usage_limit');
            }
        }

        // Buat payment untuk DP (jumlah yang harus dibayar setelah diskon)
        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $validated['down_payment'], // Sudah termasuk diskon
            'status' => 'pending',
            'payment_proof' => $filePath,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'IBC Batu Tulis',
            'notes' => 'Down Payment (DP) Reservasi',
        ]);

        // Jika ada pre-order menu, buat order
        if ($validated['with_preorder'] && isset($validated['menu_items']) && !empty($validated['menu_items'])) {
            
            // Hitung total price untuk order
            $totalPriceBeforeDiscount = 0;
            $menuIds = array_column($validated['menu_items'], 'menu_id');
            $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
            
            foreach ($validated['menu_items'] as $item) {
                $menu = $menus[$item['menu_id']] ?? null;
                if ($menu) {
                    $totalPriceBeforeDiscount += $item['jumlah'] * $menu->price;
                }
            }

            // Apply diskon jika ada untuk total pesanan
            $totalPriceAfterDiscount = $totalPriceBeforeDiscount;
            if ($validated['promo_id']) {
                $promo = Promo::find($validated['promo_id']);
                if ($promo) {
                    if ($promo->type === 'percent') {
                        $totalPriceAfterDiscount = $totalPriceBeforeDiscount * (1 - ($promo->discount / 100));
                    } else {
                        $totalPriceAfterDiscount = max(0, $totalPriceBeforeDiscount - $promo->discount);
                    }
                }
            }

            // Buat order
            $order = Order::create([
                'user_id' => Auth::id(),
                'reservation_id' => $reservation->id,
                'total_price' => $totalPriceAfterDiscount,
                'notes' => 'Pre-order dari reservasi',
            ]);

            // Buat order items (simpan harga asli)
            foreach ($validated['menu_items'] as $item) {
                $menu = $menus[$item['menu_id']] ?? null;
                if ($menu) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $item['menu_id'],
                        'qty' => $item['jumlah'],
                        'price' => $menu->price,
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'reservation_id' => $reservation->id,
            'message' => 'Reservasi berhasil dibuat. Bukti transfer sedang diverifikasi.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Reservation Store Error: ' . $e->getMessage());
        Log::error('Stack trace: ', ['exception' => $e]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function success($id)
{
    Log::info('Success Page Accessed - Reservation ID: ' . $id);
    
    if (!$id) {
        return redirect()->route('reservation.history')
            ->with('error', 'Reservasi tidak ditemukan');
    }

    // Load relationships yang diperlukan untuk perhitungan
    $reservation = Reservation::with([
        'table', 
        'promo', 
        'payments',
        'orders.orderItems.menu' // Penting untuk hitung DP awal
    ])->find($id);

    if (!$reservation) {
        return redirect()->route('reservation.history')
            ->with('error', 'Reservasi tidak ditemukan');
    }
    
    return view('customer.reservation.success', compact('reservation'));
}

    public function history()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with([
                'table', 
                'orders.orderItems.menu', 
                'promo', 
                'payments'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'customer_name' => $reservation->customer_name,
                    'customer_email' => $reservation->customer_email,
                    'customer_phone' => $reservation->customer_phone,
                    'reservation_date' => $reservation->reservation_date,
                    'reservation_time' => $reservation->reservation_time,
                    'guest_count' => $reservation->guest_count,
                    'notes' => $reservation->notes,
                    'status' => $reservation->status,
                    'total_DP' => $reservation->total_DP,
                    'created_at' => $reservation->created_at->toISOString(),
                    'table' => [
                        'id' => $reservation->table->id,
                        'number' => $reservation->table->number,
                        'capacity' => $reservation->table->capacity,
                    ],
                    'orders' => $reservation->orders->map(function ($order) {
                        return [
                            'id' => $order->id,
                            'total_price' => $order->total_price,
                            'order_items' => $order->orderItems->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'qty' => $item->qty,
                                    'price' => $item->price,
                                    'menu' => [
                                        'id' => $item->menu->id,
                                        'name' => $item->menu->name,
                                        'price' => $item->menu->price,
                                    ]
                                ];
                            })
                        ];
                    }),
                    'payments' => $reservation->payments->map(function ($payment) {
                        return [
                            'id' => $payment->id,
                            'amount' => $payment->amount,
                            'status' => $payment->status,
                            'payment_proof' => $payment->payment_proof,
                        ];
                    })
                ];
            });

        return view('customer.reservation.history', compact('reservations'));
    }

    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);
        
        // Hanya bisa cancel reservasi yang masih pending
        if ($reservation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak dapat dibatalkan'
            ]);
        }
        
        $reservation->update(['status' => 'cancelled']);
        
        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibatalkan'
        ]);
    }
}