<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number')->get();
        
        // Hitung statistik seperti di admin controller
        $totalTables = $tables->count();
        $availableTables = $tables->where('status', 'available')->count();
        $occupiedTables = $tables->where('status', 'occupied')->count();
        $reservedTables = $tables->where('status', 'reserved')->count();
        $maintenanceTables = $tables->where('status', 'maintenance')->count();
        
        return view('customer.tables.index', compact(
            'tables',
            'totalTables',
            'availableTables',
            'occupiedTables', 
            'reservedTables',
            'maintenanceTables'
        ));
    }
    // public function index(Request $request)
    // {
    //     $query = Table::query();

    //     // Filter by capacity if provided
    //     if ($request->has('capacity') && $request->capacity !== 'all') {
    //         $query->where('capacity', $request->capacity);
    //     }

    //     // Filter by location if provided
    //     if ($request->has('location') && $request->location !== 'all') {
    //         $query->where('location', $request->location);
    //     }

    //     // Search by table number
    //     if ($request->has('search')) {
    //         $query->where('number', 'like', '%' . $request->search . '%');
    //     }

    //     // Only show available tables for customers
    //     $query->where('status', 'available');

    //     $tables = $query->orderBy('capacity')->orderBy('number')->paginate(12);

    //     return view('customer.tables.index', compact('tables'));
    // }
}