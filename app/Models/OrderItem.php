<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', // ID pesanan
        'menu_id', // ID menu yang dipesan
        'name', // Nama item yang dipesan
        'qty', // Jumlah item yang dipesan
        'price', // Harga per item pada saat pemesanan
        'notes', // Catatan khusus untuk item, misalnya permintaan khusus dari pelanggan
    ];

    // relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);      
    }

    // relasi ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
