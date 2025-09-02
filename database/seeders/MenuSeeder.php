<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            //signatures
            [
                'name' => 'Gurame Bakar',
                'description' => 'Gurame bakar berbumbu manis gurih khas Jawa',
                'price' => 65000,
                'image' => 'gurame_bakar.avif',
                'category' => 'signatures',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gurame Asam Manis',
                'description' => 'Gurame goreng renyah disiram saus asam manis segar',
                'price' => 75000,
                'image' => 'gurame_asam_manis.avif',
                'category' => 'signatures',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gurame Goreng',
                'description' => 'Dengan bumbu khas racikan spesial IBC',
                'price' => 70000,
                'image' => 'gurame_goreng.avif',
                'category' => 'signatures',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //vegetables
            [
                'name' => 'Sayur Asem',
                'description' => 'Sup sayur asem Jawa dengan bumbu asam segar',
                'price' => 25000,
                'image' => 'sayur_asem.avif',
                'category' => 'vegetables',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tumis Pucuk Labu',
                'description' => 'Tumis pucuk labu dengan bumbu bawang putih dan cabai',
                'price' => 36000,
                'image' => 'tumis_pucuk_labu.avif',
                'category' => 'vegetables',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karedok',
                'description' => 'Salad sayur khas Sunda yang dicampur dengan saus kacang segar',
                'price' => 25000,
                'image' => 'karedok.avif',
                'category' => 'vegetables',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //tempoe doeloe
            [
                'name' => 'Kambing Oven',
                'description' => 'Daging kambing panggang yang juicy disajikan dengan campuran saus kacang Pondok Tempo Doeloe, irisan bawang merah, dan kecap manis',
                'price' => 50000,
                'image' => 'kambing_oven.avif',
                'category' => 'tempoe-doeloe',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gulai Kambing',
                'description' => 'Semur daging kambing lembut yang dimasak dengan kuah kari kental & santan, diberi taburan bawang goreng dan kerupuk melinjo',
                'price' => 55000,
                'image' => 'gulai_kambing.avif',
                'category' => 'tempoe-doeloe',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sop Iga',
                'description' => 'Iga sapi dimasak dengan kuah bening yang terbuat dari rempah segar khas Indonesia. Disajikan dengan sambal dan jeruk nipis buatan sendiri untuk meningkatkan aroma dan rasa',
                'price' => 60000,
                'image' => 'sop_iga.avif',
                'category' => 'tempoe-doeloe',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            //mie ayam hw
            [
                'name' => 'Mie Ayam H&W',
                'description' => 'Mie dengan ayam kecap cincang & pangsit goreng, disajikan dengan kuah kaldu ayam harum',
                'price' => 25000,
                'image' => 'mie_ayam_hw.avif',
                'category' => 'mie-ayam h&w',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bakwan Campur',
                'description' => 'Aneka macam bakso Indonesia yang digoreng dan direbus dalam kuah kaldu',
                'price' => 25000,
                'image' => 'bakwan_campur.avif',
                'category' => 'mie-ayam h&w',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mie Goreng H&W',
                'description' => 'Mie telur goreng ala Cina',
                'price' => 25000,
                'image' => 'mie_goreng_hw.avif',
                'category' => 'mie-ayam h&w',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //drinks
            [
                'name' => 'Es Campur Tempo Doeloe',
                'description' => 'Kombinasi kelapa muda, jeli, cincau, alpukat & singkong yang difermentasi, diberi es serut dan santan',
                'price' => 36000,
                'image' => 'es_campur.avif',
                'category' => 'drinks',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Es Cianjur',
                'description' => 'Campuran ketan hitam yang difermentasi, kelapa muda, alpukat, dan jeli yang diberi es serut dan susu kental manis',
                'price' => 36000,
                'image' => 'es_cianjur.avif',
                'category' => 'drinks',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Es Shanghai',
                'description' => 'Campuran lengkeng, kelapa muda, nanas, alpukat, dan kismis, diberi es serut, sirup, dan kue astor renyah',
                'price' => 36000,
                'image' => 'es_shanghai.avif',
                'category' => 'drinks',
                'is_available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ], 
        ]);
    }
}
