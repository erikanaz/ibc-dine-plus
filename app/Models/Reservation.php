<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'payment_deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_DP' => 'decimal:2',
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
        'discount_amount'
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

    // Accessor untuk total_price
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
     * Hitung jumlah diskon yang diberikan
     */
    public function getDiscountAmountAttribute()
    {
        if (!$this->promo_id) {
            return 0;
        }

        $promo = $this->promo;
        if (!$promo) {
            return 0;
        }

        // Hitung DP awal (sebelum diskon)
        $dpAwal = $this->calculateInitialDP();

        // Hitung diskon berdasarkan type promo
        if ($promo->type === 'percent') {
            return $dpAwal * ($promo->discount / 100);
        } else {
            return min($promo->discount, $dpAwal);
        }
    }

    /**
     * Hitung DP awal sebelum diskon
     */
    public function calculateInitialDP()
    {
        // Jika ada pre-order, hitung dari total pesanan
        if ($this->orders->count() > 0) {
            $order = $this->orders->first();
            
            // Hitung total pesanan sebelum diskon dari order items
            $totalBeforeDiscount = 0;
            foreach ($order->orderItems as $item) {
                $totalBeforeDiscount += $item->qty * $item->price;
            }
            
            return $totalBeforeDiscount * 0.3; // 30% dari total pesanan
        } else {
            // Untuk reservasi tanpa pre-order
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
}