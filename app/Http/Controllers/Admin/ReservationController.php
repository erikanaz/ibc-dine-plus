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
    public function index()
    {
        $reservations = Reservation::with(['user', 'table', 'promo', 'order.orderItems.menu'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        $statusCounts = [
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'expired' => Reservation::where('status', 'expired')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'statusCounts'));
    }

    public function create()
    {
        // $users = User::where('role', 'customer')->get();

         // HAPUS filter role, ambil semua user
        $users = User::all(); // Ganti dari where('role', 'customer')

        $tables = Table::where('status', 'available')->get();
        $menus = Menu::where('is_available', true)->get();
        // Di controller, ganti dengan logika berdasarkan tanggal
        $promos = Promo::where(function($query) {
            $query->where('start_date', '<=', now())
                ->orWhereNull('start_date');
        })->where(function($query) {
            $query->where('end_date', '>=', now())
                ->orWhereNull('end_date');
        })->get();
        
        return view('admin.reservations.create', compact('users', 'tables', 'menus', 'promos'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'table_id' => 'required|exists:tables,id',
    //         'reservation_date' => 'required|date',
    //         'reservation_time' => 'required',
    //         'guest_count' => 'required|integer|min:1',
    //         'notes' => 'nullable|string',
    //         'promo_id' => 'nullable|exists:promos,id',
    //         'total_DP' => 'required|numeric|min:0',
    //         'menus' => 'required|array|min:1',
    //         'menus.*.menu_id' => 'required|exists:menus,id',
    //         'menus.*.quantity' => 'required|integer|min:1',
    //     ]);

    //     DB::transaction(function () use ($validated) {
    //         // Create reservation
    //         $reservation = Reservation::create([
    //             'user_id' => $validated['user_id'],
    //             'table_id' => $validated['table_id'],
    //             'reservation_date' => $validated['reservation_date'],
    //             'reservation_time' => $validated['reservation_time'],
    //             'guest_count' => $validated['guest_count'],
    //             'notes' => $validated['notes'],
    //             'promo_id' => $validated['promo_id'],
    //             'total_DP' => $validated['total_DP'],
    //             'status' => 'confirmed',
    //         ]);

    //         // Calculate total price from menus
    //         $totalPrice = 0;
    //         foreach ($validated['menus'] as $menuItem) {
    //             $menu = Menu::find($menuItem['menu_id']);
    //             $totalPrice += $menu->price * $menuItem['quantity'];
    //         }

    //         // Apply promo discount if exists
    //         if ($validated['promo_id']) {
    //             $promo = Promo::find($validated['promo_id']);
    //             // Implement your promo logic here
    //             // For example: $totalPrice = $totalPrice - ($totalPrice * $promo->discount_percentage / 100);
    //         }

    //         // Create order
    //         $order = Order::create([
    //             'user_id' => $validated['user_id'],
    //             'reservation_id' => $reservation->id,
    //             'total_price' => $totalPrice,
    //             'notes' => $validated['notes'],
    //         ]);

    //         // Create order items
    //         foreach ($validated['menus'] as $menuItem) {
    //             $menu = Menu::find($menuItem['menu_id']);
                
    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'menu_id' => $menuItem['menu_id'],
    //                 'qty' => $menuItem['quantity'],
    //                 'price' => $menu->price,
    //             ]);
    //         }

    //         // Update table status
    //         Table::where('id', $validated['table_id'])->update(['status' => 'reserved']);
    //     });

    //     return redirect()->route('admin.reservations.index')
    //         ->with('success', 'Reservasi berhasil dibuat.');
    // }
    
    public function show(Reservation $reservation)
    {
        // Load semua relasi yang diperlukan
        $reservation->load([
            'user', 
            'table', 
            'promo', 
            'order.orderItems.menu'
        ]);

        // Get menus untuk form tambah menu
        $menus = Menu::where('is_available', true)->get();

        return view('admin.reservations.show', compact('reservation', 'menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'promo_id' => 'nullable|exists:promos,id',
            'total_DP' => 'required|numeric|min:0',
            'menus' => 'required|array|min:1', // Menu dipilih saat reservasi
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. CREATE RESERVASI
            $reservation = Reservation::create([
                'user_id' => $validated['user_id'],
                'table_id' => $validated['table_id'],
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'guest_count' => $validated['guest_count'],
                'notes' => $validated['notes'],
                'promo_id' => $validated['promo_id'],
                'total_DP' => $validated['total_DP'],
                'status' => 'confirmed',
            ]);

            // 2. HITUNG TOTAL HARGA
            $totalPrice = 0;
            foreach ($validated['menus'] as $menuItem) {
                $menu = Menu::find($menuItem['menu_id']);
                $totalPrice += $menu->price * $menuItem['quantity'];
            }

            // 3. APPLY PROMO JIKA ADA
            if ($validated['promo_id']) {
                $promo = Promo::find($validated['promo_id']);
                if ($promo) {
                    if ($promo->type == 'percent') {
                        $discount = $totalPrice * ($promo->discount / 100);
                        $totalPrice -= $discount;
                    } else {
                        $totalPrice -= $promo->discount;
                    }
                }
            }

            // 4. CREATE ORDER (OTOMATIS)
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'reservation_id' => $reservation->id, // Link ke reservasi
                'total_price' => $totalPrice,
                'notes' => $validated['notes'],
            ]);

            // 5. CREATE ORDER ITEMS
            foreach ($validated['menus'] as $menuItem) {
                $menu = Menu::find($menuItem['menu_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menuItem['menu_id'],
                    'qty' => $menuItem['quantity'],
                    'price' => $menu->price,
                ]);
            }

            // 6. UPDATE TABLE STATUS
            Table::where('id', $validated['table_id'])->update(['status' => 'reserved']);
        });

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservasi beserta pesanan berhasil dibuat.');
    }

    public function edit(Reservation $reservation)
    {
        // HAPUS filter role, ambil semua user
        $users = User::all(); // Ganti dari where('role', 'customer')

        $tables = Table::whereIn('status', ['available', 'reserved'])->get();
        $menus = Menu::where('is_available', true)->get();
        // $promos = Promo::where('is_active', true)->get();
        // Di controller, ganti dengan logika berdasarkan tanggal
        $promos = Promo::where(function($query) {
            $query->where('start_date', '<=', now())
                ->orWhereNull('start_date');
        })->where(function($query) {
            $query->where('end_date', '>=', now())
                ->orWhereNull('end_date');
        })->get();
        
        $reservation->load(['user', 'table', 'promo', 'order.orderItems.menu']);
        
        return view('admin.reservations.edit', compact('reservation', 'users', 'tables', 'menus', 'promos'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'promo_id' => 'nullable|exists:promos,id',
            'total_DP' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled,completed,expired',
        ]);

        // If table is changed, update table statuses
        if ($reservation->table_id != $validated['table_id']) {
            // Free the old table
            Table::where('id', $reservation->table_id)->update(['status' => 'available']);
            // Reserve the new table
            Table::where('id', $validated['table_id'])->update(['status' => 'reserved']);
        }

        // If status changed to cancelled or completed, free the table
        if (in_array($validated['status'], ['cancelled', 'completed', 'expired']) && 
            !in_array($reservation->status, ['cancelled', 'completed', 'expired'])) {
            Table::where('id', $validated['table_id'])->update(['status' => 'available']);
        }

        // If status changed from cancelled/completed to active, reserve the table
        if (in_array($reservation->status, ['cancelled', 'completed', 'expired']) && 
            in_array($validated['status'], ['pending', 'confirmed'])) {
            Table::where('id', $validated['table_id'])->update(['status' => 'reserved']);
        }

        $reservation->update($validated);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        DB::transaction(function () use ($reservation) {
            // Free the table
            Table::where('id', $reservation->table_id)->update(['status' => 'available']);
            
            // Delete order and order items
            if ($reservation->order) {
                $reservation->order->orderItems()->delete();
                $reservation->order()->delete();
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

        // Create new order item
        OrderItem::create([
            'order_id' => $reservation->order->id,
            'menu_id' => $validated['menu_id'],
            'qty' => $validated['quantity'],
            'price' => $menu->price,
        ]);

        // Update order total
        $this->updateOrderTotal($reservation->order);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function updateMenu(Request $request, Reservation $reservation, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $orderItem->update([
            'qty' => $validated['quantity'],
        ]);

        // Update order total
        $this->updateOrderTotal($reservation->order);

        return redirect()->back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function removeMenu(Reservation $reservation, OrderItem $orderItem)
    {
        $orderItem->delete();

        // Update order total
        $this->updateOrderTotal($reservation->order);

        return redirect()->back()->with('success', 'Menu berhasil dihapus.');
    }

    private function updateOrderTotal(Order $order)
    {
        $total = $order->orderItems->sum(function ($item) {
            return $item->price * $item->qty;
        });

        $order->update(['total_price' => $total]);
    }

    // Quick status update
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,expired',
        ]);

        $oldStatus = $reservation->status;
        $newStatus = $validated['status'];

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

        $reservation->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function printInvoice(Reservation $reservation)
    {
        $reservation->load(['user', 'table', 'promo', 'order.orderItems.menu']);
        
        return view('admin.reservations.invoice', compact('reservation'));
    }
}