<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'capacity',
        'status',
        'location'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_RESERVED = 'reserved';
    const STATUS_MAINTENANCE = 'maintenance';

    // Location constants
    const LOCATION_INDOOR = 'indoor';
    const LOCATION_OUTDOOR = 'outdoor';

    public function getStatusLabelAttribute()
    {
        return [
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_OCCUPIED => 'Terisi',
            self::STATUS_RESERVED => 'Reservasi',
            self::STATUS_MAINTENANCE => 'Perbaikan'
        ][$this->status] ?? 'Unknown';
    }

    public function getLocationLabelAttribute()
    {
        return [
            self::LOCATION_INDOOR => 'Indoor',
            self::LOCATION_OUTDOOR => 'Outdoor'
        ][$this->location] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_OCCUPIED => 'warning',
            self::STATUS_RESERVED => 'secondary',
            self::STATUS_MAINTENANCE => 'gray'
        ][$this->status] ?? 'gray';
    }

    /**
     * Get is_available attribute based on status
     */
    public function getIsAvailableAttribute()
    {
        return $this->status === 'available';
    }

    /**
     * Get formatted status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'available' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger';
    }

    /**
     * Get status text in Indonesian
     */
    public function getStatusTextAttribute()
    {
        return $this->status === 'available' ? 'Tersedia' : 'Tidak Tersedia';
    }

    /**
     * Get capacity label
     */
    public function getCapacityLabelAttribute()
    {
        return "{$this->capacity} Orang";
    }

    /**
     * Scope untuk meja yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    
}
