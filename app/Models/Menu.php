<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // Jika nama tabel tidak sesuai konvensi, misalnya bukan "menus"
    // protected $table = 'nama_tabel';

    // Field yang boleh diisi secara massal
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category',
        'is_available',
    ];

    // Cast boolean agar otomatis jadi true/false
    protected $casts = [
        'is_available' => 'boolean',
    ];

    // Relasi ke Reservation melalui tabel pivot
    public function reservations()
    {   
        return $this->belongsToMany(Reservation::class, 'menu_reservation')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Relasi ke OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);    
    }
}
