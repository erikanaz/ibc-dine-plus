@extends('layouts.customer.app')

@section('title', 'Riwayat Reservasi - IBC Batu Tulis')

@section('content')
<div class="container mx-auto px-4 max-w-6xl mt-8 mb-12" x-data="reservationHistory()" x-init="init()" x-cloak>
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
                Menunggu Verifikasi
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
                    <!-- COUNTDOWN UNTUK MENUNGGU PEMBAYARAN -->
                    <template x-if="reservation.status === 'waiting_payment'">
                        <div class="mb-4">
                            <template x-if="reservation.payment_deadline">
                                <!-- Countdown dengan deadline -->
                                <div class="bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-lg border border-orange-200 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                        <div class="flex items-center">
                                            <div class="bg-orange-100 p-2 rounded-full mr-3">
                                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-orange-800">Batas Waktu Pembayaran</p>
                                                <p class="text-xs text-orange-600" x-text="'Sampai: ' + formatDateTime(reservation.payment_deadline)"></p>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center md:text-right">
                                            <p class="text-xs text-orange-700 font-medium mb-1">SISA WAKTU:</p>
                                            <div class="flex items-center justify-center md:justify-end space-x-1">
                                                <template x-if="getTimeLeft(reservation.payment_deadline).hours > 0">
                                                    <div class="bg-white border border-orange-300 rounded px-2 py-1 min-w-[40px] text-center">
                                                        <span class="text-lg font-bold" 
                                                              :class="getCountdownTextClass(reservation.payment_deadline)"
                                                              x-text="getTimeLeft(reservation.payment_deadline).hours.toString().padStart(2, '0')"></span>
                                                        <span class="text-xs text-orange-600 block">Jam</span>
                                                    </div>
                                                </template>
                                                <div class="bg-white border border-orange-300 rounded px-2 py-1 min-w-[40px] text-center">
                                                    <span class="text-lg font-bold" 
                                                          :class="getCountdownTextClass(reservation.payment_deadline)"
                                                          x-text="getTimeLeft(reservation.payment_deadline).minutes.toString().padStart(2, '0')"></span>
                                                    <span class="text-xs text-orange-600 block">Menit</span>
                                                </div>
                                                <div class="bg-white border border-orange-300 rounded px-2 py-1 min-w-[40px] text-center">
                                                    <span class="text-lg font-bold" 
                                                          :class="getCountdownTextClass(reservation.payment_deadline)"
                                                          x-text="getTimeLeft(reservation.payment_deadline).seconds.toString().padStart(2, '0')"></span>
                                                    <span class="text-xs text-orange-600 block">Detik</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <a :href="'/reservation/payment/' + reservation.id" 
                                               class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                Bayar Sekarang
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <div class="w-full bg-orange-200 rounded-full h-2">
                                            <div class="bg-orange-500 h-2 rounded-full transition-all duration-1000" 
                                                 :style="'width: ' + getProgressPercentage(reservation.payment_deadline) + '%'"
                                                 :class="getProgressBarClass(reservation.payment_deadline)"></div>
                                        </div>
                                        <p class="text-xs text-orange-600 text-center mt-1" 
                                           x-text="getProgressText(reservation.payment_deadline)"></p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!reservation.payment_deadline">
                                <!-- Tanpa deadline -->
                                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-orange-800">Menunggu Pembayaran DP</p>
                                                <p class="text-xs text-orange-600">Silakan lakukan pembayaran segera</p>
                                            </div>
                                        </div>
                                        <a :href="'/reservation/payment/' + reservation.id" 
                                           class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition">
                                            Bayar Sekarang
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

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
                               x-text="reservation.with_preorder ? 'Ya' : 'Tidak'"></p>
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

                            <!-- Batalkan Button (untuk waiting_payment dan pending) -->
                            <template x-if="['waiting_payment', 'pending'].includes(reservation.status)">
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
                            <template x-if="reservation.status === 'waiting_payment'">
                                <span class="text-orange-600 font-medium">⏰ Menunggu pembayaran DP</span>
                            </template>
                            <template x-if="reservation.status === 'pending'">
                                <span class="text-yellow-600 font-medium">Menunggu verifikasi admin</span>
                            </template>
                            <template x-if="reservation.status === 'confirmed'">
                                <span class="text-green-600 font-medium">Reservasi dikonfirmasi</span>
                            </template>
                            <template x-if="reservation.status === 'completed'">
                                <span>Reservasi selesai</span>
                            </template>
                            <template x-if="reservation.status === 'cancelled'">
                                <span class="text-red-600 font-medium">Reservasi dibatalkan</span>
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

    <!-- MODAL DETAIL -->
    <div x-show="showDetailModal" 
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             @click.outside="closeModal()">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Detail Reservasi #<span x-text="selectedReservation ? selectedReservation.id : ''"></span></h3>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <template x-if="selectedReservation">
                    <div class="space-y-4">
                        <!-- Countdown di Modal -->
                        <template x-if="selectedReservation.status === 'waiting_payment' && selectedReservation.payment_deadline">
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-lg border border-orange-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="bg-orange-100 p-2 rounded-full mr-3">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-orange-800">Batas Waktu Pembayaran</p>
                                            <p class="text-xs text-orange-600" x-text="'Sampai: ' + formatDateTime(selectedReservation.payment_deadline)"></p>
                                        </div>
                                    </div>
                                    <a :href="'/reservation/payment/' + selectedReservation.id" 
                                       class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition">
                                        Bayar Sekarang
                                    </a>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-xs text-orange-700 font-medium mb-2">SISA WAKTU PEMBAYARAN:</p>
                                    <div class="flex justify-center space-x-2">
                                        <template x-if="getTimeLeft(selectedReservation.payment_deadline).hours > 0">
                                            <div class="bg-white border border-orange-300 rounded px-3 py-2 min-w-[50px] text-center">
                                                <span class="text-xl font-bold" 
                                                      :class="getCountdownTextClass(selectedReservation.payment_deadline)"
                                                      x-text="getTimeLeft(selectedReservation.payment_deadline).hours.toString().padStart(2, '0')"></span>
                                                <span class="text-xs text-orange-600 block">Jam</span>
                                            </div>
                                        </template>
                                        <div class="bg-white border border-orange-300 rounded px-3 py-2 min-w-[50px] text-center">
                                            <span class="text-xl font-bold" 
                                                  :class="getCountdownTextClass(selectedReservation.payment_deadline)"
                                                  x-text="getTimeLeft(selectedReservation.payment_deadline).minutes.toString().padStart(2, '0')"></span>
                                            <span class="text-xs text-orange-600 block">Menit</span>
                                        </div>
                                        <div class="bg-white border border-orange-300 rounded px-3 py-2 min-w-[50px] text-center">
                                            <span class="text-xl font-bold" 
                                                  :class="getCountdownTextClass(selectedReservation.payment_deadline)"
                                                  x-text="getTimeLeft(selectedReservation.payment_deadline).seconds.toString().padStart(2, '0')"></span>
                                            <span class="text-xs text-orange-600 block">Detik</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

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
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function reservationHistory() {
    return {
        reservations: @json($reservations),
        filterStatus: '',
        currentPage: 1,
        itemsPerPage: 10,
        showDetailModal: false,
        selectedReservation: null,
        countdownInterval: null,
        countdownTick: 0,

        init() {
            console.log('🟢 Reservations loaded:', this.reservations.length);
            
            // Debug: Check waiting payment reservations
            const waitingPayments = this.reservations.filter(r => r.status === 'waiting_payment');
            console.log('🟡 Waiting payment reservations:', waitingPayments);
            
            // Start countdown interval
            this.startCountdown();
        },

        startCountdown() {
            // Clear existing interval
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }
            
            // Update countdown every second
            this.countdownInterval = setInterval(() => {
                // Increment counter untuk memaksa Alpine.js re-render
                this.countdownTick++;
                
                // Cek jika ada reservasi yang sudah expired, update status
                this.checkExpiredReservations();
            }, 1000);
        },

        checkExpiredReservations() {
            const now = new Date().getTime();
            this.reservations.forEach(reservation => {
                if (reservation.status === 'waiting_payment' && reservation.payment_deadline) {
                    const deadline = new Date(reservation.payment_deadline).getTime();
                    if (now > deadline) {
                        // Update status lokal ke cancelled
                        reservation.status = 'cancelled';
                        console.log(`🟡 Reservasi ${reservation.id} expired, status diubah ke cancelled`);
                    }
                }
            });
        },

        get filteredReservations() {
            let filtered = this.reservations;
            
            // Filter by status
            if (this.filterStatus) {
                filtered = filtered.filter(r => r.status === this.filterStatus);
            }
            
            // Sort by created_at descending
            filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            
            // Pagination
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return filtered.slice(start, end);
        },

        // FUNGSI UTAMA COUNTDOWN - Menghitung waktu tersisa
        getTimeLeft(deadlineString) {
            // Gunakan countdownTick untuk memaksa re-evaluation
            const tick = this.countdownTick;
            
            if (!deadlineString) {
                return { hours: 0, minutes: 0, seconds: 0, expired: true };
            }
            
            const deadline = new Date(deadlineString).getTime();
            const now = new Date().getTime();
            const distance = deadline - now;
            
            if (distance < 0) {
                return { hours: 0, minutes: 0, seconds: 0, expired: true };
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            return { hours, minutes, seconds, expired: false };
        },

        // Progress bar percentage (24 hours total)
        getProgressPercentage(deadlineString) {
            if (!deadlineString) return 0;
            
            const deadline = new Date(deadlineString).getTime();
            const created = new Date(deadline - (24 * 60 * 60 * 1000)).getTime(); // 24 hours before deadline
            const now = new Date().getTime();
            const totalTime = deadline - created;
            const elapsed = now - created;
            
            if (elapsed <= 0) return 0;
            if (elapsed >= totalTime) return 100;
            
            return Math.min(100, Math.max(0, (elapsed / totalTime) * 100));
        },

        getProgressBarClass(deadlineString) {
            const percentage = this.getProgressPercentage(deadlineString);
            if (percentage >= 90) return 'bg-red-500';
            if (percentage >= 75) return 'bg-orange-500';
            return 'bg-orange-400';
        },

        getProgressText(deadlineString) {
            const percentage = this.getProgressPercentage(deadlineString);
            if (percentage >= 90) return '⏳ Waktu hampir habis!';
            if (percentage >= 75) return '⏰ Waktu tinggal sedikit';
            if (percentage >= 50) return '⏱️ Waktu setengah jalan';
            return '🕒 Masih ada waktu';
        },

        getCountdownTextClass(deadlineString) {
            const timeLeft = this.getTimeLeft(deadlineString);
            if (timeLeft.expired) return 'text-red-600';
            if (timeLeft.hours === 0 && timeLeft.minutes < 30) return 'text-red-500';
            if (timeLeft.hours === 0) return 'text-orange-500';
            return 'text-orange-600';
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
                'pending': 'Menunggu Verifikasi',
                'confirmed': 'Dikonfirmasi',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            return texts[status] || status;
        },

        getPaymentStatusClass(status) {
            const classes = {
                'verifying': 'text-yellow-600',
                'verified': 'text-blue-600',
                'paid': 'text-green-600',
                'failed': 'text-red-600'
            };
            return classes[status] || 'text-gray-600';
        },

        getPaymentStatusText(status) {
            const texts = {
                'pending': 'Belum Bayar',
                'verifying': 'Menunggu Verifikasi',
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

        formatDateTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
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
            this.selectedReservation = reservation;
            this.showDetailModal = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.showDetailModal = false;
            this.selectedReservation = null;
            document.body.style.overflow = 'auto';
        },

        async cancelReservation(reservationId) {
            // SweetAlert untuk konfirmasi pembatalan
            const result = await Swal.fire({
                title: 'Batalkan Reservasi?',
                text: "Apakah Anda yakin ingin membatalkan reservasi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'px-4 py-2 rounded-md',
                    cancelButton: 'px-4 py-2 rounded-md'
                }
            });

            if (!result.isConfirmed) {
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
                    // Update status di frontend
                    const index = this.reservations.findIndex(r => r.id === reservationId);
                    if (index !== -1) {
                        this.reservations[index].status = 'cancelled';
                    }
                    
                    // SweetAlert sukses
                    await Swal.fire({
                        title: 'Berhasil!',
                        text: 'Reservasi berhasil dibatalkan',
                        icon: 'success',
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    // SweetAlert error dari server
                    await Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat membatalkan reservasi',
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                // SweetAlert error network
                await Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan jaringan saat membatalkan reservasi',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'OK'
                });
            }
        },

        // Function untuk menampilkan notifikasi countdown hampir habis
        showCountdownWarning(reservationId, minutesLeft) {
            // Hanya tampilkan sekali per reservasi
            if (this.warningShown && this.warningShown[reservationId]) return;
            
            if (!this.warningShown) this.warningShown = {};
            this.warningShown[reservationId] = true;

            if (minutesLeft <= 30) {
                Swal.fire({
                    title: 'Waktu Pembayaran Hampir Habis!',
                    html: `Sisa waktu pembayaran DP untuk reservasi #${reservationId} hanya <strong>${minutesLeft} menit</strong> lagi.<br><br>Segera lakukan pembayaran sebelum waktu habis.`,
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Bayar Sekarang',
                    showCancelButton: true,
                    cancelButtonText: 'Nanti',
                    customClass: {
                        confirmButton: 'bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-md',
                        cancelButton: 'bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-md'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/reservation/payment/${reservationId}`;
                    }
                });
            }
        },

        // Function untuk expired payment
        showExpiredNotification(reservationId) {
            Swal.fire({
                title: 'Waktu Pembayaran Habis',
                text: `Waktu pembayaran untuk reservasi #${reservationId} telah habis. Reservasi otomatis dibatalkan.`,
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Mengerti',
                allowOutsideClick: false
            });
        },

        // Cleanup
        destroy() {
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }
        }
    }
}

// Tambahkan event listener untuk menangani expired payments
document.addEventListener('DOMContentLoaded', function() {
    // Cek jika ada parameter expired di URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('expired') === 'true') {
        Swal.fire({
            title: 'Pembayaran Kadaluarsa',
            text: 'Waktu pembayaran DP telah habis. Reservasi Anda otomatis dibatalkan.',
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#f59e0b'
        });
    }
});
</script>

<style>
[x-cloak] { display: none !important; }

/* Animasi untuk countdown yang hampir habis */
@keyframes pulse-warning {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.text-red-500, .text-red-600 {
    animation: pulse-warning 1s ease-in-out infinite;
}

/* Custom styling untuk SweetAlert */
.swal2-popup {
    border-radius: 0.75rem !important;
    font-family: 'Inter', sans-serif !important;
}

.swal2-title {
    font-size: 1.25rem !important;
    font-weight: 600 !important;
}

.swal2-confirm {
    border-radius: 0.5rem !important;
    padding: 0.5rem 1rem !important;
    font-weight: 500 !important;
}

.swal2-cancel {
    border-radius: 0.5rem !important;
    padding: 0.5rem 1rem !important;
    font-weight: 500 !important;
}
</style>
@endpush