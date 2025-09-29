<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //admin
        $admin = User::firstOrCreate(
            ['email' => 'admin123@gmail.com'], 
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123')
            ]
        );
        $admin->assignRole('admin');

        // //cashier
        // $cashier = User::firstOrCreate(
        //     ['email' => 'cashier123@gmail.com'],
        //     [
        //         'name' => 'Cashier',
        //         'password' => Hash::make('cashier123')
        //     ]
        // );
        // $cashier->assignRole('cashier');

        //customer
        $customer = User::firstOrCreate(
            ['email' => 'member1@gmail.com'],
            [
                'name' => 'Member1',
                'password' => Hash::make('member1'),
                'phone' => '081234567890',
                // 'address' => 'Jl. Batu Tulis No. 1, Jakarta'
            ]
        );
        $customer->assignRole('member');
    }
}
