<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        // 'table_id',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'notes',
        'promo_id',
        'total_DP',
        'status',
        'payment_deadline',
        'created_by',
        'fully_paid_at',
        'table_numbers', //ditambah untuk multiple table
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'payment_deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_DP' => 'decimal:2',
        'fully_paid_at' => 'datetime'
    ];

    protected $attributes = [
        'created_by' => 'customer',
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'formatted_time',
        'total_items',
        'customer_type',
        'customer_type_label',
        'formatted_dp',
        'can_edit',
        'can_cancel',
        'can_complete',
        'initial_dp',
        'discount_amount',
        'remaining_payment',
        'total_amount',
        'is_fully_paid',
        'order_subtotal', // ✅ TAMBAH INI
        'calculated_discount', // ✅ TAMBAH INI
        'total_capacity', //ditambah untuk multiple table
        'table_list', //ditambah untuk multiple table
    ];

    // Relasi ke user
    public function user()
    {  
        return $this->belongsTo(User::class);
    }

    // Relasi ke table
    // public function table()
    // {
    //     return $this->belongsTo(Table::class);
    // }

     // ✅ RELASI BARU: Many-to-Many dengan Table melalui ReservationTable
    public function tables(): BelongsToMany
    {
        return $this->belongsToMany(Table::class, 'reservation_tables')
            ->withTimestamps()
            ->withPivot(['id', 'created_at', 'updated_at']);
    }

     // ✅ RELASI: ReservationTable untuk akses langsung ke junction
    public function reservationTables(): HasMany
    {
        return $this->hasMany(ReservationTable::class);
    }

    // Relasi dengan promo
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    // Relasi dengan orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relasi dengan payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessor untuk total_items
    public function getTotalItemsAttribute()
    {
        $total = 0;
        foreach ($this->orders as $order) {
            $total += $order->orderItems->sum('qty');
        }
        return $total;
    }

    // Accessor untuk total_price (AMBIL DARI DATABASE - SUDAH DISKON)
    public function getTotalPriceAttribute()
    {
        return $this->orders->sum('total_price');
    }

     // ✅ ACCESSOR BARU: Total kapasitas semua meja
    // public function getTotalCapacityAttribute()
    // {
    //     return $this->tables->sum('capacity');
    // }

    // ✅ ACCESSOR BARU: List meja dalam format array
    public function getTablesListAttribute()
    {
        return $this->tables->map(function ($table) {
            return [
                'id' => $table->id,
                'number' => $table->number,
                'capacity' => $table->capacity,
                'status' => $table->status,
                'location' => $table->location
            ];
        });
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'waiting_payment' => 'Menunggu Pembayaran',
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
            'waiting_payment' => 'blue',
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'secondary',
            'expired' => 'danger',
        ];

        return $statusColors[$this->status] ?? 'secondary';
    }

    // Format reservation time
    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->reservation_time)->format('H:i');
    }

    // Format total_DP
    public function getFormattedDpAttribute()
    {
        return 'Rp ' . number_format($this->total_DP, 0, ',', '.');
    }

    // Customer type
    public function getCustomerTypeAttribute()
    {
        return $this->user_id ? 'member' : 'guest';
    }

    public function getCustomerTypeLabelAttribute()
    {
        return $this->user_id ? 'Member' : 'Guest';
    }

    // Cek apakah reservasi bisa di-edit
    public function getCanEditAttribute()
    {
        return in_array($this->status, ['waiting_payment', 'pending', 'confirmed']);
    }

    // Cek apakah reservasi bisa di-batalkan
    public function getCanCancelAttribute()
    {
        return in_array($this->status, ['waiting_payment', 'pending', 'confirmed']);
    }

    // Cek apakah reservasi bisa di-selesaikan
    public function getCanCompleteAttribute()
    {
        return $this->status === 'confirmed';
    }

    // ✅ METHOD BARU: Cek apakah kapasitas meja cukup
    public function hasSufficientCapacity()
    {
        return $this->total_capacity >= $this->guest_count;
    }

    // ✅ METHOD BARU: Attach tables ke reservasi
    public function attachTables(array $tableIds)
    {
        $this->tables()->attach($tableIds);
        $this->updateTableNumbers();
    }

     // ✅ METHOD BARU: Detach tables dari reservasi
    public function detachTables(array $tableIds = [])
    {
        if (empty($tableIds)) {
            $this->tables()->detach();
        } else {
            $this->tables()->detach($tableIds);
        }
        $this->updateTableNumbers();
    }

    // ✅ METHOD BARU: Sync tables (replace semua)
    public function syncTables(array $tableIds)
    {
        $this->tables()->sync($tableIds);
        $this->updateTableNumbers();
    }

    // ✅ METHOD BARU: Update table numbers string
    private function updateTableNumbers()
    {
        $this->table_numbers = $this->tables()
            ->orderBy('number')
            ->get()
            ->pluck('number')
            ->implode(', ');
        $this->save();
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

     // ✅ SCOPE BARU: Reservasi dengan meja tertentu
    public function scopeWithTable($query, $tableId)
    {
        return $query->whereHas('tables', function ($q) use ($tableId) {
            $q->where('tables.id', $tableId);
        });
    }

    /**
     * ACCESSOR BARU: Hitung subtotal order (sebelum diskon)
     */
    public function getOrderSubtotalAttribute()
    {
        if ($this->orders->count() > 0) {
            $subtotal = 0;
            foreach ($this->orders as $order) {
                foreach ($order->orderItems as $item) {
                    $subtotal += $item->price * $item->qty;
                }
            }
            return $subtotal;
        }
        return 0;
    }

    /**
     * ✅ ACCESSOR BARU: Hitung diskon yang sebenarnya
     */
    public function getCalculatedDiscountAttribute()
    {
        if (!$this->promo_id) {
            return 0;
        }

        $promo = $this->promo;
        if (!$promo) {
            return 0;
        }

        $subtotal = $this->order_subtotal;

        if ($promo->type === 'percent') {
            return $subtotal * ($promo->discount / 100);
        } else {
            return min($promo->discount, $subtotal);
        }
    }

    /**
     * ❌ DEPRECATED: Method lama - diganti dengan calculated_discount
     */
    public function getDiscountAmountAttribute()
    {
        return $this->calculated_discount;
    }

    /**
     * ❌ DEPRECATED: Method lama yang menyebabkan masalah
     */
    public function calculateInitialDP()
    {
        // JANGAN gunakan ini untuk perhitungan diskon
        // Hanya untuk keperluan display saja
        if ($this->orders->count() > 0) {
            return $this->order_subtotal * 0.3;
        } else {
            return 300000;
        }
    }

    /**
     * Hitung DP awal untuk tampilan
     */
    public function getInitialDPAttribute()
    {
        return $this->calculateInitialDP();
    }

    // Auto cancel jika expired
    public function checkAndCancelIfExpired()
    {
        if ($this->status === 'waiting_payment' && 
            $this->payment_deadline && 
            now()->greaterThan($this->payment_deadline)) {
            
            $this->update(['status' => 'cancelled']);
            Log::info("Reservation #{$this->id} auto-cancelled via helper method");
            return true;
        }
        return false;
    }

    /**
     * ✅ ACCESSOR BARU: Hitung sisa pembayaran (FIXED)
     */
    public function getRemainingPaymentAttribute()
    {
        // ✅ GUNAKAN TOTAL_PRICE YANG SUDAH DISKON DARI DATABASE
        $totalAfterDiscount = $this->total_price;
        
        return max(0, $totalAfterDiscount - $this->total_DP);
    }

    /**
     * ✅ ACCESSOR BARU: Hitung total amount (setelah promo) - FIXED
     */
    public function getTotalAmountAttribute()
    {
        // ✅ GUNAKAN TOTAL_PRICE YANG SUDAH DISKON DARI DATABASE
        return $this->total_price;
    }

    /**
     * ✅ ACCESSOR BARU: Cek apakah sudah lunas
     */
    public function getIsFullyPaidAttribute()
    {
        // return $this->remaining_payment <= 0 && $this->fully_paid_at !== null;
        // Jika sudah ada fully_paid_at, berarti sudah lunas
        return $this->fully_paid_at !== null;
    }

    /**
     * ✅ METHOD BARU: Untuk debug perhitungan
     */
    public function getCalculationDetails()
    {
        $order = $this->orders->first();
        
        return [
            'order_subtotal' => $this->order_subtotal,
            'calculated_discount' => $this->calculated_discount,
            'total_price_from_db' => $order ? $order->total_price : 0,
            'total_DP' => $this->total_DP,
            'remaining_payment' => $this->remaining_payment,
            'total_amount' => $this->total_amount,
            'promo_applied' => $this->promo ? [
                'name' => $this->promo->name,
                'type' => $this->promo->type,
                'discount' => $this->promo->discount
            ] : null
        ];
    }

    /**
     * ✅ METHOD BARU: Cek ketersediaan meja pada tanggal dan waktu tertentu
     */
    public static function getAvailableTables($date, $time)
    {
        $reservedTableIds = self::where('reservation_date', $date)
            ->where('reservation_time', $time)
            ->whereIn('status', ['confirmed', 'pending'])
            ->pluck('id')
            ->toArray();

        if (empty($reservedTableIds)) {
            return Table::available()->get();
        }

        return Table::available()
            ->whereDoesntHave('reservations', function ($query) use ($date, $time, $reservedTableIds) {
                $query->where('reservation_date', $date)
                      ->where('reservation_time', $time)
                      ->whereIn('reservations.id', $reservedTableIds);
            })
            ->get();
    }

    // File: app/Models/Reservation.php

    // Tambahkan accessor untuk table_numbers (jika belum)
    public function getTableNumbersAttribute()
    {
        if ($this->attributes['table_numbers'] ?? false) {
            return $this->attributes['table_numbers'];
        }
        
        if ($this->tables && $this->tables->count() > 0) {
            return $this->tables->pluck('number')->sort()->implode(', ');
        }
        
        return null;
    }

    // Accessor untuk total_capacity
    public function getTotalCapacityAttribute()
    {
        if ($this->attributes['total_capacity'] ?? false) {
            return $this->attributes['total_capacity'];
        }
        
        if ($this->tables) {
            return $this->tables->sum('capacity');
        }
        
        return 0;
    }

    // Accessor untuk total_tables
    public function getTotalTablesAttribute()
    {
        if ($this->attributes['total_tables'] ?? false) {
            return $this->attributes['total_tables'];
        }
        
        if ($this->tables) {
            return $this->tables->count();
        }
        
        return 0;
    }

    // Untuk mendapatkan meja pertama (compatibility dengan kode lama)
    public function getTableAttribute()
    {
        return $this->tables->first();
    }

    // Untuk mendapatkan nomor meja pertama
    public function getTableNumberAttribute()
    {
        return $this->tables->first() ? $this->tables->first()->number : null;
    }

    // Untuk mendapatkan location dari meja pertama
    public function getLocationAttribute()
    {
        return $this->tables->first() ? $this->tables->first()->location : null;
    }

    public function getTableListAttribute()
    {
        return $this->table_numbers;
    }

    
}