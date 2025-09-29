<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $selectedTableId = $request->get('selected_table');
        $selectedTable = null;

        if ($selectedTableId) {
            $selectedTable = Table::find($selectedTableId);
        }

        $tables = Table::orderBy('number')->get();
        
        // Hitung statistik berdasarkan kapasitas
        $totalTables = $tables->count();
        $tables4Person = $tables->where('capacity', 4)->count();
        $tables5Person = $tables->where('capacity', 5)->count();
        $tables6Person = $tables->where('capacity', 6)->count();
        
        // Status statistics
        $availableTables = $tables->where('status', Table::STATUS_AVAILABLE)->count();
        $occupiedTables = $tables->where('status', Table::STATUS_OCCUPIED)->count();
        $reservedTables = $tables->where('status', Table::STATUS_RESERVED)->count();
        
        // Kelompokkan meja berdasarkan kapasitas
        $tablesByCapacity = $tables->groupBy('capacity');

        // Aktivitas terkini
        $recentActivities = $this->getRecentActivities();

        return view('admin.tables.index', compact(
            'tables',
            'totalTables',
            'tables4Person',
            'tables5Person', 
            'tables6Person',
            'availableTables',
            'occupiedTables',
            'reservedTables',
            'tablesByCapacity',
            'recentActivities',
            'selectedTable'
        ));
    }

    public function create()
    {
        $locations = [
            'indoor' => 'Indoor',
            'outdoor' => 'Outdoor'
        ];
        
        $statuses = [
            'available' => 'Tersedia',
            'occupied' => 'Terisi',
            'reserved' => 'Reservasi', 
            'maintenance' => 'Perbaikan'
        ];

        return view('admin.tables.create', compact('locations', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:tables,number',
            'capacity' => 'required|integer|min:1|max:20',
            'location' => 'required|in:indoor,outdoor',
            'status' => 'required|in:available,occupied,reserved,maintenance'
        ]);

        Table::create($request->all());

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Table $table)
    {
        $locations = [
            'indoor' => 'Indoor',
            'outdoor' => 'Outdoor'
        ];
        
        $statuses = [
            'available' => 'Tersedia',
            'occupied' => 'Terisi',
            'reserved' => 'Reservasi',
            'maintenance' => 'Perbaikan'
        ];

        return view('admin.tables.edit', compact('table', 'locations', 'statuses'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'number' => 'required|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1|max:20',
            'location' => 'required|in:indoor,outdoor',
            'status' => 'required|in:available,occupied,reserved,maintenance'
        ]);

        $table->update($request->all());

        return redirect()->route('admin.tables.index', ['selected_table' => $table->id])
            ->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }

    public function updateStatus(Request $request, Table $table)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved,maintenance'
        ]);

        $table->update(['status' => $request->status]);

        return redirect()->route('admin.tables.index', ['selected_table' => $table->id])
            ->with('success', 'Status meja berhasil diperbarui.');
    }

    private function getRecentActivities()
    {
        return [
            [
                'table_number' => '02',
                'customer_name' => 'Budi Santoso',
                'status' => 'occupied',
                'time' => '30 menit lalu',
                'people' => 4
            ],
            [
                'table_number' => '05',
                'customer_name' => 'Siti Rahayu',
                'status' => 'reserved',
                'time' => '2 jam lalu',
                'people' => 6
            ],
            [
                'table_number' => '03',
                'customer_name' => null,
                'status' => 'available',
                'time' => '3 jam lalu',
                'people' => null
            ]
        ];
    }
}