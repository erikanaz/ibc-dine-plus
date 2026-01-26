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
        // Ambil semua meja yang tersedia
        $tables = Table::where('status', 'available')
            ->orderBy('location')
            ->orderBy('number')
            ->get();

        $menus = Menu::where('is_available', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get()
                ->groupBy('category');

        $promos = Promo::active()->get();

        return view('customer.reservation.index', compact('tables', 'menus', 'promos'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|before_or_equal:2050-12-31',
            'waktu' => 'required',
            'jumlah_tamu' => 'required|integer|min:1'
        ]);

        $tanggal = $request->tanggal;
        $waktu = $request->waktu;
        $jumlahTamu = $request->jumlah_tamu;

        // ✅ DIUBAH: Cari meja yang sudah direservasi
        $reservedTableIds = DB::table('reservation_tables')
            ->join('reservations', 'reservation_tables.reservation_id', '=', 'reservations.id')
            ->where('reservations.reservation_date', $tanggal)
            ->where('reservations.reservation_time', $waktu)
            ->whereIn('reservations.status', ['pending', 'confirmed'])
            ->pluck('reservation_tables.table_id')
            ->toArray();

        // ✅ DIUBAH: Hapus validasi kapasitas per meja
        $allTables = Table::all()->map(function ($table) use ($reservedTableIds) {
            $hasReservation = in_array($table->id, $reservedTableIds);
            
            // Tentukan status efektif untuk UI
            $effectiveStatus = $table->status;
            if ($hasReservation) {
                $effectiveStatus = 'reserved_slot';
            }
            
            // Meja tersedia jika: status available dan tidak ada reservasi
            $isAvailable = $table->status === 'available' && !$hasReservation;
            
            return [
                'id' => $table->id,
                'number' => $table->number,
                'capacity' => $table->capacity,
                'status' => $table->status,
                'effective_status' => $effectiveStatus,
                'has_reservation' => $hasReservation,
                'is_available' => $isAvailable, // ❌ TIDAK ADA LAGI validasi kapasitas
                'location' => $table->location,
                'location_label' => $table->location_label,
            ];
        });

        // ✅ TAMBAH: Cari kombinasi meja yang bisa memenuhi kapasitas
        $suggestedCombinations = $this->findOptimalTableCombinations($allTables, $jumlahTamu);

        return response()->json([
            'success' => true,
            'all_tables' => $allTables,
            'guest_count' => $jumlahTamu,
            'suggested_combinations' => $suggestedCombinations
        ]);
    }

    /**
     * ✅ METHOD BARU: Cari kombinasi meja optimal untuk kapasitas tertentu
     */
    private function findOptimalTableCombinations($tables, $requiredCapacity)
    {
        $availableTables = $tables->where('is_available', true)->values();
        
        if ($availableTables->isEmpty()) {
            return [];
        }

        $combinations = [];
        
        // 1. Cari meja tunggal yang cukup besar (jika ada)
        foreach ($availableTables as $table) {
            if ($table['capacity'] >= $requiredCapacity) {
                $combinations[] = [
                    'tables' => [$table['id']],
                    'total_capacity' => $table['capacity'],
                    'table_count' => 1,
                    'table_numbers' => $table['number'],
                    'is_single_table' => true,
                    'efficiency_score' => $table['capacity'] - $requiredCapacity // Semakin kecil semakin efisien
                ];
            }
        }

        // 2. Cari kombinasi 2 meja
        for ($i = 0; $i < count($availableTables); $i++) {
            for ($j = $i + 1; $j < count($availableTables); $j++) {
                $table1 = $availableTables[$i];
                $table2 = $availableTables[$j];
                $totalCapacity = $table1['capacity'] + $table2['capacity'];
                
                if ($totalCapacity >= $requiredCapacity) {
                    $efficiencyScore = $totalCapacity - $requiredCapacity;
                    
                    $combinations[] = [
                        'tables' => [$table1['id'], $table2['id']],
                        'total_capacity' => $totalCapacity,
                        'table_count' => 2,
                        'table_numbers' => $table1['number'] . ', ' . $table2['number'],
                        'is_single_table' => false,
                        'efficiency_score' => $efficiencyScore
                    ];
                }
            }
        }

        // 3. Cari kombinasi 3 meja (jika diperlukan)
        for ($i = 0; $i < count($availableTables); $i++) {
            for ($j = $i + 1; $j < count($availableTables); $j++) {
                for ($k = $j + 1; $k < count($availableTables); $k++) {
                    $table1 = $availableTables[$i];
                    $table2 = $availableTables[$j];
                    $table3 = $availableTables[$k];
                    $totalCapacity = $table1['capacity'] + $table2['capacity'] + $table3['capacity'];
                    
                    if ($totalCapacity >= $requiredCapacity) {
                        $efficiencyScore = $totalCapacity - $requiredCapacity;
                        
                        $combinations[] = [
                            'tables' => [$table1['id'], $table2['id'], $table3['id']],
                            'total_capacity' => $totalCapacity,
                            'table_count' => 3,
                            'table_numbers' => $table1['number'] . ', ' . $table2['number'] . ', ' . $table3['number'],
                            'is_single_table' => false,
                            'efficiency_score' => $efficiencyScore
                        ];
                    }
                }
            }
        }

        // Urutkan berdasarkan: 
        // 1. Single table dulu (jika ada)
        // 2. Efficiency score terbaik (kapasitas minimal melebihi kebutuhan)
        // 3. Jumlah meja sedikit
        usort($combinations, function($a, $b) {
            // Prioritas single table
            if ($a['is_single_table'] && !$b['is_single_table']) return -1;
            if (!$a['is_single_table'] && $b['is_single_table']) return 1;
            
            // Kemudian efficiency score
            if ($a['efficiency_score'] !== $b['efficiency_score']) {
                return $a['efficiency_score'] <=> $b['efficiency_score'];
            }
            
            // Terakhir jumlah meja
            return $a['table_count'] <=> $b['table_count'];
        });

        return array_slice($combinations, 0, 5); // Ambil 5 kombinasi terbaik
    }

    /**
     * ✅ METHOD BARU: Cari kombinasi meja untuk memenuhi kapasitas
     */
    private function findTableCombinations($tables, $requiredCapacity)
    {
        $availableTables = $tables->where('is_available', true)->values();
        
        if ($availableTables->isEmpty()) {
            return [];
        }

        $combinations = [];
        
        // Cari meja tunggal yang cukup besar
        foreach ($availableTables as $table) {
            if ($table['capacity'] >= $requiredCapacity) {
                $combinations[] = [
                    'tables' => [$table['id']],
                    'total_capacity' => $table['capacity'],
                    'table_count' => 1,
                    'table_numbers' => $table['number']
                ];
            }
        }

        // Cari kombinasi 2 meja
        for ($i = 0; $i < count($availableTables); $i++) {
            for ($j = $i + 1; $j < count($availableTables); $j++) {
                $table1 = $availableTables[$i];
                $table2 = $availableTables[$j];
                $totalCapacity = $table1['capacity'] + $table2['capacity'];
                
                if ($totalCapacity >= $requiredCapacity) {
                    $combinations[] = [
                        'tables' => [$table1['id'], $table2['id']],
                        'total_capacity' => $totalCapacity,
                        'table_count' => 2,
                        'table_numbers' => $table1['number'] . ', ' . $table2['number']
                    ];
                }
            }
        }

        // Urutkan berdasarkan: jumlah meja sedikit -> kapasitas sedikit
        usort($combinations, function($a, $b) {
            if ($a['table_count'] === $b['table_count']) {
                return $a['total_capacity'] <=> $b['total_capacity'];
            }
            return $a['table_count'] <=> $b['table_count'];
        });

        return array_slice($combinations, 0, 5); // Ambil 5 kombinasi terbaik
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string'
        ]);

        $kode = strtoupper(trim($request->kode_promo));
        $promo = Promo::where('promo_code', $kode)->available()->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa'
            ]);
        }

        if (!$promo->can_be_used) {
            return response()->json([
                'success' => false,
                'message' => 'Promo sudah tidak dapat digunakan (kuota habis atau tidak aktif)'
            ]);
        }

        return response()->json([
            'success' => true,
            'promo' => [
                'id' => $promo->id,
                'nama' => $promo->promo_code,
                'deskripsi' => $promo->description,
                'discount' => (float) $promo->discount,
                'diskon_text' => $promo->discount_formatted,
                'start_date' => $promo->start_date?->format('d/m/Y'),
                'end_date' => $promo->end_date?->format('d/m/Y'),
                'usage_limit' => $promo->usage_limit,
                'used_count' => $promo->used_count,
                'can_be_used' => $promo->can_be_used,
                'status' => $promo->status,
                'status_label' => $promo->status_label,
            ]
        ]);
    }

    public function calculatePrice(Request $request)
    {
        $request->validate([
            'pesan_menu' => 'required|boolean',
            'menu_items' => 'nullable|array',
            'promo_id' => 'nullable|exists:promos,id',
            'promo_discount' => 'nullable|numeric',
            'table_ids' => 'required|array|min:1', // ✅ DITAMBAH: untuk multi-table
            'table_ids.*' => 'exists:tables,id'    // ✅ DITAMBAH
        ]);

        $pesanMenu = $request->pesan_menu;
        $menuItems = $request->menu_items ?? [];
        $tableIds = $request->table_ids ?? [];

        $promo = null;
        if ($request->promo_id) {
            $promo = Promo::where('id', $request->promo_id)
                ->active()
                ->first();

            if (!$promo || !$promo->can_be_used) {
                $promo = null;
            }
        }

        // ✅ DITAMBAH: Hitung total kapasitas dari meja yang dipilih
        $selectedTables = Table::whereIn('id', $tableIds)->get();
        $totalCapacity = $selectedTables->sum('capacity');

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
        if ($promo) {
            if ($pesanMenu) {
                $diskonPromo = $subtotalPesanan * ($promo->discount / 100);
            } else {
                $diskonPromo = 300000 * ($promo->discount / 100);
            }
        }

        $totalPesanan = max(0, $subtotalPesanan - $diskonPromo);

        // Hitung DP
        if ($pesanMenu) {
            $dp = $totalPesanan * 0.3; // 30% dari total pesanan SETELAH DISKON
        } else {
            $dp = 300000; // DP fixed untuk reservasi tanpa makanan
        }

        // Hitung diskon DP - PERBAIKAN: HANYA untuk reservasi TANPA makanan
        $diskonDP = 0;
        if ($promo && !$pesanMenu) {
            $diskonDP = $dp * ($promo->discount / 100);
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
                'total_dp' => $totalDP,
                'total_capacity' => $totalCapacity, // ✅ DITAMBAH
                'table_count' => count($tableIds), // ✅ DITAMBAH
            ],
            'promo_valid' => $promo ? true : false,
            'promo_info' => $promo ? [
                'id' => $promo->id,
                'nama' => $promo->promo_code,
                'discount' => (float) $promo->discount,
                'status' => $promo->status
            ] : null,
            'selected_tables' => $selectedTables->map(function ($table) { // ✅ DITAMBAH
                return [
                    'id' => $table->id,
                    'number' => $table->number,
                    'capacity' => $table->capacity,
                    'location' => $table->location,
                    'location_label' => $table->location_label
                ];
            })
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
            'table_ids' => 'required|array|min:1', // ✅ DIUBAH: dari table_id ke table_ids
            'table_ids.*' => 'exists:tables,id',
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
            // Validasi promo
            $promo = null;
            if ($validated['promo_id']) {
                $promo = Promo::where('id', $validated['promo_id'])
                    ->active()
                    ->first();

                if (!$promo || !$promo->can_be_used) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode promo tidak valid, sudah kadaluarsa, atau habis kuota'
                    ]);
                }
            }

            // ✅ DITAMBAH: Validasi ketersediaan semua meja
            foreach ($validated['table_ids'] as $tableId) {
                $table = Table::find($tableId);
                if (!$table->isAvailableForDateTime($validated['reservation_date'], $validated['reservation_time'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Meja {$table->number} tidak tersedia pada waktu yang dipilih"
                    ]);
                }
            }

            // ✅ DITAMBAH: Hitung total kapasitas meja yang dipilih
            $selectedTables = Table::whereIn('id', $validated['table_ids'])->get();
            $totalCapacity = $selectedTables->sum('capacity');
            
            // ✅ DITAMBAH: Validasi apakah kapasitas meja cukup
            if ($totalCapacity < $validated['guest_count']) {
                return response()->json([
                    'success' => false,
                    'message' => "Kapasitas meja tidak mencukupi. Total kapasitas: {$totalCapacity}, Jumlah tamu: {$validated['guest_count']}"
                ]);
            }

            // ✅ DITAMBAH: Generate table numbers string
            $tableNumbers = $selectedTables->sortBy('number')->pluck('number')->implode(', ');

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
                if ($promo) {
                    $totalPesanan = $totalPesanan * (1 - ($promo->discount / 100));
                }
                
                $dpAwal = $totalPesanan * 0.3;
            } else {
                $dpAwal = 300000;
                
                // Apply promo discount untuk DP fixed jika ada
                if ($promo) {
                    $dpAwal = max(0, $dpAwal - ($dpAwal * ($promo->discount / 100)));
                }
            }

            // Validasi DP dari frontend dengan perhitungan backend
            if (abs($validated['down_payment'] - $dpAwal) > 100) {
                Log::warning('DP mismatch', [
                    'frontend_dp' => $validated['down_payment'],
                    'backend_dp' => $dpAwal,
                    'user_id' => Auth::id()
                ]);
            }

            // Set payment deadline
            $paymentDeadline = now()->addHours(24);
            
            // Data untuk create reservation
            $reservationData = [
                'user_id' => Auth::id(),
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                // 'table_id' => $validated['table_id'], // ❌ DIHAPUS
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'guest_count' => $validated['guest_count'],
                'notes' => $validated['notes'] ?? null,
                'promo_id' => $promo ? $promo->id : null,
                'total_DP' => $validated['down_payment'],
                'status' => 'waiting_payment',
                'payment_deadline' => $paymentDeadline,
                'created_by' => 'customer',
                'table_numbers' => $tableNumbers, // ✅ DITAMBAH
            ];

            Log::info('Reservation Data:', $reservationData);

            // Buat reservasi
            $reservation = Reservation::create($reservationData);

            // ✅ DITAMBAH: Attach semua meja ke reservasi
            $reservation->tables()->attach($validated['table_ids']);

            // Update usage limit promo
            if ($promo && $promo->usage_limit !== null) {
                $promo->decrement('usage_limit');
                Log::info('Promo usage decremented', [
                    'promo_id' => $promo->id,
                    'new_usage_limit' => $promo->usage_limit,
                    'can_still_be_used' => $promo->fresh()->can_be_used
                ]);
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
                'notes' => 'Down Payment (DP) Reservasi - Menunggu Pembayaran',
            ]);

            Log::info('Payment created with pending status', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
            ]);

            // ❌ DIHAPUS: Tidak perlu update status meja ke 'available'
            // Table::where('id', $validated['table_id'])->update(['status' => 'available']);

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
                if ($promo) {
                    $totalPriceAfterDiscount = $totalPriceBeforeDiscount * (1 - ($promo->discount / 100));
                    Log::info('Promo applied to order', [
                        'promo_id' => $promo->id,
                        'discount_percent' => $promo->discount,
                        'total_before' => $totalPriceBeforeDiscount,
                        'total_after' => $totalPriceAfterDiscount
                    ]);
                }

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'reservation_id' => $reservation->id,
                    'total_price' => $totalPriceAfterDiscount,
                    'notes' => 'Pre-order dari reservasi',
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
            }

            DB::commit();

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

        // ✅ DIUBAH: Load tables (many-to-many)
        $reservation = Reservation::with([
            'tables', // ✅ DIUBAH: dari 'table' ke 'tables'
            'promo', 
            'payments',
            'orders.orderItems.menu'
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

        $latestPayment = $reservation->payments->sortByDesc('created_at')->first();
        $paymentStatus = $latestPayment ? $latestPayment->status : 'pending';
        $paymentDeadline = $reservation->payment_deadline ?? $reservation->created_at->addHours(24);
        
        return view('customer.reservation.success', compact(
            'reservation', 
            'paymentDeadline',
            'paymentStatus'
        ));
    }

    public function history()
    {
        // ✅ DIUBAH: Load tables (many-to-many)
        $reservations = Reservation::where('user_id', Auth::id())
            ->with([
                'tables', // ✅ DIUBAH: dari 'table' ke 'tables'
                'orders.orderItems.menu', 
                'promo', 
                'payments'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Check dan update status expired
        $updatedReservations = [];
        foreach ($reservations as $reservation) {
            if ($reservation->status === 'waiting_payment' && 
                $reservation->payment_deadline && 
                now()->greaterThan($reservation->payment_deadline)) {
                
                $reservation->update(['status' => 'cancelled']);
                Log::info("Reservation #{$reservation->id} auto-cancelled in history page");
                $updatedReservations[] = $reservation->id;
            }
        }

        // Hanya refresh jika ada yang di-update
        if (!empty($updatedReservations)) {
            $reservations = $reservations->fresh();
        }

        $formattedReservations = $reservations->map(function ($reservation) {
            $latestPayment = $reservation->payments->sortByDesc('created_at')->first();
            
            // ✅ DITAMBAH: Helper untuk menghitung jumlah meja dan kapasitas
            $totalTables = $reservation->tables->count();
            $totalCapacity = $reservation->tables->sum('capacity');
            
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
                'table_numbers' => $reservation->table_numbers, // ✅ DITAMBAH
                'total_tables' => $totalTables, // ✅ DITAMBAH (lebih konsisten)
                'total_capacity' => $totalCapacity, // ✅ DITAMBAH
                'payment_deadline' => $reservation->payment_deadline ? $reservation->payment_deadline->toISOString() : null,
                'created_at' => $reservation->created_at->toISOString(),
                'with_preorder' => $reservation->orders->count() > 0,
                // ✅ DIUBAH: dari 'table' ke 'tables' (array)
                'tables' => $reservation->tables->map(function ($table) {
                    return [
                        'id' => $table->id,
                        'number' => $table->number,
                        'capacity' => $table->capacity,
                        'location' => $table->location,
                        'location_label' => $table->location_label,
                    ];
                })->toArray(), // ✅ DITAMBAH: Convert ke array
                'orders' => $reservation->orders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'total_price' => $order->total_price,
                        'order_items' => $order->orderItems->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'qty' => $item->qty,
                                'price' => $item->price,
                                'menu' => $item->menu ? [
                                    'id' => $item->menu->id,
                                    'name' => $item->menu->name,
                                    'price' => $item->menu->price,
                                ] : null // ✅ DITAMBAH: Null safety
                            ];
                        })->toArray()
                    ];
                })->toArray(), // ✅ DITAMBAH: Convert ke array
                'payments' => $reservation->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'status' => $payment->status,
                        'payment_proof' => $payment->payment_proof,
                        'payment_type' => $payment->payment_type,
                        'verified_at' => $payment->verified_at ? $payment->verified_at->toISOString() : null,
                        'verified_by' => $payment->verified_by,
                        'created_at' => $payment->created_at->toISOString(),
                    ];
                })->toArray(), // ✅ DITAMBAH: Convert ke array
                'latest_payment_status' => $latestPayment ? $latestPayment->status : 'pending'
            ];
        });

        return view('customer.reservation.history', [
            'reservations' => $formattedReservations->toArray()
        ]);
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

        $payment = $reservation->payments()->first();

        if (!$payment) {
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
        } else {
            $payment->update([
                'status' => 'verifying',
                'payment_proof' => $filePath,
                'notes' => 'Down Payment (DP) Reservasi - Menunggu Verifikasi',
            ]);
        }

        Log::info('Payment updated on upload', [
            'reservation_id' => $reservation->id,
            'payment_id' => $payment->id,
            'old_status' => 'pending',
            'new_status' => 'verifying'
        ]);

        // Update reservation status
        $reservation->update([
            'status' => 'pending'
        ]);

        return redirect()->route('reservation.history')
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    /**
     * ✅ METHOD BARU: Update tables untuk reservasi yang sudah ada
     */
    public function updateTables(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

        // Cek apakah reservasi bisa di-edit
        if (!$reservation->can_edit) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak dapat diubah'
            ]);
        }

        $request->validate([
            'table_ids' => 'required|array|min:1',
            'table_ids.*' => 'exists:tables,id',
        ]);

        DB::beginTransaction();

        try {
            // Validasi ketersediaan semua meja baru
            foreach ($request->table_ids as $tableId) {
                $table = Table::find($tableId);
                if (!$table->isAvailableForDateTime($reservation->reservation_date, $reservation->reservation_time)) {
                    // Cek apakah meja ini sudah ter-attach ke reservasi ini
                    $alreadyAttached = $reservation->tables()->where('table_id', $tableId)->exists();
                    if (!$alreadyAttached) {
                        return response()->json([
                            'success' => false,
                            'message' => "Meja {$table->number} tidak tersedia"
                        ]);
                    }
                }
            }

            // Validasi kapasitas
            $selectedTables = Table::whereIn('id', $request->table_ids)->get();
            $totalCapacity = $selectedTables->sum('capacity');
            
            if ($totalCapacity < $reservation->guest_count) {
                return response()->json([
                    'success' => false,
                    'message' => "Kapasitas meja tidak mencukupi"
                ]);
            }

            // Sync tables
            $reservation->syncTables($request->table_ids);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Meja berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Update Tables Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}