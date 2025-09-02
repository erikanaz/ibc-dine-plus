<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $menuItems = Menu::inRandomOrder()->take(3)->get();

        $order = Order::create([
            'user_id' => $user->id,
            'reservation_id' => null,
            'total_price' => 0, // akan dihitung ulang
            'status' => 'pending',
        ]);

        $total = 0;
        foreach ($menuItems as $menu) {
            $qty = rand(1, 3);
            $subtotal = $menu->price * $qty;

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'qty' => $qty,
                'price' => $menu->price,
                'notes' => fake()->optional()->sentence(),
            ]);

            $total += $subtotal;
        }

        $order->update(['total_price' => $total]);
    }
}
