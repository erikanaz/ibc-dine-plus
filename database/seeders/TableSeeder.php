<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['prefix' => 'A', 'capacity' => 4, 'count' => 7, 'location' => 'indoor'],
            ['prefix' => 'B', 'capacity' => 5, 'count' => 3, 'location' => 'indoor'],
            ['prefix' => 'C', 'capacity' => 6, 'count' => 17, 'location' => 'indoor'],
        ];

        foreach ($tables as $table) {
            for ($i = 1; $i <= $table['count']; $i++) {
                DB::table('tables')->insert([
                    'number' => $table['prefix'] . $i, // contoh: A1, A2, B1, C1, dst
                    'capacity' => $table['capacity'],
                    'status' => 'available',
                    'location' => $table['location'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}