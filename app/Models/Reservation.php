<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'table_id',
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
        'calculated_discount' // ✅ TAMBAH INI
    ];

    // Relasi ke user
    public function user()
    {  
        return $this->belongsTo(User::class);
    }

    // Relasi ke table
    public function table()
    {
        return $this->belongsTo(Table::class);
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

    /**
     * ✅ ACCESSOR BARU: Hitung subtotal order (sebelum diskon)
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
        return $this->remaining_payment <= 0 && $this->fully_paid_at !== null;
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
}