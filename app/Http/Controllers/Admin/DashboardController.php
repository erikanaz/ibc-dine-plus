<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalReservations = Reservation::count();
        $todayReservations = Reservation::whereDate('reservation_time', now())->count();
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)->sum('total_price');
        $availableTables = Table::where('status', 'available')->count();
        $totalTables = Table::count();

        $todayReservationList = Reservation::whereDate('reservation_time', now())
            ->with('user', 'table')
            ->get();

        $upcomingReservations = Reservation::whereDate('reservation_time', '>=', now())
            ->orderBy('reservation_time')
            ->with('user', 'table')
            ->take(5)
            ->get();

        $tableStatus = Table::all();

        return view('admin.dashboard', compact(
            'totalReservations',
            'todayReservations',
            'monthlyRevenue',
            'availableTables',
            'totalTables',
            'todayReservationList',
            'upcomingReservations',
            'tableStatus'
        ));
    }
}
