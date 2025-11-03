@extends('layouts.customer.app')

@section('title', 'Reservasi Berhasil - IBC Batu Tulis')

@section('content')
<div class="container mx-auto px-4 max-w-2xl mt-8 mb-12">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <!-- Success Message -->
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Reservasi Berhasil Dibuat!</h1>
        <p class="text-gray-600 mb-2">Kode Reservasi: <span class="font-mono font-bold text-yellow-600">#{{ $reservation->id }}</span></p>
        
        <!-- Payment Deadline Info -->
        @if($paymentDeadline)
            <div class="bg-orange-50 p-4 rounded-md border border-orange-200 max-w-md mx-auto mb-6">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-orange-800 font-medium">Batas Pembayaran</span>
                </div>
                <p class="text-orange-700 text-sm">
                    Bayar sebelum: <strong>{{ $paymentDeadline->format('d M Y H:i') }}</strong>
                </p>
                <p class="text-orange-600 text-xs mt-1">
                    ({{ $paymentDeadline->diffForHumans() }})
                </p>
            </div>
        @else
            <!-- Fallback jika payment_deadline null -->
            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200 max-w-md mx-auto mb-6">
                <p class="text-yellow-700 text-sm text-center">
                    Silakan lakukan pembayaran dalam <strong>24 jam</strong>
                </p>
            </div>
        @endif

        <!-- Reservation Summary -->
        <div class="bg-gray-50 p-4 rounded-md max-w-md mx-auto mb-6">
            <h3 class="font-medium text-gray-700 mb-3">Detail Reservasi</h3>
            <div class="grid grid-cols-2 gap-3 text-sm text-left">
                <div>
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-medium">{{ $reservation->reservation_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Waktu</p>
                    <p class="font-medium">{{ $reservation->reservation_time }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Meja</p>
                    <p class="font-medium">Meja {{ $reservation->table->number }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tamu</p>
                    <p class="font-medium">{{ $reservation->guest_count }} orang</p>
                </div>
                <div class="col-span-2 border-t pt-2 mt-2">
                    <p class="text-gray-500">Total DP</p>
                    <p class="font-bold text-yellow-600 text-lg">Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <!-- Bayar Sekarang -->
            @if($reservation->status === 'waiting_payment')
                <a href="{{ route('reservation.payment', $reservation) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Bayar Sekarang
                </a>
            @endif
            
            <!-- Lihat Riwayat -->
            <a href="{{ route('reservation.history') }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Lihat Riwayat
            </a>
            
            <!-- Kembali ke Home -->
            <a href="{{ route('homepage') }}" 
               class="inline-flex items-center justify-center px-6 py-3 text-gray-600 font-medium rounded-md hover:text-gray-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Home
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-xs text-gray-500">
            <p>Anda juga dapat membayar nanti melalui menu <strong>Riwayat Reservasi</strong></p>
        </div>
    </div>
</div>
@endsection