<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    protected $appends = [
        'status_label',
        'location_label',
        'status_color',
        'is_available',
        'status_badge_class',
        'status_text',
        'capacity_label',
        'reservation_count', // ✅ DITAMBAH
        'current_reservations' // ✅ DITAMBAH
    ];

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
     * ✅ ACCESSOR BARU: Jumlah reservasi untuk meja ini
     */
    public function getReservationCountAttribute()
    {
        return $this->reservations()->count();
    }

    /**
     * ✅ ACCESSOR BARU: Reservasi aktif untuk meja ini
     */
    public function getCurrentReservationsAttribute()
    {
        return $this->reservations()
            ->where('reservation_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->get();
    }

    /**
     * Scope untuk meja yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // public function reservations()
    // {
    //     return $this->hasMany(Reservation::class);
    // }

    // ✅ RELASI BARU: Many-to-Many dengan Reservation melalui ReservationTable
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_tables')
            ->withTimestamps()
            ->withPivot(['id', 'created_at', 'updated_at']);
    }

    /**
     * ✅ METHOD BARU: Cek apakah meja tersedia pada tanggal dan waktu tertentu
     */
    public function isAvailableForDateTime($date, $time)
    {
        // Cek status meja
        if ($this->status !== self::STATUS_AVAILABLE) {
            return false;
        }

        // Cek apakah ada reservasi pada waktu tersebut
        $conflictingReservations = $this->reservations()
            ->where('reservation_date', $date)
            ->where('reservation_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        return $conflictingReservations === 0;
    }

    /**
     * ✅ METHOD BARU: Dapatkan reservasi pada tanggal tertentu
     */
    public function getReservationsForDate($date)
    {
        return $this->reservations()
            ->where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_time')
            ->get();
    }

    /**
     * ✅ METHOD BARU: Update status meja berdasarkan reservasi
     */
    public function updateStatusBasedOnReservations()
    {
        $today = today()->toDateString();
        
        // Cek apakah ada reservasi aktif hari ini
        $activeReservationToday = $this->reservations()
            ->where('reservation_date', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($activeReservationToday) {
            $this->update(['status' => self::STATUS_RESERVED]);
        } elseif ($this->status === self::STATUS_RESERVED) {
            $this->update(['status' => self::STATUS_AVAILABLE]);
        }
    }
    
}
