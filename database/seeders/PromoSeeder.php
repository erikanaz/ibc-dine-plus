<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Promo;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Promo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $promos = [
            [
                'promo_code' => 'WELCOME10',
                'description' => 'Diskon 10% untuk pembelian pertama Anda di restoran kami',
                'discount' => 10,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(45),
                'usage_limit' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'WEEKEND15',
                'description' => 'Diskon spesial 15% setiap akhir pekan untuk makan malam',
                'discount' => 15,
                'start_date' => Carbon::now()->startOfWeek(),
                'end_date' => Carbon::now()->addMonths(2),
                'usage_limit' => 200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'NEWYEAR30',
                'description' => 'Selamat Tahun Baru! Diskon 30% untuk semua menu',
                'discount' => 30,
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(45),
                'usage_limit' => 300,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'MEMBER5',
                'description' => 'Diskon member 5% untuk setiap pembelian',
                'discount' => 5,
                'start_date' => Carbon::now()->subDays(180),
                'end_date' => Carbon::now()->addYear(),
                'usage_limit' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'RAMADHAN20',
                'description' => 'Spesial Ramadhan diskon 20% untuk berbuka puasa',
                'discount' => 20,
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(40),
                'usage_limit' => 400,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }

        $this->command->info('Sample promos created successfully!');
        $this->command->info('Total Promos: ' . Promo::count());

        // Statistik
        $activePromos = Promo::where('start_date', '<=', Carbon::now())
                            ->where(function($query) {
                                $query->where('end_date', '>=', Carbon::now())
                                      ->orWhereNull('end_date');
                            })
                            ->count();

        $upcomingPromos = Promo::where('start_date', '>', Carbon::now())->count();
        $expiredPromos = Promo::where('end_date', '<', Carbon::now())->count();

        $this->command->info('Active Promos: ' . $activePromos);
        $this->command->info('Upcoming Promos: ' . $upcomingPromos);
        $this->command->info('Expired Promos: ' . $expiredPromos);
    }
}
