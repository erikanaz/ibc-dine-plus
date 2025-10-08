@extends('layouts.admin.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, Admin!')

@section('content')
    <!-- Dashboard Cards -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-1">Dashboard</h2>
        <p class="text-gray-600 text-base">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Reservations Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-primary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Reservasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalReservations }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="fas fa-calendar-check text-primary text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Today's Reservations Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-secondary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Reservasi Hari Ini</p>
                    <p class="text-3xl font-bold mt-2">{{ $todayReservations }}</p>
                </div>
                <div class="bg-secondary/10 p-3 rounded-lg">
                    <i class="fas fa-calendar-day text-secondary text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Monthly Revenue Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-success transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Pendapatan Bulan Ini</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="fas fa-wallet text-success text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Available Tables Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-warning transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Meja Tersedia</p>
                    <p class="text-3xl font-bold mt-2">{{ $availableTables }}/{{ $totalTables }}</p>
                </div>
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="fas fa-chair text-warning text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3 width) -->
        <div class="lg:col-span-2">
            <!-- Today's Reservations Table -->
            <div class="bg-white rounded-xl shadow mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-calendar-day text-secondary mr-2"></i>
                        Reservasi Hari Ini
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($todaysReservationsList as $reservation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($reservation->user->name) }}&background=random" alt="{{ $reservation->user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $reservation->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation->guest_count }} Orang</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reservation->formatted_time }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->reservation_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $reservation->table->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-{{ $reservation->status_color }}/10 text-{{ $reservation->status_color }} px-2 py-1 rounded-full text-xs">
                                        {{ $reservation->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="text-primary hover:text-primary/80 mr-3" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.reservations.edit', $reservation->id) }}" class="text-warning hover:text-warning/80" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada reservasi hari ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t text-center">
                    <a href="{{ route('admin.reservations.index') }}" class="text-primary hover:underline">Lihat semua reservasi</a>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-chart-line text-primary mr-2"></i>
                        Pendapatan 7 Hari Terakhir (DP Reservasi)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-end h-64 space-x-2 justify-center">
                        @php
                            $maxRevenue = $revenueData->max('revenue') ?: 1;
                            $days = [];
                            for ($i = 6; $i >= 0; $i--) {
                                $days[] = now()->subDays($i)->format('Y-m-d');
                            }
                        @endphp
                        
                        @foreach($days as $day)
                            @php
                                $revenue = $revenueData->firstWhere('date', $day);
                                $amount = $revenue ? $revenue->revenue : 0;
                                $height = ($amount / $maxRevenue) * 80;
                                $dayName = \Carbon\Carbon::parse($day)->translatedFormat('D');
                                $dayDate = \Carbon\Carbon::parse($day)->format('d/m');
                            @endphp
                            <div class="flex-1 flex flex-col items-center justify-end">
                                <div 
                                    class="bg-primary w-8 rounded-t-lg transition-all duration-300 hover:bg-primary-dark cursor-pointer" 
                                    style="height: {{ max($height, 10) }}%"
                                    title="Rp {{ number_format($amount, 0, ',', '.') }}"
                                ></div>
                                <p class="mt-2 text-sm text-gray-600">{{ $dayName }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $dayDate }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-600">
                        Total: Rp {{ number_format($revenueData->sum('revenue'), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (1/3 width) -->
        <div>
            <!-- Upcoming Reservations -->
            <div class="bg-white rounded-xl shadow mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-calendar-alt text-warning mr-2"></i>
                        Reservasi Mendatang (3 Hari)
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($upcomingReservations as $reservation)
                    <div class="reservation-card p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-gray-900">{{ $reservation->user->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $reservation->reservation_date->format('d M Y') }} • {{ $reservation->formatted_time }}
                                </p>
                            </div>
                            <span class="status-badge bg-{{ $reservation->status_color }}/10 text-{{ $reservation->status_color }} px-2 py-1 rounded-full text-xs">
                                {{ $reservation->status_label }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center text-sm text-gray-600">
                            <i class="fas fa-users mr-2"></i>
                            <span class="mr-4">{{ $reservation->guest_count }} Orang</span>
                            <i class="fas fa-chair mr-2"></i>
                            <span>{{ $reservation->table->name ?? 'N/A' }}</span>
                        </div>
                        <div class="mt-3 flex items-center text-sm text-gray-600">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            <span>DP: Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-3 flex space-x-2">
                            @if($reservation->status === 'pending')
                            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="text-xs bg-primary text-white px-3 py-2 rounded hover:bg-primary-dark transition-colors">
                                    Konfirmasi
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="text-xs bg-gray-200 text-gray-700 px-3 py-2 rounded hover:bg-gray-300 transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-4">
                        Tidak ada reservasi mendatang
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Table Status -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-chair text-secondary mr-2"></i>
                        Status Meja
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        @php
                            $statusColors = [
                                'available' => ['bg' => 'success', 'text' => 'success', 'label' => 'Tersedia'],
                                'occupied' => ['bg' => 'primary', 'text' => 'primary', 'label' => 'Terisi'],
                                'reserved' => ['bg' => 'warning', 'text' => 'warning', 'label' => 'Reservasi'],
                                'maintenance' => ['bg' => 'gray-400', 'text' => 'gray-500', 'label' => 'Perbaikan']
                            ];
                        @endphp
                        
                        @foreach($tableStatus as $status)
                            @php
                                $colorConfig = $statusColors[$status->status] ?? ['bg' => 'gray', 'text' => 'gray', 'label' => $status->status];
                            @endphp
                            <div class="bg-{{ $colorConfig['bg'] }}/10 border border-{{ $colorConfig['bg'] }} rounded-lg p-3 text-center">
                                <div class="text-2xl font-bold text-{{ $colorConfig['text'] }}">{{ $status->count }}</div>
                                <div class="text-sm text-{{ $colorConfig['text'] }}">{{ $colorConfig['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="font-medium mb-3 text-gray-800">Legenda Status</h4>
                        <div class="space-y-2">
                            @foreach($statusColors as $status => $color)
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-{{ $color['bg'] }} rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700">{{ $color['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Total Meja:</span>
                            <span class="font-bold text-gray-800">{{ $totalTables }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mt-1">
                            <span class="text-gray-600">Tersedia:</span>
                            <span class="font-bold text-success">{{ $availableTables }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.status-badge {
    @apply px-2 py-1 rounded-full text-xs font-medium;
}
.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
.reservation-card:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
@endpush