@extends('layouts.admin.app')

@section('title', 'Manajemen Reservasi')
@section('subtitle', 'Kelola reservasi dan pesanan restoran')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-1">Manajemen Reservasi</h2>
                <p class="text-gray-600 text-base">Kelola semua reservasi dan pesanan restoran</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reservations.create') }}" class="btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Reservasi
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <!-- Total Reservations -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-primary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Reservasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $reservations->total() }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="fas fa-calendar-alt text-primary text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Pending -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-warning transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Menunggu</p>
                    <p class="text-3xl font-bold mt-2">{{ $statusCounts['pending'] }}</p>
                </div>
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="fas fa-clock text-warning text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Confirmed -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-success transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Dikonfirmasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $statusCounts['confirmed'] }}</p>
                </div>
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-success text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Completed -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-secondary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Selesai</p>
                    <p class="text-3xl font-bold mt-2">{{ $statusCounts['completed'] }}</p>
                </div>
                <div class="bg-secondary/10 p-3 rounded-lg">
                    <i class="fas fa-flag-checkered text-secondary text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Cancelled -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-red-500 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Dibatalkan</p>
                    <p class="text-3xl font-bold mt-2">{{ $statusCounts['cancelled'] + $statusCounts['expired'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-xl shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg flex items-center">
                <i class="fas fa-list text-primary mr-2"></i>
                Daftar Reservasi & Pesanan
            </h3>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari customer atau meja..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID & Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal & Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Meja & Tamu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pesanan & Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            DP & Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="reservationTableBody">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50 transition-colors reservation-row" data-status="{{ $reservation->status }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">#{{ $reservation->id }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <!-- PERBAIKAN DI SINI: Handle user yang null -->
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $reservation->customer_name }}
                                            @if($reservation->user_id)
                                                <span class="text-xs text-green-600 ml-1">
                                                    <i class="fas fa-user-check"></i> Member
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500 ml-1">
                                                    <i class="fas fa-user"></i> Guest
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $reservation->customer_email }}</div>
                                        <div class="text-xs text-gray-500">
                                            @if($reservation->promo)
                                                <i class="fas fa-tag text-green-500 mr-1"></i>{{ $reservation->promo->promo_code }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $reservation->reservation_date->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $reservation->formatted_time }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $reservation->created_at->format('d M H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Meja {{ $reservation->table->number }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $reservation->guest_count }} orang
                                </div>
                                <div class="text-xs text-gray-500 capitalize">
                                    {{ $reservation->table->location_label }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($reservation->order && $reservation->order->orderItems->count() > 0)
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $reservation->total_items }} item
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $reservation->order->orderItems->count() }} jenis menu
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-bold text-primary">
                                                    Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($reservation->order->orderItems->take(3) as $item)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    {{ $item->menu->name }} ({{ $item->qty }})
                                                </span>
                                            @endforeach
                                            @if($reservation->order->orderItems->count() > 3)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                    +{{ $reservation->order->orderItems->count() - 3 }} lainnya
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <div class="text-sm text-gray-500">Belum ada pesanan</div>
                                        <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                                           class="text-xs text-primary hover:underline">
                                            Tambah pesanan
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-orange-600">
                                            DP: Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="status-badge 
                                            @if($reservation->status === 'pending') bg-warning/10 text-warning
                                            @elseif($reservation->status === 'confirmed') bg-success/10 text-success
                                            @elseif($reservation->status === 'completed') bg-secondary/10 text-secondary
                                            @elseif(in_array($reservation->status, ['cancelled', 'expired'])) bg-red-100 text-red-600 @endif">
                                            {{ $reservation->status_label }}
                                        </span>
                                    </div>
                                    @if($reservation->order && $reservation->order->total_price > 0)
                                        <div class="text-xs text-gray-500">
                                            Sisa: Rp {{ number_format($reservation->order->total_price - $reservation->total_DP, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors"
                                       title="Detail Lengkap">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.reservations.edit', $reservation->id) }}" 
                                       class="text-primary hover:text-primary/80 transition-colors"
                                       title="Edit Reservasi">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.reservations.invoice', $reservation->id) }}" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-800 transition-colors"
                                       title="Cetak Invoice">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus reservasi #{{ $reservation->id }}?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Hapus Reservasi">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Quick Actions -->
                                <div class="flex justify-end space-x-1 mt-2">
                                    @if($reservation->status === 'confirmed')
                                        <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" 
                                                    class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200 transition-colors"
                                                    title="Tandai Selesai">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(!in_array($reservation->status, ['cancelled', 'completed', 'expired']))
                                        <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" 
                                                    class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 transition-colors"
                                                    title="Batalkan">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                <p>Belum ada reservasi</p>
                                <a href="{{ route('admin.reservations.create') }}" class="text-primary hover:underline mt-2 inline-block">
                                    Buat reservasi pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($reservations->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
@endsection

@section('styles')
<style>
    /* Styles tetap sama */
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const reservationRows = document.querySelectorAll('.reservation-row');

        function filterReservations() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            reservationRows.forEach(row => {
                const customerName = row.querySelector('td:first-child .text-sm.font-medium').textContent.toLowerCase();
                const customerEmail = row.querySelector('td:first-child .text-sm.text-gray-600').textContent.toLowerCase();
                const tableNumber = row.querySelector('td:nth-child(3) .text-sm.font-medium').textContent.toLowerCase();
                const status = row.getAttribute('data-status');

                const matchesSearch = customerName.includes(searchTerm) || 
                                    customerEmail.includes(searchTerm) || 
                                    tableNumber.includes(searchTerm);
                const matchesStatus = !statusValue || status.includes(statusValue);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterReservations);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', filterReservations);
        }
    });
</script>
@endsection