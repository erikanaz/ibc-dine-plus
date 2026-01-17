@extends('layouts.customer.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section py-20 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Selamat Datang, <span class="gold-text">{{ Auth::user()->name }}!</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8">
                Nikmati pengalaman reservasi yang mudah di Ikan Bakar Cianjur
            </p>
            <a href="{{ route('reservation.index') }}" class="inline-block gold-bg text-white px-8 py-4 rounded-lg font-semibold text-lg hover:shadow-xl transition-all duration-300">
                🍽️ Buat Reservasi Baru
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="container mx-auto px-4 -mt-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Total Reservasi -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Reservasi</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalReservations }}</h3>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reservasi Aktif -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Reservasi Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $activeReservations }}</h3>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reservasi Bulan Ini -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Reservasi Bulan Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $monthlyReservations }}</h3>
                    <p class="text-xs text-gray-400 mt-2">📅 {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Left Column: Reservasi Mendatang & Riwayat -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Reservasi Mendatang -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Reservasi Mendatang
                    </h2>
                </div>
                <div class="p-6">
                    @forelse($upcomingReservations as $reservation)
                    <div class="bg-gray-50 rounded-lg p-5 mb-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">{{ $reservation->customer_name }}</h3>
                                <p class="text-gray-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $reservation->reservation_date->format('d M Y') }} • {{ $reservation->formatted_time }}
                                </p>
                            </div>
                            {!! $reservation->status_badge !!}
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $reservation->guest_count }} Orang
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Meja {{ $reservation->table->number }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-sm font-semibold text-gray-700">
                                DP: <span class="gold-text">Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                            </span>
                            <a href="{{ route('reservation.history', $reservation->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 mb-4">Belum ada reservasi mendatang</p>
                        <a href="{{ route('reservation.index') }}" class="inline-block gold-bg text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                            Buat Reservasi
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Riwayat Reservasi Terakhir -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Reservasi
                    </h2>
                    <a href="{{ route('reservation.index') }}" class="text-white text-sm hover:underline">
                        Lihat Semua →
                    </a>
                </div>
                <div class="p-6">
                    @forelse($recentReservations as $reservation)
                    <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-0">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $reservation->reservation_date->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $reservation->formatted_time }} • {{ $reservation->guest_count }} Orang</p>
                        </div>
                        <div class="text-right">
                            {!! $reservation->status_badge !!}
                            <p class="text-xs text-gray-500 mt-1">Meja {{ $reservation->table->number }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        Belum ada riwayat reservasi
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Info -->
        <div class="space-y-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Menu Cepat
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('reservation.index') }}" class="block w-full gold-bg text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all text-center font-semibold">
                        🍽️ Buat Reservasi Baru
                    </a>
                    <a href="{{ route('reservation.history') }}" class="block w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-all text-center font-semibold">
                        📋 Lihat Semua Reservasi
                    </a>
                    <a href="#" class="block w-full bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 transition-all text-center font-semibold">
                        👤 Edit Profil
                    </a>
                </div>
            </div>

            <!-- Promo Aktif -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                        Promo Spesial
                    </h3>
                </div>
                <div class="p-4">
                    @forelse($activePromos ?? [] as $promo)
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-4 mb-3 border-l-4 border-amber-400 hover:shadow-md transition-all cursor-pointer">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs font-bold text-amber-700 bg-amber-200 px-2 py-0.5 rounded">{{ $promo->promo_code }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $promo->description }}</p>
                            </div>
                            <div class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold ml-2 whitespace-nowrap">
                                {{ $promo->discount_badge }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mt-2 pt-2 border-t border-amber-200">
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                s/d {{ $promo->end_date->format('d M Y') }}
                            </span>
                            @if($promo->usage_limit)
                            <span class="text-amber-600 font-semibold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                Kuota: {{ $promo->usage_limit }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada promo aktif</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Informasi Kontak -->
            {{-- <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-lg p-6 border border-amber-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Hubungi Kami
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Telepon</p>
                            <p class="text-sm text-gray-600">+62 812-3456-7890</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Alamat</p>
                            <p class="text-sm text-gray-600">Jl. Raya Cianjur No. 123</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Jam Operasional</p>
                            <p class="text-sm text-gray-600">10:00 - 22:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Tips & Info -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border border-blue-200">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Tips Reservasi
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="gold-text mr-2">✓</span>
                        <span>Reservasi H-1 untuk weekend</span>
                    </li>
                    <li class="flex items-start">
                        <span class="gold-text mr-2">✓</span>
                        <span>Konfirmasi 2 jam sebelumnya</span>
                    </li>
                    <li class="flex items-start">
                        <span class="gold-text mr-2">✓</span>
                        <span>Dapatkan poin setiap reservasi</span>
                    </li>
                    <li class="flex items-start">
                        <span class="gold-text mr-2">✓</span>
                        <span>Pembatalan max 6 jam sebelumnya</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- ====================================================== -->
    <!-- FACILITIES SECTION - TAMBAHKAN DI SINI -->
    <!-- ====================================================== -->
    @if($facilities->isNotEmpty())
    <div class="mb-12">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-concierge-bell mr-3 text-white"></i>
                    Fasilitas Restoran
                </h2>
                <span class="text-white text-sm font-medium bg-white/20 px-3 py-1 rounded-full">
                    {{ $facilities->count() }} Fasilitas
                </span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($facilities as $facility)
                    <div class="bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-300 overflow-hidden hover:{{ $facility->icon_border_class }} group">
                        <div class="p-5">
                            <!-- Header dengan icon dan status -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 rounded-lg {{ $facility->icon_color_class }} group-hover:scale-110 transition-transform duration-300">
                                    <i class="{{ $facility->icon_class }} text-lg"></i>
                                </div>
                                <span class="status-badge {{ $facility->status_badge_class }} text-xs">
                                    {{ $facility->status_text }}
                                </span>
                            </div>
                            
                            <!-- Nama dan deskripsi -->
                            <h3 class="font-bold text-gray-800 mb-2 group-hover:{{ str_replace('text-', 'hover:', $facility->icon_text_class) }} transition-colors">
                                {{ $facility->name }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ $facility->description }}
                            </p>
                            
                            <!-- Lokasi -->
                            @if($facility->location)
                            <div class="flex items-center text-gray-500 text-sm pt-3 border-t border-gray-100">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                <span>{{ $facility->location }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- ====================================================== -->
</div>
<style>
    /* CSS tambahan untuk fasilitas */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Warna untuk icon */
    .text-blue-600 { color: #2563eb; }
    .bg-blue-100 { background-color: #dbeafe; }
    .text-yellow-600 { color: #d97706; }
    .bg-yellow-100 { background-color: #fef3c7; }
    .text-pink-600 { color: #db2777; }
    .bg-pink-100 { background-color: #fce7f3; }
    .text-cyan-600 { color: #0891b2; }
    .bg-cyan-100 { background-color: #cffafe; }
    .text-purple-600 { color: #9333ea; }
    .bg-purple-100 { background-color: #f3e8ff; }
    .text-orange-600 { color: #ea580c; }
    .bg-orange-100 { background-color: #ffedd5; }
    .text-gray-600 { color: #4b5563; }
    .bg-gray-100 { background-color: #f3f4f6; }
</style>
@endsection

@push('scripts')
<script>
    // Add any interactive features here if needed
    // console.log('Customer Dashboard Loaded');
    // Add any interactive features here if needed
    console.log('Customer Dashboard Loaded');
    
    // Smooth scroll to facilities section
    document.querySelectorAll('a[href="#facilities-section"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const facilitiesSection = document.getElementById('facilities-section');
            if (facilitiesSection) {
                facilitiesSection.scrollIntoView({ 
                    behavior: 'smooth' 
                });
            }
        });
    });
</script>
@endpush