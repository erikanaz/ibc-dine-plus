@extends('layouts.customer.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Pilih Meja</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Pilih meja yang sesuai dengan kebutuhan Anda. Tersedia berbagai pilihan meja dengan kapasitas berbeda.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <!-- Filter and Search -->
            <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="flex flex-wrap gap-4 items-center">
                        <!-- Capacity Filter -->
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600 font-medium">Kapasitas:</span>
                            <select id="capacityFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                <option value="all">Semua</option>
                                <option value="2">2 Orang</option>
                                <option value="4">4 Orang</option>
                                <option value="6">6 Orang</option>
                                <option value="8">8+ Orang</option>
                            </select>
                        </div>

                        <!-- Location Filter -->
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600 font-medium">Lokasi:</span>
                            <select id="locationFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                <option value="all">Semua Area</option>
                                <option value="indoor">Indoor</option>
                                <option value="outdoor">Outdoor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="relative">
                        <input type="text" id="tableSearch" placeholder="Cari nomor meja..." 
                               class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8" id="tablesContainer">
                @forelse($tables as $table)
                    <div class="table-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg"
                         data-capacity="{{ $table->capacity }}"
                         data-location="{{ $table->location }}"
                         data-number="{{ $table->number }}">
                        <!-- Table Status Badge -->
                        <div class="absolute top-4 right-4 z-10">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $table->status_label }}
                            </span>
                        </div>

                        <!-- Table Image/Icon -->
                        <div class="h-48 bg-gray-100 flex items-center justify-center relative">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-500 text-sm">Meja {{ $table->number }}</span>
                            </div>
                        </div>

                        <!-- Table Info -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800">Meja {{ $table->number }}</h3>
                                <span class="text-lg font-semibold text-yellow-600">{{ $table->capacity }} Orang</span>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $table->location_label }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Status: {{ $table->status_label }}</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-6">
                                <button onclick="openReservationModal({{ $table->id }}, '{{ $table->number }}', {{ $table->capacity }}, '{{ $table->location_label }}')"
                                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Reservasi Meja
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Meja Tersedia</h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Saat ini tidak ada meja yang tersedia untuk reservasi. Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('customer.menu') }}" class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Lihat Menu
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tables->hasPages())
                <div class="bg-white rounded-lg shadow-sm p-6">
                    {{ $tables->links() }}
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Reservation Modal -->
<div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Reservasi Meja</h3>
            <p class="text-gray-600 mt-1" id="modalTableInfo"></p>
        </div>

        <form id="reservationForm" action="{{ route('customer.reservations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="table_id" id="reservationTableId">
            
            <div class="p-6 space-y-4">
                <!-- Customer Information -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pemesan *
                    </label>
                    <input type="text" 
                           id="customer_name" 
                           name="customer_name" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                           placeholder="Masukkan nama lengkap"
                           required>
                </div>

                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        No. Telepon *
                    </label>
                    <input type="tel" 
                           id="customer_phone" 
                           name="customer_phone" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                           placeholder="Contoh: 081234567890"
                           required>
                </div>

                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           id="customer_email" 
                           name="customer_email" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                           placeholder="email@contoh.com">
                </div>

                <!-- Date Selection -->
                <div>
                    <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Reservasi *
                    </label>
                    <input type="date" 
                           id="reservation_date" 
                           name="reservation_date" 
                           min="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                           required>
                </div>

                <!-- Time Selection -->
                <div>
                    <label for="reservation_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Reservasi *
                    </label>
                    <select id="reservation_time" name="reservation_time" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                        <option value="">Pilih Waktu</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                        <option value="21:00">21:00</option>
                    </select>
                </div>

                <!-- Number of People -->
                <div>
                    <label for="number_of_people" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Orang *
                    </label>
                    <input type="number" 
                           id="number_of_people" 
                           name="number_of_people" 
                           min="1"
                           max="10"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                           required>
                </div>

                <!-- Special Requests -->
                <div>
                    <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Khusus (Opsional)
                    </label>
                    <textarea id="special_requests" 
                              name="special_requests" 
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                              placeholder="Contoh: Meja dekat jendela, ada anak kecil, alergi makanan tertentu, dll."></textarea>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex space-x-3">
                <button type="button" 
                        onclick="closeReservationModal()"
                        class="flex-1 bg-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg transition duration-300 hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-4 rounded-lg transition duration-300">
                    Reservasi Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const capacityFilter = document.getElementById('capacityFilter');
        const locationFilter = document.getElementById('locationFilter');
        const tableSearch = document.getElementById('tableSearch');
        const tableCards = document.querySelectorAll('.table-card');

        function filterTables() {
            const capacityValue = capacityFilter.value;
            const locationValue = locationFilter.value;
            const searchValue = tableSearch.value.toLowerCase();

            let visibleCount = 0;

            tableCards.forEach(card => {
                const capacity = card.getAttribute('data-capacity');
                const location = card.getAttribute('data-location');
                const number = card.getAttribute('data-number').toLowerCase();

                const capacityMatch = capacityValue === 'all' || capacity === capacityValue;
                const locationMatch = locationValue === 'all' || location === locationValue;
                const searchMatch = number.includes(searchValue);

                if (capacityMatch && locationMatch && searchMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show empty state if no tables visible
            const emptyState = document.querySelector('.col-span-full');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        capacityFilter.addEventListener('change', filterTables);
        locationFilter.addEventListener('change', filterTables);
        tableSearch.addEventListener('input', filterTables);

        // Set today's date as default for reservation date
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('reservation_date').value = today;
    });

    // Reservation Modal Functions
    function openReservationModal(tableId, tableNumber, capacity, location) {
        document.getElementById('reservationTableId').value = tableId;
        document.getElementById('modalTableInfo').textContent = `Meja ${tableNumber} - ${location} - Kapasitas ${capacity} orang`;
        document.getElementById('number_of_people').max = capacity;
        document.getElementById('number_of_people').value = capacity;
        document.getElementById('reservationModal').classList.remove('hidden');
    }

    function closeReservationModal() {
        document.getElementById('reservationModal').classList.add('hidden');
        document.getElementById('reservationForm').reset();
        
        // Reset date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('reservation_date').value = today;
    }

    // Close modal when clicking outside
    document.getElementById('reservationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReservationModal();
        }
    });

    // Form validation
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        const numberOfPeople = parseInt(document.getElementById('number_of_people').value);
        const capacity = parseInt(document.getElementById('number_of_people').max);
        
        if (numberOfPeople > capacity) {
            e.preventDefault();
            alert(`Jumlah orang tidak boleh melebihi kapasitas meja (${capacity} orang)`);
            return false;
        }
    });
</script>
@endpush

@push('styles')
<style>
    .table-card {
        transition: all 0.3s ease;
    }

    .table-card:hover {
        transform: translateY(-5px);
    }

    /* Custom scrollbar for filters */
    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush