<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\Promo;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display customer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // ========== STATISTIK CARDS ==========
        
        // Total semua reservasi user
        $totalReservations = Reservation::where('user_id', $user->id)->count();
        
        // Reservasi Aktif (waiting_payment, pending, confirmed yang belum lewat)
        $activeReservations = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['waiting_payment', 'pending', 'confirmed'])
            ->where(function($query) {
                $query->where('reservation_date', '>', now()->toDateString())
                    ->orWhere(function($q) {
                        $q->where('reservation_date', '=', now()->toDateString())
                          ->where('reservation_time', '>=', now()->toTimeString());
                    });
            })
            ->count();
        
        // RESERVASI BULAN INI - GANTI POIN MEMBER
        $monthlyReservations = Reservation::where('user_id', $user->id)
            ->whereYear('reservation_date', now()->year)
            ->whereMonth('reservation_date', now()->month)
            ->count();
        
        // Reservasi Completed
        $completedReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        // Total belanja dari orders yang completed
        $totalSpent = Order::whereHas('reservation', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', 'completed');
            })
            ->sum('total_price');
        
        // ========== RESERVASI MENDATANG ==========
        
        $upcomingReservations = Reservation::with([
                'table', 
                'orders.orderItems.menu'
            ])
            ->where('user_id', $user->id)
            ->whereIn('status', ['waiting_payment', 'pending', 'confirmed'])
            ->where(function($query) {
                $query->where('reservation_date', '>', now()->toDateString())
                    ->orWhere(function($q) {
                        $q->where('reservation_date', '=', now()->toDateString())
                          ->where('reservation_time', '>=', now()->toTimeString());
                    });
            })
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->take(5)
            ->get()
            ->map(function($reservation) {
                // Format waktu
                $reservation->formatted_time = Carbon::parse($reservation->reservation_time)->format('H:i');
                
                // Status badge HTML
                $reservation->status_badge = $this->getStatusBadge($reservation->status);
                
                // Total harga dari orders (jika ada)
                $reservation->total_order_price = $reservation->orders->sum('total_price');
                
                return $reservation;
            });
        
        // ========== RIWAYAT RESERVASI ==========
        
        $recentReservations = Reservation::with(['table', 'orders'])
            ->where('user_id', $user->id)
            ->where(function($query) {
                $query->where('reservation_date', '<', now()->toDateString())
                    ->orWhere(function($q) {
                        $q->where('reservation_date', '=', now()->toDateString())
                          ->where('reservation_time', '<', now()->toTimeString());
                    });
            })
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->take(5)
            ->get()
            ->map(function($reservation) {
                // Format waktu
                $reservation->formatted_time = Carbon::parse($reservation->reservation_time)->format('H:i');
                
                // Status badge HTML
                $reservation->status_badge = $this->getStatusBadge($reservation->status);
                
                return $reservation;
            });
        
        // ========== PROMO AKTIF ==========
        
        $activePromos = Promo::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($promo) {
                // Format discount display
                if ($promo->discount <= 100) {
                    $promo->discount_display = $promo->discount . '%';
                    $promo->discount_badge = '-' . $promo->discount . '%';
                } else {
                    $promo->discount_display = 'Rp ' . number_format($promo->discount, 0, ',', '.');
                    $promo->discount_badge = '-Rp' . number_format($promo->discount / 1000, 0) . 'K';
                }
                
                return $promo;
            });

        // ================================================
        // TAMBAHKAN INI: Get available facilities
        // ================================================
        $facilities = Facility::where('is_available', true)
            ->orderBy('icon') // Urutkan berdasarkan jenis
            ->get();

        return view('customer.member-dashboard', compact(
            'totalReservations',
            'activeReservations',
            'monthlyReservations', // GANTI: monthlyReservations bukan memberPoints
            'completedReservations',
            'totalSpent',
            'upcomingReservations',
            'recentReservations',
            'activePromos',
            'facilities'
        ));
    }
    
    /**
     * Generate status badge HTML
     *
     * @param string $status
     * @return string
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'waiting_payment' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                    </svg>
                                    Menunggu Pembayaran
                                </span>',
            
            'pending' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Menunggu Konfirmasi
                        </span>',
            
            'confirmed' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Dikonfirmasi
                            </span>',
            
            'completed' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Selesai
                            </span>',
            
            'cancelled' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Dibatalkan
                            </span>',
            
            'rejected' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                            </svg>
                            Ditolak
                        </span>',
        ];

        return $badges[$status] ?? '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">' . ucfirst($status) . '</span>';
    }
    
    /**
     * Get quick stats for API/AJAX requests
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuickStats()
    {
        $user = Auth::user();

        $stats = [
            'total_reservations' => Reservation::where('user_id', $user->id)->count(),
            'active_reservations' => Reservation::where('user_id', $user->id)
                ->whereIn('status', ['waiting_payment', 'pending', 'confirmed'])
                ->where(function($query) {
                    $query->where('reservation_date', '>', now()->toDateString())
                        ->orWhere(function($q) {
                            $q->where('reservation_date', '=', now()->toDateString())
                              ->where('reservation_time', '>=', now()->toTimeString());
                        });
                })
                ->count(),
            'monthly_reservations' => Reservation::where('user_id', $user->id)
                ->whereYear('reservation_date', now()->year)
                ->whereMonth('reservation_date', now()->month)
                ->count(), // GANTI: monthly_reservations bukan member_points
            'completed_reservations' => Reservation::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Order::whereHas('reservation', function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->where('status', 'completed');
                })
                ->sum('total_price'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}