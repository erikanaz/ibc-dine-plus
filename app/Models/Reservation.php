<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Reservation extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'notes',
        'promo_id',
        'total_DP',
        'status',
        // 'name',
        // 'email',
        // 'phone',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'total_DP' => 'decimal:2',
    ];


    //relasi ke user
    public function user()
    {  
        return $this->belongsTo(User::class);
    }

    //relasi ke table
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Relasi dengan promo
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    // Relasi dengan order
    public function order()
    {
        return $this->hasOne(Order::class);
    }

    // public function getTotalItemsAttribute()
    // {
    //     if ($this->order) {
    //         return $this->order->orderItems->sum('qty');
    //     }
    //     return 0;
    // }

    public function getTotalItemsAttribute()
    {
        if ($this->order && $this->order->orderItems) {
            return $this->order->orderItems->sum('qty');
        }
        return 0;
    }

    public function getTotalPriceAttribute()
    {
        return $this->order ? $this->order->total_price : 0;
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            'expired' => 'Kadaluarsa',
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'secondary',
            'expired' => 'gray',
        ];

        return $statusColors[$this->status] ?? 'secondary';
    }

    // Format reservation time
    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->reservation_time)->format('H:i');
    }

    // //relasi ke menu
    // public function menus()
    // {
    //     return $this->belongsToMany(Menu::class, 'menu_reservation')
    //                 ->withPivot('quantity')
    //                 ->withTimestamps();
    // }

    // // relasi ke order
    // public function order()
    // {
    //     return $this->hasOne(Order::class); 
    // }

    // Format total_DP
    public function getFormattedDpAttribute()
    {
        return 'Rp ' . number_format($this->total_DP, 0, ',', '.');
    }

    // Scope for today's reservations
    public function scopeToday($query)
    {
        return $query->whereDate('reservation_date', today());
    }

    // Scope for upcoming reservations
    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', today())
                    ->whereIn('status', ['pending', 'confirmed']);
    }

}
