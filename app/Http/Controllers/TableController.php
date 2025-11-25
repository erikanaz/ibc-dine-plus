<?php

namespace App\Http\Controllers;

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
        
        return view('customer.tables', compact(
            'tables',
            'totalTables',
            'availableTables',
            'occupiedTables', 
            'reservedTables',
            'maintenanceTables'
        ));
    }
}
