@extends('layouts.customer.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <section class="bg-cover bg-center py-40" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/images/ibc-batlis-bg.png')">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">DAFTAR MEJA</h1>
            <p class="text-xl max-w-2xl mx-auto">Status ketersediaan meja <span class="font-semibold">hari ini</span></p>
            <p class="text-lg mt-2 opacity-90">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </section>

    <!-- Info Status -->
    {{-- <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-blue-700 text-sm">
                    <span class="font-semibold">Informasi:</span> Status meja yang ditampilkan adalah kondisi <span class="font-semibold">real-time hari ini</span>. 
                    Untuk reservasi di tanggal lain, silakan gunakan form reservasi.
                </p>
            </div>
        </div>
    </div> --}}

    <!-- Filter Status -->
    <section class="py-6 bg-white sticky top-16 z-10 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap gap-4 justify-center">
                <button class="filter-btn active px-4 py-2 rounded-lg border-2 border-blue-500 bg-gray-100 text-gray-800 font-medium transition-all scale-105" data-status="all">
                    Semua Meja
                </button>
                <button class="filter-btn px-4 py-2 rounded-lg border-2 border-transparent bg-green-100 text-green-800 font-medium transition-all" data-status="available">
                    Tersedia
                </button>
                <button class="filter-btn px-4 py-2 rounded-lg border-2 border-transparent bg-red-100 text-red-800 font-medium transition-all" data-status="occupied">
                    Terpakai
                </button>
                <button class="filter-btn px-4 py-2 rounded-lg border-2 border-transparent bg-yellow-100 text-yellow-800 font-medium transition-all" data-status="reserved">
                    Reservasi
                </button>
                <button class="filter-btn px-4 py-2 rounded-lg border-2 border-transparent bg-blue-100 text-blue-800 font-medium transition-all" data-status="maintenance">
                    Perbaikan
                </button>
            </div>
        </div>
    </section>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-blue-700 text-sm">
                    <span class="font-semibold">Informasi:</span> Status meja yang ditampilkan adalah kondisi <span class="font-semibold">real-time hari ini</span>. 
                    Untuk reservasi di tanggal lain, silakan gunakan form reservasi.
                </p>
            </div>
        </div>
    </div>

    <!-- Daftar Meja -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Summary dengan tanggal -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Statistik Meja</h2>
                <p class="text-gray-600">Per {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $totalTables }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Meja</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $availableTables }}</div>
                    <div class="text-sm text-gray-600 mt-1">Tersedia</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $occupiedTables }}</div>
                    <div class="text-sm text-gray-600 mt-1">Terpakai</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $reservedTables }}</div>
                    <div class="text-sm text-gray-600 mt-1">Reservasi</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $maintenanceTables }}</div>
                    <div class="text-sm text-gray-600 mt-1">Perbaikan</div>
                </div>
            </div>

            <!-- Grid Meja - 4 kolom -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="tables-grid">
                @foreach($tables as $table)
                    <div class="table-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg" data-status="{{ $table->status }}">
                        <!-- Header Meja -->
                        <div class="p-4 border-b">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-800">Meja {{ $table->number }}</h3>
                                <span class="status-badge px-3 py-1 rounded-full text-xs font-semibold 
                                    {{ $table->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $table->status === 'occupied' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $table->status === 'reserved' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $table->status === 'maintenance' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    @if($table->status === 'available') Tersedia
                                    @elseif($table->status === 'occupied') Terpakai
                                    @elseif($table->status === 'reserved') Reservasi
                                    @elseif($table->status === 'maintenance') Perbaikan
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">Kapasitas: {{ $table->capacity }} orang</p>
                        </div>

                        <!-- Info Meja -->
                        <div class="p-4">
                            <div class="space-y-3">
                                <!-- Lokasi -->
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-gray-600">{{ $table->location }}</span>
                                </div>

                                <!-- Status Hari Ini -->
                                <div class="flex items-center text-sm bg-gray-50 p-2 rounded">
                                    <svg class="w-4 h-4 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-600 text-xs">
                                        Status hari ini
                                    </span>
                                </div>

                                <!-- Last Update -->
                                {{-- <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-500 text-xs">
                                        Update: {{ $table->updated_at->diffForHumans() }}
                                    </span>
                                </div> --}}
                            </div>

                            <!-- Status Info -->
                            <div class="mt-4 text-center">
                                <div class="text-sm font-medium 
                                    {{ $table->status === 'available' ? 'text-green-600 bg-green-50 py-2 rounded' : '' }}
                                    {{ $table->status === 'occupied' ? 'text-red-600 bg-red-50 py-2 rounded' : '' }}
                                    {{ $table->status === 'reserved' ? 'text-yellow-600 bg-yellow-50 py-2 rounded' : '' }}
                                    {{ $table->status === 'maintenance' ? 'text-blue-600 bg-blue-50 py-2 rounded' : '' }}">
                                    @if($table->status === 'available') 
                                        ✅ Tersedia Hari Ini
                                    @elseif($table->status === 'occupied') 
                                        ❌ Sedang Digunakan
                                    @elseif($table->status === 'reserved') 
                                        ⏳ Reservasi Hari Ini
                                    @elseif($table->status === 'maintenance') 
                                        🔧 Dalam Perbaikan
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if($tables->count() === 0)
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada meja tersedia</h3>
                    <p class="mt-2 text-gray-500">Semua meja sedang dipakai atau dalam perbaikan.</p>
                </div>
            @endif

            <!-- Call to Action -->
            <div class="text-center mt-12 bg-white rounded-lg shadow-md p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Ingin Melakukan Reservasi?</h3>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    Status yang ditampilkan adalah kondisi <span class="font-semibold">hari ini saja</span>. 
                    Untuk reservasi di tanggal lain atau pengecekan ketersediaan meja berdasarkan tanggal dan waktu tertentu, 
                    silakan gunakan form reservasi kami.
                </p>
                <a href="{{ route('reservation.index') }}" 
                   class="inline-block gold-bg text-white px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-colors text-lg">
                    📅 Buat Reservasi
                </a>
                <p class="text-sm text-gray-500 mt-3">Kami akan cek ketersediaan meja sesuai tanggal yang Anda pilih</p>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableCards = document.querySelectorAll('.table-card');

    // Function untuk menampilkan semua meja
    function showAllTables() {
        tableCards.forEach(card => {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, 50);
        });
    }

    // Tampilkan semua meja saat pertama load
    showAllTables();

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class dari semua button
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'scale-105');
                btn.classList.add('border-transparent');
            });
            
            // Add active class ke button yang diklik
            this.classList.add('active', 'border-blue-500', 'scale-105');
            this.classList.remove('border-transparent');
            
            const status = this.dataset.status;
            
            // Filter meja
            tableCards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Auto-refresh every 30 seconds untuk update status real-time
    setInterval(() => {
        window.location.reload();
    }, 30000);
});
</script>

<style>
.filter-btn.active {
    border-color: #3b82f6 !important;
    transform: scale(1.05);
}

.table-card {
    transition: all 0.3s ease;
}

.status-badge {
    transition: all 0.3s ease;
}
</style>
@endpush