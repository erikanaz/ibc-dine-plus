<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Table::insert([
            [
                'number' => 'A1',
                'capacity' => 4,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),      
            ],
            [
                'number' => 'A2',
                'capacity' => 2,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),      
            ],
            [
                'number' => 'B1',
                'capacity' => 6,
                'status' => 'unavailable',
                'created_at' => now(),
                'updated_at' => now(),      
            ],
            [
                'number' => 'VIP1',
                'capacity' => 8,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),      
            ],
        ]);
    }
}
