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
        'name',
        'email',
        'phone',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'table_id',
        'with_preorder',
        'payment_method',
        'notes',
        'status',
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

    //relasi ke menu
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_reservation')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // relasi ke order
    public function order()
    {
        return $this->hasOne(Order::class); 
    }

}
