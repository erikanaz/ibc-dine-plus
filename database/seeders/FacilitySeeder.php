<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Wi-Fi Gratis',
                'description' => 'Kecepatan tinggi, dengan password',
                'icon' => 'wifi',
                'is_available' => true,
                'location' => 'Indoor dan outdoor'
            ],
            [
                'name' => 'Stop Kontak / Colokan',
                'description' => 'Di hampir semua meja',
                'icon' => 'socket',
                'is_available' => true,
                'location' => 'Indoor'
            ],
            [
                'name' => 'Kursi Bayi (High Chair)',
                'description' => 'Untuk pelanggan dengan anak kecil',
                'icon' => 'baby-chair',
                'is_available' => true,
                'location' => 'Indoor'
            ],
            [
                'name' => 'AC / Pendingin Ruangan',
                'description' => 'Menyala pada jam operasional',
                'icon' => 'ac',
                'is_available' => true,
                'location' => 'Indoor'
            ],
            [
                'name' => 'Toilet',
                'description' => 'Toilet pria dan wanita terpisah',
                'icon' => 'toilet',
                'is_available' => true,
                'location' => 'Indoor'
            ],
            [
                'name' => 'Smoking Area',
                'description' => 'Terpisah dari area utama',
                'icon' => 'smoking-area',
                'is_available' => true,
                'location' => 'Indoor dan outdoor'
            ],
            [
                'name' => 'Area Parkir',
                'description' => 'Cukup untuk mobil dan motor',
                'icon' => 'parking',
                'is_available' => true,
                'location' => 'Outdoor'
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
        
        $this->command->info('7 fasilitas default berhasil ditambahkan!');
    }
}