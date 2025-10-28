@extends('layouts.customer.app')

@section('title', 'Reservasi Berhasil - IBC Batu Tulis')

@section('content')
<div class="container mx-auto px-4 max-w-4xl mt-8 mb-12">
    <!-- Success Icon -->
    <div class="text-center mb-8">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Reservasi Berhasil!</h1>
        <p class="text-gray-600">Terima kasih telah melakukan reservasi di IBC Batu Tulis</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <!-- Informasi Reservasi -->
        <div class="md:col-span-2 space-y-6">
            <!-- Card Detail Reservasi -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Reservasi</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Kode Reservasi</p>
                        <p class="text-lg font-bold text-yellow-600">#{{ $reservation->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                               ($reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-gray-100 text-gray-800') }}">
                            {{ $reservation->status === 'confirmed' ? 'Terkonfirmasi' : 
                               ($reservation->status === 'pending' ? 'Menunggu Konfirmasi' : 
                               'Dibatalkan') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                        <p class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($reservation->reservation_date)->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-gray-600">{{ $reservation->reservation_time }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Meja</p>
                        <p class="font-medium text-gray-800">Meja {{ $reservation->table->number }}</p>
                        <p class="text-sm text-gray-600">Kapasitas: {{ $reservation->table->capacity }} orang</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Tamu</p>
                        <p class="font-medium text-gray-800">{{ $reservation->guest_count }} Orang</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Atas Nama</p>
                        <p class="font-medium text-gray-800">{{ $reservation->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-800">{{ $reservation->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Telepon</p>
                        <p class="font-medium text-gray-800">{{ $reservation->customer_phone }}</p>
                    </div>
                    @if($reservation->notes)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Catatan Khusus</p>
                        <p class="font-medium text-gray-800">{{ $reservation->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card Pesanan Menu -->
            @if($reservation->orders->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Pesanan Menu</h2>
                <div class="space-y-3">
                    @foreach($reservation->orders->first()->orderItems as $orderItem)
                    <div class="flex justify-between items-center py-2 border-b">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-sm bg-gray-100 bg-cover bg-center"
                                 style="background-image: url('{{ asset('images/menus/' . ($orderItem->menu->image ?? 'menu-placeholder.jpg')) }}')">
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $orderItem->menu->name }}</p>
                                <p class="text-sm text-gray-500">{{ $orderItem->qty }} x Rp {{ number_format($orderItem->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <p class="font-medium text-gray-800">
                            Rp {{ number_format($orderItem->qty * $orderItem->price, 0, ',', '.') }}
                        </p>
                    </div>
                    @endforeach
                    
                    <!-- Total Pesanan -->
                    <div class="pt-3 border-t">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Total Pesanan</span>
                            <span class="text-lg font-bold text-yellow-600">
                                Rp {{ number_format($reservation->orders->first()->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Card Pembayaran -->
            <!-- Card Pembayaran -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pembayaran</h2>
                <div class="space-y-3">
                    @php
                        $dpAwal = $reservation->initial_DP; // DP sebelum diskon
                        $diskon = $reservation->discount_amount; // Jumlah diskon
                        $totalDP = $reservation->total_DP; // DP setelah diskon (yang sudah dibayar)
                    @endphp

                    <!-- Tampilkan DP Awal jika ada diskon -->
                    @if($diskon > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">DP Awal:</span>
                        <span class="text-gray-600">
                            Rp {{ number_format($dpAwal, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between text-green-600">
                        <span>Diskon 
                            @if($reservation->promo)
                            ({{ $reservation->promo->promo_code }})
                            @endif
                        </span>
                        <span>- Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center pt-3 border-t">
                        <span class="font-bold text-gray-800">Total DP yang Dibayar</span>
                        <span class="text-lg font-bold text-yellow-600">
                            Rp {{ number_format($totalDP, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-sm font-medium text-gray-800 mb-2">Status Pembayaran:</p>
                        @if($reservation->payments->count() > 0)
                            @php
                                $payment = $reservation->payments->first();
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                ($payment->status === 'verified' ? 'bg-blue-100 text-blue-800' : 
                                ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                'bg-red-100 text-red-800')) }}">
                                @if($payment->status === 'paid')
                                    Lunas
                                @elseif($payment->status === 'verified')
                                    Terverifikasi
                                @elseif($payment->status === 'pending')
                                    Menunggu Verifikasi
                                @else
                                    Ditolak
                                @endif
                            </span>
                            
                            @if($payment->status === 'pending')
                            <p class="text-xs text-gray-600 mt-2">
                                * Pembayaran DP sebesar <strong>Rp {{ number_format($totalDP, 0, ',', '.') }}</strong> 
                                telah dilakukan dan sedang menunggu verifikasi admin
                            </p>
                            @endif
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Belum Bayar
                            </span>
                        @endif
                    </div>
                    
                    <!-- Informasi tambahan untuk pre-order -->
                    @if($reservation->orders->count() > 0)
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> DP yang sudah dibayar (Rp {{ number_format($totalDP, 0, ',', '.') }}) 
                            adalah 30% dari total pesanan setelah diskon. Sisa pembayaran dapat dilakukan saat kedatangan.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Informasi -->
        <div class="space-y-6">
            <!-- Card Kontak -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Butuh Bantuan?</h3>
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-sm text-gray-600">+62 812-3456-7890</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-600">info@ibc-batutulis.com</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm text-gray-600">Jl. Batu Tulis No. 123, Jakarta</span>
                    </div>
                </div>
            </div>

            <!-- Card Tips -->
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-3">Tips untuk Anda</h3>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-500">•</span>
                        <span>Datang 15 menit sebelum waktu reservasi</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-500">•</span>
                        <span>Bawa bukti reservasi (kode: #{{ $reservation->id }})</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-500">•</span>
                        <span>Informasikan jika ada perubahan jumlah tamu</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-500">•</span>
                        <span>Hubungi kami jika perlu membatalkan reservasi</span>
                    </li>
                </ul>
            </div>

            <!-- Tombol Kembali ke Beranda -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <a href="{{ route('homepage') }}" 
                   class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-md font-medium transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Terima Kasih atas Kepercayaan Anda</h3>
        <p class="text-gray-600">
            Kami tunggu kedatangan Anda di IBC Batu Tulis. Untuk pertanyaan atau perubahan reservasi, 
            jangan ragu untuk menghubungi kami.
        </p>
    </div>
</div>
@endsection