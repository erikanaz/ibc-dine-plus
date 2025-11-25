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

    // Cari meja yang sudah direservasi pada tanggal dan waktu tertentu
    $reservedTableIds = Reservation::where('reservation_date', $tanggal)
        ->where('reservation_time', $waktu)
        ->whereIn('status', ['pending', 'confirmed'])
        ->pluck('table_id')
        ->toArray();

    // Ambil semua meja dengan tambahan info ketersediaan
    $allTables = Table::all()->map(function ($table) use ($reservedTableIds, $jumlahTamu) {
        $hasReservation = in_array($table->id, $reservedTableIds);
        $isCapacitySufficient = $table->capacity >= $jumlahTamu;
        
        // Tentukan status efektif untuk UI
        $effectiveStatus = $table->status;
        if ($hasReservation) {
            $effectiveStatus = 'reserved_slot'; // Reserved untuk waktu ini
        }
        
        // Meja tersedia jika: status available, tidak ada reservasi, dan kapasitas cukup
        $isAvailable = $table->status === 'available' && 
                       !$hasReservation && 
                       $isCapacitySufficient;
        
        return [
            'id' => $table->id,
            'number' => $table->number,
            'capacity' => $table->capacity,
            'status' => $table->status, // Status asli dari database
            'effective_status' => $effectiveStatus, // Status untuk ditampilkan
            'has_reservation' => $hasReservation,
            'is_available' => $isAvailable,
            'is_capacity_insufficient' => !$isCapacitySufficient,
        ];
    });

    return response()->json([
        'success' => true,
        'all_tables' => $allTables,
        'guest_count' => $jumlahTamu
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
                'discount' => $promo->discount,
                'diskon_text' => $promo->discount . '%' // ✅ SELALU PERSENTASE
            ]
        ]);
    }

    public function calculatePrice(Request $request)
    {
        $request->validate([
            'pesan_menu' => 'required|boolean',
            'menu_items' => 'nullable|array',
            'promo_id' => 'nullable|exists:promos,id',
            'promo_discount' => 'nullable|numeric'
        ]);

        $pesanMenu = $request->pesan_menu;
        $menuItems = $request->menu_items ?? [];
        $promo = $request->promo_id ? [
            'id' => $request->promo_id,
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

        // Hitung diskon promo (PERSENTASE)
        $diskonPromo = 0;
        if ($promo && $pesanMenu) {
            $diskonPromo = $subtotalPesanan * ($promo['discount'] / 100);
        }

        $totalPesanan = max(0, $subtotalPesanan - $diskonPromo);

        // Hitung DP
        if ($pesanMenu) {
            $dp = $totalPesanan * 0.3; // 30% dari total pesanan
        } else {
            $dp = 300000; // DP fixed untuk reservasi tanpa makanan
        }

        // Hitung diskon DP (PERSENTASE)
        $diskonDP = 0;
        if ($promo && !$pesanMenu) {
            $diskonDP = $dp * ($promo['discount'] / 100);
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
            'down_payment' => 'required|numeric|min:0',
            'promo_id' => 'nullable|exists:promos,id',
            'menu_items' => 'nullable|array',
            'menu_items.*.menu_id' => 'required_with:menu_items|exists:menus,id',
            'menu_items.*.jumlah' => 'required_with:menu_items|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Hitung DP awal (untuk validasi)
            $dpAwal = 0;
            if ($validated['with_preorder'] && isset($validated['menu_items']) && !empty($validated['menu_items'])) {
                $totalPesanan = 0;
                $menuIds = array_column($validated['menu_items'], 'menu_id');
                $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
                
                foreach ($validated['menu_items'] as $item) {
                    $menu = $menus[$item['menu_id']] ?? null;
                    if ($menu) {
                        $totalPesanan += $item['jumlah'] * $menu->price;
                    }
                }
                
                // Apply promo discount jika ada
                if ($validated['promo_id']) {
                    $promo = Promo::find($validated['promo_id']);
                    if ($promo) {
                        $totalPesanan = $totalPesanan * (1 - ($promo->discount / 100));
                    }
                }
                
                $dpAwal = $totalPesanan * 0.3;
            } else {
                $dpAwal = 300000;
                
                // Apply promo discount untuk DP fixed jika ada
                if ($validated['promo_id']) {
                    $promo = Promo::find($validated['promo_id']);
                    if ($promo) {
                        $dpAwal = max(0, $dpAwal - ($dpAwal * ($promo->discount / 100)));
                    }
                }
            }

            // Validasi DP dari frontend dengan perhitungan backend
            if (abs($validated['down_payment'] - $dpAwal) > 100) { // Toleransi 100 rupiah
                Log::warning('DP mismatch', [
                    'frontend_dp' => $validated['down_payment'],
                    'backend_dp' => $dpAwal,
                    'user_id' => Auth::id()
                ]);
            }

            // Set payment deadline
            $paymentDeadline = now()->addHours(24);
            
            // Debug detail
            Log::info('=== RESERVATION CREATION DEBUG ===');
            Log::info('Payment Deadline: ' . $paymentDeadline->format('Y-m-d H:i:s'));
            Log::info('DP from Frontend: ' . $validated['down_payment']);
            Log::info('DP Calculated: ' . $dpAwal);
            Log::info('With Preorder: ' . ($validated['with_preorder'] ? 'Yes' : 'No'));
            Log::info('Promo ID: ' . ($validated['promo_id'] ?? 'None'));

            // Data untuk create reservation
            $reservationData = [
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
                'total_DP' => $validated['down_payment'], // Gunakan DP dari frontend
                'status' => 'waiting_payment',
                'payment_deadline' => $paymentDeadline,
                'created_by' => 'customer',
            ];

            Log::info('Reservation Data:', $reservationData);

            // Buat reservasi
            $reservation = Reservation::create($reservationData);

            // Debug setelah create
            Log::info('=== AFTER RESERVATION CREATION ===');
            Log::info('Reservation ID: ' . $reservation->id);
            Log::info('Payment Deadline from DB: ' . $reservation->payment_deadline);
            Log::info('Status: ' . $reservation->status);

            // Update usage limit promo
            if ($validated['promo_id']) {
                $promo = Promo::find($validated['promo_id']);
                if ($promo && $promo->usage_limit !== null) {
                    $promo->decrement('usage_limit');
                    Log::info('Promo usage decremented', [
                        'promo_id' => $promo->id,
                        'new_usage_limit' => $promo->usage_limit
                    ]);
                }
            }

            // Buat payment untuk DP
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $validated['down_payment'],
                'status' => 'pending',
                'payment_proof' => null,
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'IBC Batu Tulis',
                'notes' => 'Down Payment (DP) Reservasi',
            ]);

            Log::info('Payment created', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'status' => $payment->status
            ]);

            // Meja tetap available untuk customer reservation
            Table::where('id', $validated['table_id'])->update(['status' => 'available']);
            Log::info("Meja {$validated['table_id']} tetap available (customer reservation)");

            // Jika ada pre-order menu, buat order
            if ($validated['with_preorder'] && isset($validated['menu_items']) && !empty($validated['menu_items'])) {
                
                $totalPriceBeforeDiscount = 0;
                $menuIds = array_column($validated['menu_items'], 'menu_id');
                $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
                
                foreach ($validated['menu_items'] as $item) {
                    $menu = $menus[$item['menu_id']] ?? null;
                    if ($menu) {
                        $totalPriceBeforeDiscount += $item['jumlah'] * $menu->price;
                    }
                }

                $totalPriceAfterDiscount = $totalPriceBeforeDiscount;
                if ($validated['promo_id']) {
                    $promo = Promo::find($validated['promo_id']);
                    if ($promo) {
                        // HANYA PERSENTASE
                        $totalPriceAfterDiscount = $totalPriceBeforeDiscount * (1 - ($promo->discount / 100));
                        Log::info('Promo applied to order', [
                            'promo_id' => $promo->id,
                            'discount_percent' => $promo->discount,
                            'total_before' => $totalPriceBeforeDiscount,
                            'total_after' => $totalPriceAfterDiscount
                        ]);
                    }
                }

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'reservation_id' => $reservation->id,
                    'total_price' => $totalPriceAfterDiscount,
                    'notes' => 'Pre-order dari reservasi',
                ]);

                Log::info('Order created', [
                    'order_id' => $order->id,
                    'total_price' => $order->total_price
                ]);

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

                Log::info('Order items created', [
                    'count' => count($validated['menu_items'])
                ]);
            }

            DB::commit();

            // Debug final
            Log::info('=== FINAL CHECK ===');
            $finalReservation = Reservation::find($reservation->id);
            Log::info('Final Payment Deadline: ' . $finalReservation->payment_deadline);
            Log::info('Final Status: ' . $finalReservation->status);

            return response()->json([
                'success' => true,
                'reservation_id' => $reservation->id,
                'message' => 'Reservasi berhasil dibuat. Silakan lakukan pembayaran dalam 24 jam.'
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

        // Check jika reservasi expired
        if ($reservation->status === 'waiting_payment' && 
            $reservation->payment_deadline && 
            now()->greaterThan($reservation->payment_deadline)) {
            
            $reservation->update(['status' => 'cancelled']);
            $reservation->refresh();
            Log::info("Reservation #{$reservation->id} auto-cancelled in success page");
        }

        // ✅ HITUNG PAYMENT DEADLINE
        $paymentDeadline = $reservation->payment_deadline ?? $reservation->created_at->addHours(24);
        
        return view('customer.reservation.success', compact('reservation', 'paymentDeadline'));
    }

    public function history()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with([
                'table', 
                'orders.orderItems.menu', 
                'promo', 
                'payments' // Tetap load payments, tapi mungkin kosong
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Check dan update status expired
        foreach ($reservations as $reservation) {
            if ($reservation->status === 'waiting_payment' && 
                $reservation->payment_deadline && 
                now()->greaterThan($reservation->payment_deadline)) {
                
                $reservation->update(['status' => 'cancelled']);
                Log::info("Reservation #{$reservation->id} auto-cancelled in history page");
            }
        }

        // Refresh data setelah update
        $reservations = $reservations->fresh();

        $formattedReservations = $reservations->map(function ($reservation) {
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
                'payment_deadline' => $reservation->payment_deadline ? $reservation->payment_deadline->toISOString() : null,
                'created_at' => $reservation->created_at->toISOString(),
                'with_preorder' => $reservation->orders->count() > 0,
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

        return view('customer.reservation.history', [
        'reservations' => $formattedReservations->toArray()]);

            // ->map(function ($reservation) {
            //     return [
            //         'id' => $reservation->id,
            //         'customer_name' => $reservation->customer_name,
            //         'customer_email' => $reservation->customer_email,
            //         'customer_phone' => $reservation->customer_phone,
            //         'reservation_date' => $reservation->reservation_date,
            //         'reservation_time' => $reservation->reservation_time,
            //         'guest_count' => $reservation->guest_count,
            //         'notes' => $reservation->notes,
            //         'status' => $reservation->status,
            //         'total_DP' => $reservation->total_DP,
            //         'payment_deadline' => $reservation->payment_deadline ? $reservation->payment_deadline->toISOString() : null,
            //         'created_at' => $reservation->created_at->toISOString(),
            //         'with_preorder' => $reservation->orders->count() > 0,
            //         'table' => [
            //             'id' => $reservation->table->id,
            //             'number' => $reservation->table->number,
            //             'capacity' => $reservation->table->capacity,
            //         ],
            //         'orders' => $reservation->orders->map(function ($order) {
            //             return [
            //                 'id' => $order->id,
            //                 'total_price' => $order->total_price,
            //                 'order_items' => $order->orderItems->map(function ($item) {
            //                     return [
            //                         'id' => $item->id,
            //                         'qty' => $item->qty,
            //                         'price' => $item->price,
            //                         'menu' => [
            //                             'id' => $item->menu->id,
            //                             'name' => $item->menu->name,
            //                             'price' => $item->menu->price,
            //                         ]
            //                     ];
            //                 })
            //             ];
            //         }),
            //         'payments' => $reservation->payments->map(function ($payment) {
            //             return [
            //                 'id' => $payment->id,
            //                 'amount' => $payment->amount,
            //                 'status' => $payment->status,
            //                 'payment_proof' => $payment->payment_proof,
            //             ];
            //         })
            //     ];
            // });

        // return view('customer.reservation.history', compact('reservations'));
    }

    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

        // Check jika sudah expired, langsung return
        if ($reservation->status === 'waiting_payment' && 
            $reservation->payment_deadline && 
            now()->greaterThan($reservation->payment_deadline)) {
            
            $reservation->update(['status' => 'cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Reservasi telah dibatalkan secara otomatis karena melewati batas waktu pembayaran.'
            ]);
        }

        // Status yang boleh dibatalkan oleh user
        $cancelableStatuses = ['waiting_payment', 'pending'];
        
        if (!in_array($reservation->status, $cancelableStatuses)) {
            $statusText = [
                'waiting_payment' => 'menunggu pembayaran',
                'pending' => 'menunggu verifikasi',
                'confirmed' => 'sudah dikonfirmasi',
                'completed' => 'selesai',
                'cancelled' => 'sudah dibatalkan'
            ];

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak dapat dibatalkan karena statusnya ' . ($statusText[$reservation->status] ?? $reservation->status) . '.'
            ]);
        }
        
        $reservation->update(['status' => 'cancelled']);
        
        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibatalkan'
        ]);
    }

    public function payment(Reservation $reservation)
    {
        // Check jika reservasi expired
        if ($reservation->status === 'waiting_payment' && 
            $reservation->payment_deadline && 
            now()->greaterThan($reservation->payment_deadline)) {
            
            $reservation->update(['status' => 'cancelled']);
            $reservation->refresh();
            Log::info("Reservation #{$reservation->id} auto-cancelled when accessing payment page");
            
            return redirect()->route('reservation.history')
                ->with('error', 'Reservasi telah dibatalkan karena melewati batas waktu pembayaran.');
        }

        // Pastikan reservasi milik user yang login dan status waiting_payment
        if ($reservation->user_id !== Auth::id() || $reservation->status !== 'waiting_payment') {
            abort(403);
        }

        return view('customer.reservation.payment', compact('reservation'));
    }

    public function uploadPayment(Request $request, Reservation $reservation)
    {
        // Validasi tidak expired sebelum upload
        if ($reservation->status === 'waiting_payment' && 
            $reservation->payment_deadline && 
            now()->greaterThan($reservation->payment_deadline)) {
            
            $reservation->update(['status' => 'cancelled']);
            Log::info("Reservation #{$reservation->id} auto-cancelled when trying to upload payment");
            
            return redirect()->route('reservation.history')
                ->with('error', 'Tidak dapat upload bukti pembayaran. Reservasi telah dibatalkan karena melewati batas waktu.');
        }

        // Validasi bukti transfer
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048'
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bukti_transfer', $filename, 'public');
        }

        // ✅ BUAT payment record di sini (bukan di store)
        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $reservation->total_DP,
            'status' => 'verifying',
            'payment_proof' => $filePath,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'IBC Batu Tulis',
            'notes' => 'Down Payment (DP) Reservasi - Menunggu Verifikasi',
        ]);

        // Update reservation status
        $reservation->update([
            'status' => 'pending' // Menunggu verifikasi admin
        ]);

        return redirect()->route('reservation.history')
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}