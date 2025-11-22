<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'promo_code',
        'description',
        'discount',
        'type',
        'start_date',
        'end_date',
        'usage_limit',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        // 'discount' => 'decimal:2',
    ];

    // ✅ METHOD UNTUK CEK STATUS PROMO
    public function getIsActiveAttribute()
    {
        $now = Carbon::now();
        
        // Cek tanggal mulai
        if ($this->start_date && $now->lessThan($this->start_date)) {
            return false;
        }
        
        // Cek tanggal berakhir
        if ($this->end_date && $now->greaterThan($this->end_date)) {
            return false;
        }
        
        return true;
    }

    // Accessor untuk status promo
    public function getStatusAttribute()
    {
        $now = Carbon::now();
        
        if ($this->start_date && $this->end_date) {
            if ($now->lessThan($this->start_date)) {
                return 'upcoming';
            }
            
            if ($now->greaterThan($this->end_date)) {
                return 'expired';
            }
            
            return 'active';
        }
        
        // Jika tidak ada tanggal, consider sebagai aktif
        return 'active';
    }

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'active' => 'Aktif',
            'expired' => 'Kadaluarsa',
            'upcoming' => 'Akan Datang',
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $statusColors = [
            'active' => 'success',
            'expired' => 'warning',
            'upcoming' => 'info',
        ];

        return $statusColors[$this->status] ?? 'secondary';
    }

    // Scope untuk promo aktif
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->where('start_date', '<=', now())
              ->orWhereNull('start_date');
        })->where(function($q) {
            $q->where('end_date', '>=', now())
              ->orWhereNull('end_date');
        });
    }


    // Relasi dengan reservations (jika ada)
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Hitung berapa kali promo sudah digunakan
    public function getUsedCountAttribute()
    {
        return $this->reservations()->count();
    }

    // Cek apakah promo masih bisa digunakan
    public function getCanBeUsedAttribute()
    {
        if ($this->status !== 'active') {
            return false;
        }
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }
        
        return true;
    }

    
}