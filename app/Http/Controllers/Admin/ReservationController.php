<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['table', 'promo', 'orders.orderItems.menu']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'cancelled') {
                // Gabungkan cancelled dan expired
                $query->whereIn('status', ['cancelled', 'expired']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('table', function($q) use ($search) {
                      $q->where('number', 'like', "%{$search}%");
                  });
            });
        }

        $reservations = $query->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        // Hitung total keseluruhan (tanpa filter)
        $totalAllReservations = Reservation::count();

        // Hitung status counts
        $statusCounts = [
            'waiting_payment' => Reservation::where('status', 'waiting_payment')->count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'expired' => Reservation::where('status', 'expired')->count(),
        ];

        return view('admin.reservations.index', compact(
            'reservations', 
            'statusCounts', 
            'totalAllReservations'
        ));
    }

    public function create()
    {
        $tables = Table::where('status', 'available')->get();
        $menus = Menu::where('is_available', true)->get();
        
        $promos = Promo::where(function($query) {
            $query->where('start_date', '<=', now())
                ->orWhereNull('start_date');
        })->where(function($query) {
            $query->where('end_date', '>=', now())
                ->orWhereNull('end_date');
        })->get();
        
        return view('admin.reservations.create', compact('tables', 'menus', 'promos'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'customer_email' => 'required|email|max:255',
        'table_id' => 'required|exists:tables,id',
        'reservation_date' => 'required|date',
        'reservation_time' => 'required',
        'guest_count' => 'required|integer|min:1',
        'notes' => 'nullable|string',
        'promo_id' => 'nullable|exists:promos,id',
        'total_DP' => 'required|numeric|min:0',
        'status' => 'required|in:pending,confirmed,cancelled,completed',
        'menus' => 'sometimes|array', // Ubah dari required menjadi sometimes
        'menus.*.menu_id' => 'required_with:menus|exists:menus,id',
        'menus.*.quantity' => 'required_with:menus|integer|min:1',
        'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Tambah validasi file
    ]);

    DB::transaction(function () use ($validated, $request) {
        // 1. CREATE RESERVASI
        $reservation = Reservation::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'table_id' => $validated['table_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'guest_count' => $validated['guest_count'],
            'notes' => $validated['notes'] ?? null,
            'promo_id' => $validated['promo_id'] ?? null,
            'total_DP' => $validated['total_DP'],
            'status' => $validated['status'],
            'user_id' => null,
            'created_by' => 'admin',
        ]);

        // 2. HANDLE PAYMENT PROOF UPLOAD
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            
            // Create payment record
            $reservation->payments()->create([
                'amount' => $validated['total_DP'],
                'payment_method' => 'transfer',
                // ✅ PERBAIKAN: GUNAKAN 'paid' UNTUK STATUS CONFIRMED
                'status' => $validated['status'] == 'confirmed' ? 'paid' : 'verifying',
                'proof_image' => $paymentProofPath,
                'paid_at' => $validated['total_DP'] > 0 ? now() : null,
                'verified_at' => $validated['status'] == 'confirmed' ? now() : null,
                // 'verified_by' => $validated['status'] == 'confirmed' ? auth()->id() : null,
            ]);
        } else if ($validated['total_DP'] > 0) {
            // Create payment record without proof
            $reservation->payments()->create([
                'amount' => $validated['total_DP'],
                'payment_method' => 'transfer',
                // ✅ PERBAIKAN: GUNAKAN 'paid' UNTUK STATUS CONFIRMED
                'status' => $validated['status'] == 'confirmed' ? 'paid' : 'verifying',
                'paid_at' => now(),
                'verified_at' => $validated['status'] == 'confirmed' ? now() : null,
                // 'verified_by' => $validated['status'] == 'confirmed' ? auth()->id() : null,
            ]);
        }

        // 3. HANDLE MENU ORDER JIKA ADA
        if (!empty($validated['menus'])) {
            $totalPrice = 0;
            foreach ($validated['menus'] as $menuItem) {
                $menu = Menu::find($menuItem['menu_id']);
                $totalPrice += $menu->price * $menuItem['quantity'];
            }

            // 4. APPLY PROMO JIKA ADA
            $finalTotal = $totalPrice;
            if (!empty($validated['promo_id'])) {
                $promo = Promo::find($validated['promo_id']);
                if ($promo) {
                    if ($promo->type == 'percent') {
                        $discount = $totalPrice * ($promo->discount / 100);
                        $finalTotal = $totalPrice - $discount;
                    } else {
                        $finalTotal = $totalPrice - $promo->discount;
                    }
                    // Ensure final total is not negative
                    $finalTotal = max(0, $finalTotal);
                }
            }

            // 5. CREATE ORDER
            $order = Order::create([
                'reservation_id' => $reservation->id,
                'user_id' => null,
                'total_price' => $finalTotal,
                'notes' => $validated['notes'] ?? null,
            ]);

            // 6. CREATE ORDER ITEMS
            foreach ($validated['menus'] as $menuItem) {
                $menu = Menu::find($menuItem['menu_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menuItem['menu_id'],
                    'qty' => $menuItem['quantity'],
                    'price' => $menu->price,
                ]);
            }
        }

        // 7. UPDATE TABLE STATUS JIKA RESERVASI CONFIRMED
        if ($validated['status'] == 'confirmed') {
            Table::where('id', $validated['table_id'])->update(['status' => 'reserved']);
        }
    });

    return redirect()->route('admin.reservations.index')
        ->with('success', 'Reservasi berhasil dibuat.');
}

    public function show(Reservation $reservation)
    {
        $reservation->load([
            'table', 
            'promo', 
            'orders.orderItems.menu',
            'payments'
        ]);

        $menus = Menu::where('is_available', true)->get();

        return view('admin.reservations.show', compact('reservation', 'menus'));
    }

    public function edit(Reservation $reservation)
    {
        $tables = Table::whereIn('status', ['available', 'reserved'])->get();
        $menus = Menu::where('is_available', true)->get();
        
        $promos = Promo::where(function($query) {
            $query->where('start_date', '<=', now())
                ->orWhereNull('start_date');
        })->where(function($query) {
            $query->where('end_date', '>=', now())
                ->orWhereNull('end_date');
        })->get();
        
        $reservation->load(['table', 'promo', 'orders.orderItems.menu']);
        
        return view('admin.reservations.edit', compact('reservation', 'tables', 'menus', 'promos'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'promo_id' => 'nullable|exists:promos,id',
            'total_DP' => 'required|numeric|min:0',
            'status' => 'required|in:waiting_payment,pending,confirmed,cancelled,completed,expired',
        ]);

        DB::transaction(function () use ($validated, $reservation) {
            $oldTableId = $reservation->table_id;
            $oldStatus = $reservation->status;
            $newTableId = $validated['table_id'];
            $newStatus = $validated['status'];

            // Handle table changes
            if ($oldTableId != $newTableId) {
                // Free the old table
                Table::where('id', $oldTableId)->update(['status' => 'available']);
                // Reserve the new table if status is confirmed
                if ($newStatus == 'confirmed') {
                    Table::where('id', $newTableId)->update(['status' => 'reserved']);
                }
            }

            // Handle status changes
            if ($oldStatus != $newStatus) {
                // If changing to confirmed, reserve the table
                if ($newStatus == 'confirmed') {
                    Table::where('id', $newTableId)->update(['status' => 'reserved']);
                }
                // If changing from confirmed to other status, free the table
                elseif ($oldStatus == 'confirmed' && in_array($newStatus, ['cancelled', 'completed', 'expired'])) {
                    Table::where('id', $newTableId)->update(['status' => 'available']);
                }
                // ✅ UPDATE STATUS PAYMENT BERDASARKAN STATUS RESERVASI
                $payment = $reservation->payments()->first();
                
                if ($payment) {
                    switch ($newStatus) {
                        case 'confirmed':
                            // ✅ GUNAKAN 'paid' BUKAN 'verified'
                            $payment->update([
                                'status' => 'paid',
                                'verified_at' => now(),
                                // 'verified_by' => auth()->id()
                            ]);
                            break;
                        case 'completed':
                            // ✅ GUNAKAN 'paid' UNTUK STATUS COMPLETED JUGA
                            if (in_array($payment->status, ['verifying', 'verified'])) {
                                $payment->update(['status' => 'paid']);
                            }
                            break;
                        case 'cancelled':
                        case 'expired':
                            $payment->update(['status' => 'failed']);
                            break;
                        case 'pending':
                            // Jika kembali ke pending, payment kembali ke verifying
                            if ($payment->status === 'paid') {
                                $payment->update(['status' => 'verifying']);
                            }
                            break;
                    }
                }
            }

            // Update reservation
            $reservation->update($validated);
        });

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        DB::transaction(function () use ($reservation) {
            // Free the table
            Table::where('id', $reservation->table_id)->update(['status' => 'available']);
            
            // Delete order and order items
            if ($reservation->orders->count() > 0) {
                foreach ($reservation->orders as $order) {
                    $order->orderItems()->delete();
                    $order->delete();
                }
            }
            
            $reservation->delete();
        });

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }

    public function addMenu(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($validated['menu_id']);

        DB::transaction(function () use ($reservation, $validated, $menu) {
            // Get the first order or create new one
            $order = $reservation->orders->first();
            if (!$order) {
                $order = Order::create([
                    'reservation_id' => $reservation->id,
                    'user_id' => null,
                    'total_price' => 0,
                    'notes' => $reservation->notes,
                ]);
            }

            // Create new order item
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $validated['menu_id'],
                'qty' => $validated['quantity'],
                'price' => $menu->price,
            ]);

            // Update order total
            $this->updateOrderTotal($order);
        });

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function updateMenu(Request $request, Reservation $reservation, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($orderItem, $validated, $reservation) {
            $orderItem->update([
                'qty' => $validated['quantity'],
            ]);

            // Update order total
            if ($order = $orderItem->order) {
                $this->updateOrderTotal($order);
            }
        });

        return redirect()->back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function removeMenu(Reservation $reservation, OrderItem $orderItem)
    {
        DB::transaction(function () use ($orderItem, $reservation) {
            $order = $orderItem->order;
            $orderItem->delete();

            // Update order total
            if ($order) {
                $this->updateOrderTotal($order);
            }
        });

        return redirect()->back()->with('success', 'Menu berhasil dihapus.');
    }

    private function updateOrderTotal(Order $order)
    {
        $total = $order->orderItems->sum(function ($item) {
            return $item->price * $item->qty;
        });

        $order->update(['total_price' => $total]);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting_payment,pending,confirmed,cancelled,completed,expired',
        ]); 

        $oldStatus = $reservation->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($reservation, $oldStatus, $newStatus) {
            // Handle table status changes
            if (in_array($newStatus, ['cancelled', 'completed', 'expired']) && 
                !in_array($oldStatus, ['cancelled', 'completed', 'expired'])) {
                // Free the table
                Table::where('id', $reservation->table_id)->update(['status' => 'available']);
            }

            if (in_array($oldStatus, ['cancelled', 'completed', 'expired']) && 
                in_array($newStatus, ['pending', 'confirmed'])) {
                // Reserve the table again
                Table::where('id', $reservation->table_id)->update(['status' => 'reserved']);
            }

            // ✅ UPDATE STATUS PAYMENT BERDASARKAN STATUS RESERVASI
            $payment = $reservation->payments()->first();

            if ($payment) {
                switch ($newStatus) {
                    case 'confirmed':
                        // ✅ GUNAKAN 'paid' BUKAN 'verified'
                        $payment->update([
                            'status' => 'paid',
                            'verified_at' => now(),
                            // 'verified_by' => auth()->id()
                        ]);
                        break;
                    case 'completed':
                        // ✅ GUNAKAN 'paid' UNTUK STATUS COMPLETED JUGA
                        if (in_array($payment->status, ['verifying', 'verified'])) {
                            $payment->update(['status' => 'paid']);
                        }
                        break;
                    case 'cancelled':
                    case 'expired':
                        $payment->update(['status' => 'failed']);
                        break;
                    case 'pending':
                        // Jika kembali ke pending, payment kembali ke verifying
                        if ($payment->status === 'paid') {
                            $payment->update(['status' => 'verifying']);
                        }
                        break;
                }
            }

            $reservation->update(['status' => $newStatus]);
        });

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function printInvoice(Reservation $reservation)
    {
        $reservation->load(['table', 'promo', 'orders.orderItems.menu']);
        
        return view('admin.reservations.invoice', compact('reservation'));
    }
}