@extends('layouts.customer.app')

@section('title', 'Riwayat Reservasi - IBC Batu Tulis')

@section('content')
<div class="container mx-auto px-4 max-w-6xl mt-8 mb-12" x-data="reservationHistory()" x-cloak>
    <h1 class="text-3xl font-bold text-center mb-2 text-gray-800">Riwayat Reservasi</h1>
    <p class="text-center text-gray-600 mb-8">Lihat dan kelola semua reservasi Anda di IBC Batu Tulis</p>

    <!-- Filter Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap gap-2 justify-center">
            <button @click="filterStatus = ''" 
                    :class="filterStatus === '' ? 
                    'bg-yellow-500 text-white' : 
                    'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition">
                Semua
            </button>
            <button @click="filterStatus = 'waiting_payment'" 
                :class="filterStatus === 'waiting_payment' ? 
                'bg-orange-100 text-orange-800 border border-orange-300' : 
                'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                class="px-4 py-2 rounded-md text-sm font-medium transition">
                Menunggu Pembayaran
            </button>
            <button @click="filterStatus = 'pending'" 
                    :class="filterStatus === 'pending' ? 
                    'bg-yellow-100 text-yellow-800 border border-yellow-300' : 
                    'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition">
                Menunggu
            </button>
            <button @click="filterStatus = 'confirmed'" 
                    :class="filterStatus === 'confirmed' ? 
                    'bg-green-100 text-green-800 border border-green-300' : 
                    'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition">
                Dikonfirmasi
            </button>
            <button @click="filterStatus = 'completed'" 
                    :class="filterStatus === 'completed' ? 
                    'bg-blue-100 text-blue-800 border border-blue-300' : 
                    'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition">
                Selesai
            </button>
            <button @click="filterStatus = 'cancelled'" 
                    :class="filterStatus === 'cancelled' ? 
                    'bg-red-100 text-red-800 border border-red-300' : 
                    'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition">
                Dibatalkan
            </button>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="filteredReservations.length === 0" 
         class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2" x-text="reservations.length === 0 ? 'Belum ada reservasi' : 'Tidak ada reservasi dengan status ini'"></h3>
        <p class="text-gray-500 mb-4" x-text="reservations.length === 0 ? 'Anda belum memiliki riwayat reservasi.' : 'Tidak ditemukan reservasi dengan status yang dipilih.'"></p>
        <a href="{{ route('reservation.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat Reservasi Baru
        </a>
    </div>

    <!-- List Reservasi -->
    <div class="space-y-6" x-show="filteredReservations.length > 0">
        <template x-for="reservation in filteredReservations" :key="reservation.id">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-lg font-semibold text-gray-800" 
                                x-text="'Reservasi #' + reservation.id"></h3>
                            <span class="px-3 py-1 rounded-full text-sm font-medium" 
                                  :class="getStatusBadgeClass(reservation.status)">
                                <span x-text="getStatusText(reservation.status)"></span>
                            </span>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="text-sm text-gray-500" 
                                  x-text="formatDate(reservation.created_at)"></span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                        <!-- Tanggal & Waktu -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal & Waktu</p>
                            <p class="text-gray-800 font-semibold" 
                               x-text="formatReservationDate(reservation.reservation_date)"></p>
                            <p class="text-gray-600" x-text="reservation.reservation_time"></p>
                        </div>

                        <!-- Meja & Tamu -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Meja & Tamu</p>
                            <p class="text-gray-800 font-semibold" 
                               x-text="'Meja ' + reservation.table.number"></p>
                            <p class="text-gray-600" x-text="reservation.guest_count + ' Tamu'"></p>
                        </div>

                        <!-- Pembayaran -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pembayaran DP</p>
                            <p class="text-gray-800 font-semibold" 
                               x-text="'Rp ' + formatCurrency(reservation.total_DP)"></p>
                            <p class="text-sm" 
                               :class="getPaymentStatusClass(reservation.payments[0]?.status)">
                                <span x-text="getPaymentStatusText(reservation.payments[0]?.status)"></span>
                            </p>
                        </div>

                        <!-- Pre-order -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pre-order Menu</p>
                            <p class="text-gray-800 font-semibold" 
                               x-text="reservation.orders.length > 0 ? 'Ya' : 'Tidak'"></p>
                            <template x-if="reservation.orders.length > 0">
                                <p class="text-sm text-gray-600" 
                                   x-text="'Rp ' + formatCurrency(reservation.orders[0].total_price)"></p>
                            </template>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-3 justify-between items-center pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap gap-2">
                            <!-- Detail Button -->
                            <button @click="viewDetail(reservation)" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </button>

                            <!-- Batalkan Button (hanya untuk pending) -->
                            <template x-if="reservation.status === 'pending'">
                                <button @click="cancelReservation(reservation.id)" 
                                        class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batalkan
                                </button>
                            </template>

                            <!-- Buat Lagi Button (untuk completed/cancelled) -->
                            <template x-if="['completed', 'cancelled'].includes(reservation.status)">
                                <a :href="'{{ route('reservation.index') }}?date=' + reservation.reservation_date + '&time=' + reservation.reservation_time + '&guests=' + reservation.guest_count + '&table=' + reservation.table.id" 
                                   class="inline-flex items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Buat Lagi
                                </a>
                            </template>
                        </div>

                        <!-- Status Timeline -->
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <template x-if="reservation.status === 'pending'">
                                <span>Menunggu konfirmasi admin</span>
                            </template>
                            <template x-if="reservation.status === 'confirmed'">
                                <span>Reservasi dikonfirmasi</span>
                            </template>
                            <template x-if="reservation.status === 'completed'">
                                <span>Reservasi selesai</span>
                            </template>
                            <template x-if="reservation.status === 'cancelled'">
                                <span>Reservasi dibatalkan</span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Pagination -->
    <div class="mt-8" x-show="filteredReservations.length > 0">
        <div class="flex justify-between items-center">
            <p class="text-sm text-gray-700">
                Menampilkan <span x-text="filteredReservations.length"></span> dari 
                <span x-text="reservations.length"></span> reservasi
            </p>
            
            <div class="flex space-x-2">
                <button @click="currentPage--" 
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 
                        'bg-gray-100 text-gray-400 cursor-not-allowed' : 
                        'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium transition">
                    Sebelumnya
                </button>
                <button @click="currentPage++" 
                        :disabled="currentPage * itemsPerPage >= reservations.length"
                        :class="currentPage * itemsPerPage >= reservations.length ? 
                        'bg-gray-100 text-gray-400 cursor-not-allowed' : 
                        'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium transition">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<!-- Modal Detail -->
<div x-show="showDetailModal" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 overflow-y-auto z-50" 
     x-cloak>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="closeModal()"></div>

        <!-- Modal Panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            <!-- Modal Header -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Detail Reservasi #<span x-text="selectedReservation ? selectedReservation.id : ''"></span>
                        </h3>
                        
                        <template x-if="selectedReservation">
                            <div class="space-y-4">
                                <!-- Informasi Reservasi -->
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Tanggal</p>
                                        <p class="font-medium text-gray-800" 
                                           x-text="formatReservationDate(selectedReservation.reservation_date)"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Waktu</p>
                                        <p class="font-medium text-gray-800" 
                                           x-text="selectedReservation.reservation_time"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Meja</p>
                                        <p class="font-medium text-gray-800" 
                                           x-text="'Meja ' + selectedReservation.table.number"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Jumlah Tamu</p>
                                        <p class="font-medium text-gray-800" 
                                           x-text="selectedReservation.guest_count + ' Orang'"></p>
                                    </div>
                                </div>

                                <!-- Informasi Customer -->
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Informasi Customer:</p>
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Nama</p>
                                            <p class="font-medium text-gray-800" 
                                               x-text="selectedReservation.customer_name"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Email</p>
                                            <p class="font-medium text-gray-800" 
                                               x-text="selectedReservation.customer_email"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Telepon</p>
                                            <p class="font-medium text-gray-800" 
                                               x-text="selectedReservation.customer_phone"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Status</p>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                                  :class="getStatusBadgeClass(selectedReservation.status)"
                                                  x-text="getStatusText(selectedReservation.status)"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Catatan -->
                                <template x-if="selectedReservation.notes">
                                    <div class="border-t pt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Catatan:</p>
                                        <p class="text-sm text-gray-800" x-text="selectedReservation.notes"></p>
                                    </div>
                                </template>

                                <!-- Pre-order Menu -->
                                <template x-if="selectedReservation.orders.length > 0">
                                    <div class="border-t pt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Pesanan Menu:</p>
                                        <div class="space-y-2">
                                            <template x-for="item in selectedReservation.orders[0].order_items" :key="item.id">
                                                <div class="flex justify-between items-center py-1">
                                                    <div>
                                                        <span x-text="item.menu.name" class="text-sm text-gray-800"></span>
                                                        <span x-text="' × ' + item.qty" class="text-xs text-gray-500 ml-1"></span>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-sm text-gray-800" 
                                                              x-text="'Rp ' + formatCurrency(item.price)"></span>
                                                        <br>
                                                        <span class="text-xs text-gray-500" 
                                                              x-text="'Total: Rp ' + formatCurrency(item.qty * item.price)"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <div class="border-t pt-2 mt-2">
                                                <div class="flex justify-between font-medium">
                                                    <span>Total Pesanan:</span>
                                                    <span x-text="'Rp ' + formatCurrency(selectedReservation.orders[0].total_price)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Pembayaran -->
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Pembayaran DP:</p>
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Total DP</p>
                                            <p class="font-medium text-gray-800" 
                                               x-text="'Rp ' + formatCurrency(selectedReservation.total_DP)"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Status Pembayaran</p>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                                  :class="getPaymentStatusClass(selectedReservation.payments[0]?.status)"
                                                  x-text="getPaymentStatusText(selectedReservation.payments[0]?.status)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        @click="closeModal()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function reservationHistory() {
    return {
        reservations: @json($reservations),
        filterStatus: '',
        currentPage: 1,
        itemsPerPage: 10,
        showDetailModal: false,
        selectedReservation: null,

        

        init() {
            console.log('Reservations loaded:', this.reservations.length);
        },

        get filteredReservations() {
            let filtered = this.reservations;
            
            // Filter by status
            if (this.filterStatus) {
                filtered = filtered.filter(r => r.status === this.filterStatus);
            }
            
            // Pagination
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return filtered.slice(start, end);
        },

        getStatusBadgeClass(status) {
            const classes = {
                'waiting_payment': 'bg-orange-100 text-orange-800',
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-green-100 text-green-800',
                'completed': 'bg-blue-100 text-blue-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },

        getStatusText(status) {
            const texts = {
                'waiting_payment': 'Menunggu Pembayaran',
                'pending': 'Menunggu Konfirmasi',
                'confirmed': 'Dikonfirmasi',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            return texts[status] || status;
        },

        getPaymentStatusClass(status) {
            const classes = {
                'pending': 'text-yellow-600',
                'verified': 'text-blue-600',
                'paid': 'text-green-600',
                'failed': 'text-red-600'
            };
            return classes[status] || 'text-gray-600';
        },

        getPaymentStatusText(status) {
            const texts = {
                'pending': 'Menunggu Verifikasi',
                'verified': 'Terverifikasi',
                'paid': 'Lunas',
                'failed': 'Gagal'
            };
            return texts[status] || 'Belum Bayar';
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        },

        formatReservationDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        },

        formatCurrency(amount) {
            if (!amount) return '0';
            return new Intl.NumberFormat('id-ID').format(amount);
        },

        viewDetail(reservation) {
            console.log('View detail clicked:', reservation);
            this.selectedReservation = reservation;
            this.showDetailModal = true;
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.showDetailModal = false;
            this.selectedReservation = null;
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        },

        async cancelReservation(reservationId) {
            if (!confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')) {
                return;
            }

            try {
                const response = await fetch(`/reservation/${reservationId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update status di local state
                    const index = this.reservations.findIndex(r => r.id === reservationId);
                    if (index !== -1) {
                        this.reservations[index].status = 'cancelled';
                    }
                    alert('Reservasi berhasil dibatalkan');
                } else {
                    alert('Gagal membatalkan reservasi: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membatalkan reservasi');
            }
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }

/* Modal styles */
.fixed.inset-0 {
    z-index: 50;
}

.fixed.inset-0 .bg-gray-500 {
    z-index: 40;
}

.inline-block.align-bottom {
    z-index: 50;
}
</style>
@endpush