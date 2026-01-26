<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationTable extends Model
{
    /**
     * Nama tabel
     */
    protected $table = 'reservation_tables';

    /**
     * Kolom yang bisa diisi
     */
    protected $fillable = [
        'reservation_id',
        'table_id'
    ];

    /**
     * Timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke Reservation
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Relasi ke Table
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Scope untuk reservasi tertentu
     */
    public function scopeForReservation($query, $reservationId)
    {
        return $query->where('reservation_id', $reservationId);
    }

    /**
     * Scope untuk meja tertentu
     */
    public function scopeForTable($query, $tableId)
    {
        return $query->where('table_id', $tableId);
    }
}