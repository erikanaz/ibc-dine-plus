<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Reservations
        $totalReservations = Reservation::count();
        
        // Today's Reservations
        $todayReservations = Reservation::whereDate('reservation_date', today())->count();
        
        // Monthly Revenue (dari total_DP reservations yang confirmed/completed)
        $monthlyRevenue = Reservation::whereMonth('reservation_date', now()->month)
            ->whereYear('reservation_date', now()->year)
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_DP');

        // Monthly Reservations Count
        $monthlyReservations = Reservation::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Table Availability
        $totalTables = Table::count();
        $availableTables = Table::where('status', 'available')->count();
        
        // Today's Reservations with details - PERBAIKAN: Gunakan relasi tables (plural)
        $todaysReservationsList = Reservation::with(['user', 'tables']) // PERUBAHAN: tables bukan table
            ->whereDate('reservation_date', today())
            ->orderBy('reservation_time', 'asc')
            ->limit(5)
            ->get();
        
        // Upcoming Reservations (next 3 days) - PERBAIKAN: Gunakan relasi tables
        $upcomingReservations = Reservation::with(['user', 'tables']) // PERUBAHAN: tables bukan table
            ->where('reservation_date', '>=', today())
            ->where('reservation_date', '<=', today()->addDays(3))
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->limit(5)
            ->get();
        
        // Revenue Chart Data (Last 7 days dari total_DP)
        $revenueData = Reservation::where('reservation_date', '>=', now()->subDays(7))
            ->whereIn('status', ['confirmed', 'completed'])
            ->select(
                DB::raw('DATE(reservation_date) as date'),
                DB::raw('SUM(total_DP) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Table Status Summary
        $tableStatus = Table::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalReservations',
            'todayReservations',
            'monthlyRevenue',
            'totalTables',
            'availableTables',
            'todaysReservationsList',
            'upcomingReservations',
            'revenueData',
            'tableStatus',
            'monthlyReservations'
        ));
    }
}