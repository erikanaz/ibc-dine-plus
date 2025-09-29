<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Table;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // 1 contoh reservasi
            $reservation = Reservation::create([
                'user_id' => 1,
                'table_id' => 1,
                'reservation_date' => now()->addDays(1)->toDateString(),
                'reservation_time' => '20:00:00',
                'guest_count' => 4,
                'notes' => 'Minta dekat jendela',
                'total_DP' => 20000,
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),  
            ]);

        // // ambil menu id 1 dan 3
        // $menuIds = [1, 3];

        // foreach ($menuIds as $menuId) {
        //     $reservation->menus()->attach($menuId, ['quantity' => 2]);
        // }
        
    }
}
