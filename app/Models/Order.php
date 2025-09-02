<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'reservation_id', // ID reservasi jika ada
        'total_price', // Total harga dari semua item dalam pesanan
        'note', // Catatan tambahan untuk pesanan
        'order_type', // Jenis pesanan (dine-in atau takeaway)
        'payment_method', // Metode pembayaran (cash, qris, transfer)
        'status', // Status pesanan
    ];

    // relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke Reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);   
    }

    // relasi ke OrderItem
    public function items() 
    {
        return $this->hasMany(OrderItem::class);
    }
}
