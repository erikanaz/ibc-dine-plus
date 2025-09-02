<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    //
    public function index()
    {
        $tables = Table::where('status', 'available')->get();
        // $menus = Menu::where('is_available', true)->get();
        $menus = Menu::where('is_available', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get()
                ->groupBy('category'); // <--- Group by kategori

        return view('customer.reservation.index', compact('tables', 'menus'));
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
            'payment_method' => 'required|in:qris,bank_transfer',
            'with_preorder' => 'required|boolean',
            'menu_items' => 'nullable|array',
            'menu_items.*.menu_id' => 'required_with:menu_items|exists:menus,id',
            'menu_items.*.jumlah' => 'required_with:menu_items|integer|min:1',
        ]);

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'guest_count' => $validated['guest_count'],
            'table_id' => $validated['table_id'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'with_preorder' => $validated['with_preorder'],
        ]);

        // Simpan menu jika ada preorder
        if ($reservation->with_preorder && isset($validated['menu_items'])) {
            foreach ($validated['menu_items'] as $item) {
                $reservation->menus()->attach($item['menu_id'], [
                    'quantity' => $item['jumlah']
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'reservation_id' => $reservation->id
        ]);
    }

    public function success(Request $request)
    {
        $reservationId = $request->query('id');
        $reservation = Reservation::findOrFail($reservationId);
        
        return view('customer.reservation.success', compact('reservation'));
    }

    // app/Http/Controllers/ReservationController.php

    // public function history(Request $request)
    // {
    //     $reservations = Reservation::where('user_id', auth()->id())
    //         ->with('table')
    //         ->latest()
    //         ->filter($request->only('filter'))
    //         ->paginate(10);

    //     return view('customer.reservation.history', [
    //         'reservations' => $reservations
    //     ]);
    // }

    // public function pilihMeja(Request $request)
    // {
    //     $tableId = $request->input('table_id');
        
    //     // Lanjut ke step berikutnya atau simpan session
    //     session(['selected_table' => $tableId]);

    //     return redirect()->route('customer.reservation.index'); // ke step 3 misalnya
    // }

}
