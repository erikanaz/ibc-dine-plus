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
                                <!-- SUPPORT MULTI-TABLE -->
                                @if($reservation->display_table_numbers)
                                    {{ $reservation->display_table_numbers }}
                                    @if($reservation->table_count > 1)
                                        <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-1 py-0.5 rounded">
                                            {{ $reservation->table_count }} meja
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <!-- Informasi tambahan jika ada -->
                        @if($reservation->notes)
                        <div class="mb-3 p-2 bg-yellow-50 rounded border border-yellow-200">
                            <p class="text-xs text-yellow-700">
                                <i class="fas fa-sticky-note mr-1"></i>
                                {{ Str::limit($reservation->notes, 50) }}
                            </p>
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-sm font-semibold text-gray-700">
                                DP: <span class="gold-text">Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                            </span>
                            <div class="flex items-center space-x-2">
                                @if($reservation->status === 'waiting_payment' && $reservation->payment_deadline)
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">
                                    <i class="fas fa-clock mr-1"></i>
                                    Bayar sebelum: {{ $reservation->payment_deadline->format('H:i') }}
                                </span>
                                @endif
                                <a href="{{ route('reservation.history') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                    Detail
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
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
                        Riwayat Reservasi Terakhir
                    </h2>
                    <a href="{{ route('reservation.history') }}" class="text-white text-sm hover:underline">
                        Lihat Semua →
                    </a>
                </div>
                <div class="p-6">
                    @forelse($recentReservations as $reservation)
                    <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-lg px-3 transition-colors">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $reservation->reservation_date->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $reservation->formatted_time }} • {{ $reservation->guest_count }} Orang
                            </p>
                            <!-- SUPPORT MULTI-TABLE -->
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-chair mr-1"></i>
                                @if($reservation->display_table_numbers)
                                    {{ $reservation->display_table_numbers }}
                                    @if($reservation->table_count > 1)
                                        <span class="text-blue-600"> ({{ $reservation->table_count }} meja)</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            {!! $reservation->status_badge !!}
                            <p class="text-xs text-gray-500 mt-1">
                                DP: Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-400">Belum ada riwayat reservasi</p>
                        <p class="text-sm text-gray-500 mt-1">Setelah membuat reservasi, riwayat akan muncul di sini</p>
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
                    <a href="{{ route('reservation.index') }}" class="block w-full gold-bg text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all text-center font-semibold flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Buat Reservasi Baru
                    </a>
                    <a href="{{ route('reservation.history') }}" class="block w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-all text-center font-semibold flex items-center justify-center">
                        <i class="fas fa-history mr-2"></i>
                        Lihat Semua Reservasi
                    </a>
                    {{-- <a href="{{ route('profile.edit') }}" class="block w-full bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 transition-all text-center font-semibold flex items-center justify-center">
                        <i class="fas fa-user-edit mr-2"></i>
                        Edit Profil
                    </a> --}}
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
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-4 mb-3 border-l-4 border-amber-400 hover:shadow-md transition-all cursor-pointer" onclick="window.location.href='{{ route('reservation.index') }}'">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs font-bold text-amber-700 bg-amber-200 px-2 py-0.5 rounded">{{ $promo->promo_code }}</span>
                                    <span class="text-xs font-bold text-amber-600">{{ $promo->name }}</span>
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
                        <p class="text-xs text-gray-400 mt-1">Nantikan promo menarik lainnya</p>
                    </div>
                    @endforelse
                </div>
            </div>

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
                    {{-- <li class="flex items-start">
                        <span class="gold-text mr-2">✓</span>
                        <span>Dapatkan poin setiap reservasi</span>
                    </li> --}}
                    <li class="flex items-start">
                        <span class="gold-text mr-2">✓</span>
                        <span>Pembatalan max 6 jam sebelumnya</span>
                    </li>
                </ul>
                
                <!-- Info Penting -->
                <div class="mt-4 p-3 bg-blue-100 rounded-lg border border-blue-300">
                    <p class="text-xs text-blue-800 font-medium">
                        <i class="fas fa-info-circle mr-1"></i>
                        Sistem multi-table memungkinkan Anda memesan beberapa meja sekaligus
                    </p>
                </div>
            </div>

            
        </div>
    </div>
    
    <!-- FACILITIES SECTION -->
    @if($facilities->isNotEmpty())
    <div class="mb-12" id="facilities-section">
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
                    <div class="bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-300 overflow-hidden hover:border-{{ $facility->color }}-400 group">
                        <div class="p-5">
                            <!-- Header dengan icon dan status -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 rounded-lg bg-{{ $facility->color }}-100 group-hover:scale-110 transition-transform duration-300">
                                    <i class="{{ $facility->icon }} text-{{ $facility->color }}-600 text-lg"></i>
                                </div>
                                <span class="status-badge bg-{{ $facility->is_available ? 'green' : 'red' }}-100 text-{{ $facility->is_available ? 'green' : 'red' }}-800 text-xs">
                                    {{ $facility->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                            
                            <!-- Nama dan deskripsi -->
                            <h3 class="font-bold text-gray-800 mb-2 group-hover:text-{{ $facility->color }}-600 transition-colors">
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
                            
                            <!-- Kapasitas (jika ada) -->
                            @if($facility->capacity)
                            <div class="flex items-center text-gray-500 text-sm mt-2">
                                <i class="fas fa-users mr-2 text-gray-400"></i>
                                <span>Kapasitas: {{ $facility->capacity }} orang</span>
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
</div>

<style>
    /* Custom Styles */
    /* .hero-section {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    } */
    
    /* .gold-text {
        color: #fbbf24;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .gold-bg {
        background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
        box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
    }
    
    .gold-bg:hover {
        background: linear-gradient(135deg, #fbbf24 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
    } */
    
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
    
    /* Animation for cards */
    .hover-lift:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    
    /* Gradient borders */
    .border-gradient {
        border: 2px solid transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #6366f1, #8b5cf6) border-box;
    }
</style>
@endsection

@push('scripts')
<script>
    console.log('Customer Dashboard Loaded');
    
    // Auto refresh stats every 60 seconds
    setInterval(() => {
        fetch('/customer/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update stats counters
                    document.querySelector('[data-stat="total"]').textContent = data.data.total_reservations;
                    document.querySelector('[data-stat="active"]').textContent = data.data.active_reservations;
                    document.querySelector('[data-stat="monthly"]').textContent = data.data.monthly_reservations;
                    document.querySelector('[data-stat="completed"]').textContent = data.data.completed_reservations;
                }
            })
            .catch(error => console.error('Error fetching stats:', error));
    }, 60000);

    // Smooth scroll to facilities section
    document.querySelectorAll('a[href="#facilities-section"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const facilitiesSection = document.getElementById('facilities-section');
            if (facilitiesSection) {
                facilitiesSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add hover effect to reservation cards
    document.addEventListener('DOMContentLoaded', function() {
        const reservationCards = document.querySelectorAll('.bg-gray-50.rounded-lg');
        reservationCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg', 'border-blue-300');
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg', 'border-blue-300');
            });
        });

        // Add click effect to promo cards
        const promoCards = document.querySelectorAll('.bg-gradient-to-r.from-amber-50');
        promoCards.forEach(card => {
            card.addEventListener('click', function() {
                window.location.href = '{{ route("reservation.index") }}';
            });
        });
    });

    // Show notification if there are upcoming reservations with payment deadline
    @if($upcomingReservations->isNotEmpty())
        const upcomingWithDeadline = @json($upcomingReservations->filter(fn($r) => $r->status === 'waiting_payment' && $r->payment_deadline));
        if (upcomingWithDeadline.length > 0) {
            setTimeout(() => {
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-4 right-4 bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 rounded-lg shadow-lg z-50 max-w-sm animate-fade-in-up';
                notification.innerHTML = `
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">Ada reservasi menunggu pembayaran!</p>
                            <p class="text-xs mt-1">Segera selesaikan pembayaran DP sebelum batas waktu.</p>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-orange-500 hover:text-orange-700 rounded-lg p-1.5 inline-flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
                
                notification.querySelector('button').addEventListener('click', function() {
                    notification.remove();
                });
                
                document.body.appendChild(notification);
                
                // Auto remove after 10 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 10000);
            }, 2000);
        }
    @endif

    // Animation for stats cards
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    // Observe stats cards
    document.querySelectorAll('.bg-white.rounded-xl').forEach(card => {
        observer.observe(card);
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    /* Stagger animations */
    .bg-white.rounded-xl:nth-child(1) { animation-delay: 0.1s; }
    .bg-white.rounded-xl:nth-child(2) { animation-delay: 0.2s; }
    .bg-white.rounded-xl:nth-child(3) { animation-delay: 0.3s; }
    
    /* Hover animations */
    .hover-scale:hover {
        transform: scale(1.02);
        transition: transform 0.3s ease;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush