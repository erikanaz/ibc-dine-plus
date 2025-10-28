<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'amount',
        'status',
        // 'payment_type',
        'payment_proof',
        'bank_name',
        'account_number',
        'account_name',
        'notes',
        'paid_at',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime'
    ];

    // Relasi ke Reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'partial' => 'Sebagian',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending' => 'warning',
            'paid' => 'success',
            'partial' => 'info',
            'failed' => 'danger',
            'refunded' => 'secondary',
        ];

        return $statusColors[$this->status] ?? 'secondary';
    }

    // Format amount
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}