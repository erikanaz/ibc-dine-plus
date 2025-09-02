@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold mb-4 gold-text">RIWAYAT RESERVASI</h1>
                <div class="w-20 h-1 gold-bg mx-auto"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                    Lihat semua reservasi yang pernah Anda buat di Ikan Bakar Cianjur Batu Tulis
                </p>
            </div>

            <!-- Filter and Content -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-center p-4 border-b border-gray-200">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-600">
                            Menampilkan <span class="font-medium">{{ $reservations->count() }}</span> reservasi
                        </p>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <span>Filter Reservasi</span>
                            <i class="fas fa-filter"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-200"
                             x-cloak>
                            <a href="{{ route('reservation.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Semua Reservasi</a>
                            <a href="{{ route('reservation.history', ['filter' => 'upcoming']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Akan Datang</a>
                            <a href="{{ route('reservation.history', ['filter' => 'completed']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Selesai</a>
                            <a href="{{ route('reservation.history', ['filter' => 'cancelled']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dibatalkan</a>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Reservasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Orang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reservations as $reservation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $reservation->reservation_code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reservation->reservation_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reservation->reservation_time }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reservation->number_of_people }} Orang</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($reservation->status == 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @elseif($reservation->status == 'confirmed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Akan Datang
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="showDetail({{ json_encode($reservation) }})" class="gold-text hover:text-yellow-700 mr-4">Detail</button>
                                    @if($reservation->status == 'confirmed')
                                        <button @click="confirmCancel({{ $reservation->id }})" class="text-red-600 hover:text-red-900">Batalkan</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Belum ada riwayat reservasi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile List -->
                <div class="md:hidden">
                    @forelse ($reservations as $reservation)
                    <div class="border-b border-gray-200 p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">#{{ $reservation->reservation_code }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $reservation->reservation_date->format('d M Y') }} • {{ $reservation->reservation_time }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $reservation->number_of_people }} Orang</p>
                            </div>
                            @if($reservation->status == 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @elseif($reservation->status == 'confirmed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Akan Datang
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Dibatalkan
                                </span>
                            @endif
                        </div>
                        <div class="mt-3 flex space-x-4">
                            <button @click="showDetail({{ json_encode($reservation) }})" class="text-sm gold-text hover:text-yellow-700">Detail</button>
                            @if($reservation->status == 'confirmed')
                                <button @click="confirmCancel({{ $reservation->id }})" class="text-sm text-red-600 hover:text-red-900">Batalkan</button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-sm text-gray-500">
                        Belum ada riwayat reservasi
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($reservations->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($reservations->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Previous
                            </span>
                        @else
                            <a href="{{ $reservations->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        @endif
                        
                        @if($reservations->hasMorePages())
                            <a href="{{ $reservations->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Next
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $reservations->firstItem() }}</span> sampai <span class="font-medium">{{ $reservations->lastItem() }}</span> dari <span class="font-medium">{{ $reservations->total() }}</span> hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @if($reservations->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $reservations->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif
                                
                                @foreach(range(1, $reservations->lastPage()) as $i)
                                    @if($i == $reservations->currentPage())
                                        <span aria-current="page" class="z-10 bg-yellow-50 border-yellow-500 text-yellow-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $i }}
                                        </span>
                                    @else
                                        <a href="{{ $reservations->url($i) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endforeach
                                
                                @if($reservations->hasMorePages())
                                    <a href="{{ $reservations->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reservation Detail Modal -->
<div class="fixed z-50 inset-0 overflow-y-auto" x-show="showModal" x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" @click.stop>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Detail Reservasi #<span x-text="currentReservation.reservation_code"></span>
                        </h3>
                        <div class="mt-4">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">Informasi Reservasi</h4>
                                    <div class="mt-2 grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Tanggal</p>
                                            <p class="text-sm font-medium text-gray-900" x-text="formatDate(currentReservation.reservation_date)"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Waktu</p>
                                            <p class="text-sm font-medium text-gray-900" x-text="currentReservation.reservation_time"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Jumlah Orang</p>
                                            <p class="text-sm font-medium text-gray-900" x-text="currentReservation.number_of_people + ' Orang'"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Status</p>
                                            <p class="text-sm font-medium" :class="{
                                                'text-yellow-600': currentReservation.status === 'confirmed',
                                                'text-green-600': currentReservation.status === 'completed',
                                                'text-red-600': currentReservation.status === 'cancelled'
                                            }" x-text="getStatusText(currentReservation.status)"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="font-medium text-gray-900">Detail Meja</h4>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Nomor Meja</p>
                                        <p class="text-sm font-medium text-gray-900" x-text="currentReservation.table_number || '-'"></p>
                                    </div>
                                </div>
                                
                                <div x-show="currentReservation.special_request">
                                    <h4 class="font-medium text-gray-900">Catatan Khusus</h4>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-900" x-text="currentReservation.special_request"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 gold-bg text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm" @click="showModal = false">
                    Tutup
                </button>
                <template x-if="currentReservation.status === 'confirmed'">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="cancelReservation(currentReservation.id)">
                        Batalkan Reservasi
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="fixed z-50 inset-0 overflow-y-auto" x-show="showConfirmation" x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showConfirmation = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" @click.stop>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Batalkan Reservasi
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin membatalkan reservasi ini? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm" @click="proceedCancel()">
                    Ya, Batalkan
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showConfirmation = false">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('reservationHistory', () => ({
        showModal: false,
        showConfirmation: false,
        currentReservation: null,
        reservationToCancel: null,
        
        showDetail(reservation) {
            this.currentReservation = reservation;
            this.showModal = true;
        },
        
        confirmCancel(reservationId) {
            this.reservationToCancel = reservationId;
            this.showConfirmation = true;
        },
        
        cancelReservation(reservationId) {
            this.reservationToCancel = reservationId;
            this.showModal = false;
            this.showConfirmation = true;
        },
        
        proceedCancel() {
            axios.post(`/reservations/${this.reservationToCancel}/cancel`)
                .then(response => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert('Gagal membatalkan reservasi');
                });
            
            this.showConfirmation = false;
        },
        
        formatDate(dateString) {
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        },
        
        getStatusText(status) {
            const statusMap = {
                'confirmed': 'Akan Datang',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            return statusMap[status] || status;
        }
    }));
});
</script>
@endpush
@endsection