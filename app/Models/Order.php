<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'reservation_id', // ID reservasi jika ada
        'total_price', // Total harga dari semua item dalam pesanan
        'notes', // Catatan tambahan untuk pesanan
        // 'order_type', // Jenis pesanan (dine-in atau takeaway)
        // 'payment_method', // Metode pembayaran (cash, qris, transfer)
        // 'status', // Status pesanan
    ];

     protected $casts = [
        'total_price' => 'decimal:2',
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

    // Relasi dengan order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Hitung total items
    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('qty');
    }

    // Status order berdasarkan reservation
    public function getStatusAttribute()
    {
        if ($this->reservation) {
            return $this->reservation->status;
        }
        return 'unknown';
    }

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            'expired' => 'Kadaluarsa',
            'unknown' => 'Tidak Diketahui',
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending' => 'warning',
            'confirmed' => 'primary',
            'cancelled' => 'danger',
            'completed' => 'success',
            'expired' => 'secondary',
            'unknown' => 'gray',
        ];

        return $statusColors[$this->status] ?? 'secondary';
    }

    // Format nomor order
    public function getOrderNumberAttribute()
    {
        return 'ORD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

}
