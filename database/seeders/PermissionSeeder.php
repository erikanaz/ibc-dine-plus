<?php

namespace Database\Seeders;

// use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = [
            //admin
            'manage users',
            'manage roles',
            'manage tables',
            'manage menus',
            'manage orders',
            'view orders',
            'manage reservations',
            'view reservations',
            'view reports',

            //cashier
            'process payments',
            'print receipts',
            'view transactions',

            //customer
            'create reservations',
            'cancel reservations',
            'view own reservations',
            'order menus',
            'view own orders',
            'make payments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ambil roles
        $admin = Role::where('name', 'admin')->first();
        $cashier = Role::where('name', 'cashier')->first();
        $customer = Role::where('name', 'customer')->first();  
        
        // assign permissions to roles
        $admin->syncPermissions([
            'manage users',
            'manage roles',
            'manage tables',
            'manage menus',
            'manage orders',
            'view orders',
            'manage reservations',
            'view reservations',
            'view reports',
        ]);

        $cashier->syncPermissions([
            'view reservations',
            'view orders',
            'process payments',
            'print receipts',
            'view transactions',
        ]);

        $customer->syncPermissions([
            'create reservations',
            'cancel reservations',
            'view own reservations',
            'order menus',
            'view own orders',
            'make payments',
        ]);
    }
}
