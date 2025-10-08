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
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Promo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $promos = [
            // Promo Aktif - Diskon Persentase
            [
                'promo_code' => 'WELCOME10',
                'description' => 'Diskon 10% untuk pembelian pertama Anda di restoran kami',
                'discount' => 10.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(45),
                'usage_limit' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'WEEKEND15',
                'description' => 'Diskon spesial 15% setiap akhir pekan untuk makan malam',
                'discount' => 15.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->startOfWeek(),
                'end_date' => Carbon::now()->addMonths(2),
                'usage_limit' => 200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'FAMILY20',
                'description' => 'Diskon 20% untuk reservasi keluarga minimal 4 orang',
                'discount' => 20.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'usage_limit' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Promo Aktif - Diskon Nominal
            [
                'promo_code' => 'CASHBACK25K',
                'description' => 'Cashback Rp 25.000 untuk pembelian minimal Rp 150.000',
                'discount' => 25000.00,
                'type' => 'fixed',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(30),
                'usage_limit' => 150,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'DISKON50K',
                'description' => 'Potongan langsung Rp 50.000 untuk pembelian minimal Rp 300.000',
                'discount' => 50000.00,
                'type' => 'fixed',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(90),
                'usage_limit' => 75,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Promo Akan Datang
            [
                'promo_code' => 'NEWYEAR30',
                'description' => 'Selamat Tahun Baru! Diskon 30% untuk semua menu',
                'discount' => 30.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(45),
                'usage_limit' => 300,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'VALENTINE25',
                'description' => 'Spesial Valentine, diskon 25% untuk pasangan romantis',
                'discount' => 25.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'usage_limit' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Promo Kadaluarsa
            [
                'promo_code' => 'GRANDOPENING',
                'description' => 'Diskon pembukaan restoran 40% untuk 100 customer pertama',
                'discount' => 40.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->subDays(90),
                'end_date' => Carbon::now()->subDays(30),
                'usage_limit' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'CHRISTMAS35',
                'description' => 'Spesial Natal diskon 35% dan free dessert',
                'discount' => 35.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(15),
                'usage_limit' => 200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Promo Tanpa Batas Penggunaan
            [
                'promo_code' => 'MEMBER5',
                'description' => 'Diskon member 5% untuk setiap pembelian',
                'discount' => 5.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->subDays(180),
                'end_date' => Carbon::now()->addYear(),
                'usage_limit' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'SENIOR10',
                'description' => 'Diskon 10% khusus untuk customer usia senior (60+ tahun)',
                'discount' => 10.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => null, // Tidak ada tanggal berakhir
                'usage_limit' => null, // Tidak ada batas penggunaan
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Promo dengan Diskon Nominal Besar
            [
                'promo_code' => 'FREEDELIVERY',
                'description' => 'Gratis biaya pengantaran untuk order minimal Rp 200.000',
                'discount' => 15000.00,
                'type' => 'fixed',
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => Carbon::now()->addDays(23),
                'usage_limit' => 500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'BIRTHDAYFREE',
                'description' => 'Free dessert untuk customer yang berulang tahun',
                'discount' => 20000.00,
                'type' => 'fixed',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addYear(),
                'usage_limit' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Promo Spesial Event
            [
                'promo_code' => 'RAMADHAN20',
                'description' => 'Spesial Ramadhan diskon 20% untuk berbuka puasa',
                'discount' => 20.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(40),
                'usage_limit' => 400,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'LEBARAN50',
                'description' => 'Spesial Lebaran diskon 50% untuk pembelian katering',
                'discount' => 50.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->addDays(45),
                'end_date' => Carbon::now()->addDays(60),
                'usage_limit' => 80,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Promo Quick Response
            [
                'promo_code' => 'FLASH25',
                'description' => 'Flash sale! Diskon 25% hanya 3 hari',
                'discount' => 25.00,
                'type' => 'percent',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(3),
                'usage_limit' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'promo_code' => 'MIDNIGHT15',
                'description' => 'Diskon 15% untuk order jam 10 malam - 2 pagi',
                'discount' => 15.00,
                'type' => 'percent',
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(28),
                'usage_limit' => 120,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }

        $this->command->info('Sample promos created successfully!');
        $this->command->info('Total Promos: ' . Promo::count());
        
        // Tampilkan statistik
        $activePromos = Promo::where('start_date', '<=', Carbon::now())
                            ->where(function($query) {
                                $query->where('end_date', '>=', Carbon::now())
                                      ->orWhereNull('end_date');
                            })
                            ->count();
        
        $upcomingPromos = Promo::where('start_date', '>', Carbon::now())->count();
        $expiredPromos = Promo::where('end_date', '<', Carbon::now())->count();
        $percentPromos = Promo::where('type', 'percent')->count();
        $fixedPromos = Promo::where('type', 'fixed')->count();

        $this->command->info('Active Promos: ' . $activePromos);
        $this->command->info('Upcoming Promos: ' . $upcomingPromos);
        $this->command->info('Expired Promos: ' . $expiredPromos);
        $this->command->info('Percent Discount Promos: ' . $percentPromos);
        $this->command->info('Fixed Discount Promos: ' . $fixedPromos);
    }
}