@extends('layouts.admin.app')

@section('title', 'Manajemen Promo')
@section('subtitle', 'Kelola promo dan diskon restoran')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-1">Manajemen Promo</h2>
                <p class="text-gray-600 text-base">Kelola semua promo dan diskon restoran</p>
            </div>
            {{-- <div class="flex space-x-3">
                <a href="{{ route('admin.promos.create') }}" class="btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Promo
                </a>
            </div> --}}
        </div>
    </div>

    <!-- Stats Cards -->
    <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6"> -->
        <!-- Total Promos -->
        <!-- <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-primary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Promo</p>
                    <p class="text-3xl font-bold mt-2">{{ $promos->total() }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="fas fa-tags text-primary text-2xl"></i>
                </div>
            </div>
        </div> -->
        
        <!-- Active Promos -->
        <!-- <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-success transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Promo Aktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $activePromosCount }}</p>
                </div>
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-success text-2xl"></i>
                </div>
            </div>
        </div> -->
        
        <!-- Percent Discount -->
        <!-- <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-info transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Diskon Persentase</p>
                    <p class="text-3xl font-bold mt-2">{{ $percentPromosCount }}</p>
                </div>
                <div class="bg-info/10 p-3 rounded-lg">
                    <i class="fas fa-percentage text-info text-2xl"></i>
                </div>
            </div>
        </div> -->
        
        <!-- Fixed Discount -->
        <!-- <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-warning transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Diskon Nominal</p>
                    <p class="text-3xl font-bold mt-2">{{ $fixedPromosCount }}</p>
                </div>
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-warning text-2xl"></i>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <form action="{{ route('admin.promos.index') }}" method="GET" class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari kode promo atau deskripsi..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" class="btn-primary px-4">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    
                    @if(request('search') || request('status') || request('type'))
                    <a href="{{ route('admin.promos.index') }}" class="btn-secondary px-4 py-2 flex items-center">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                    @endif
                </form>
            </div>
            
            <!-- Additional Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Status Filter -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600 whitespace-nowrap">Status:</label>
                    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                </div>
                
                <!-- Type Filter -->
                {{-- <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600 whitespace-nowrap">Tipe:</label>
                    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>Persentase</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Nominal</option>
                    </select>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Promos Table -->
    <div class="bg-white rounded-xl shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg flex items-center">
                <i class="fas fa-list text-primary mr-2"></i>
                Daftar Promo
            </h3>
             <div class="flex items-center space-x-4 px-2">
                <a href="{{ route('admin.promos.create') }}" class="btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Promo
                </a>
            </div>
            {{-- <div class="flex items-center space-x-2"> --}}
                <!-- <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari promo..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div> -->
                <!-- <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                    <option value="akan datang">Akan Datang</option>
                </select> -->
            {{-- </div> --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode Promo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diskon
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Batas Penggunaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="promoTableBody">
                    @forelse($promos as $promo)
                        @php
                            $status = $promo->getStatusAttribute();
                            $statusColor = $promo->getStatusColorAttribute();
                            $statusLabel = $promo->getStatusLabelAttribute();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors promo-row" data-status="{{ strtolower($statusLabel) }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($promo->promo_code, 0, 3) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 promo-code">{{ $promo->promo_code }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $promo->type == 'percent' ? 'Diskon Persen' : 'Diskon Tetap' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 promo-description">
                                    {{ $promo->description ? Str::limit($promo->description, 60) : 'Tidak ada deskripsi' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-green-600">
                                    @if($promo->type == 'percent')
                                        {{ $promo->discount }}%
                                    @else
                                        Rp {{ number_format($promo->discount, 0, ',', '.') }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 capitalize">
                                    {{ $promo->type }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $promo->start_date ? $promo->start_date->format('d M Y') : '-' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $promo->end_date ? $promo->end_date->format('d M Y') : '-' }}
                                </div>
                                @if($promo->start_date && $promo->end_date)
                                    <div class="text-xs text-gray-500">
                                        {{ $promo->start_date->diffInDays($promo->end_date) }} hari
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($promo->usage_limit)
                                        {{ $promo->usage_limit }} kali
                                    @else
                                        <span class="text-gray-400">Unlimited</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if($promo->usage_limit)
                                        @php
                                            $usedCount = $promo->reservations()->count();
                                            $remaining = $promo->usage_limit - $usedCount;
                                        @endphp
                                        Sisa: {{ $remaining }}
                                    @else
                                        Tidak terbatas
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge 
                                    @if($statusColor === 'success') bg-success/10 text-success
                                    @elseif($statusColor === 'warning') bg-warning/10 text-warning
                                    @elseif($statusColor === 'info') bg-info/10 text-info
                                    @elseif($statusColor === 'danger') bg-red-100 text-red-600
                                    @else bg-secondary/10 text-secondary @endif">
                                    {{ $statusLabel }}
                                </span>
                                @if($promo->start_date && $promo->end_date)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($status === 'active')
                                            {{ $promo->end_date->diffForHumans() }}
                                        @elseif($status === 'upcoming')
                                            Mulai {{ $promo->start_date->diffForHumans() }}
                                        @elseif($status === 'expired')
                                            Kadaluarsa {{ $promo->end_date->diffForHumans() }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.promos.edit', $promo->id) }}" 
                                       class="text-primary hover:text-primary/80 transition-colors"
                                       title="Edit Promo">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.promos.destroy', $promo->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus promo {{ $promo->promo_code }}?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Hapus Promo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-tags text-4xl mb-2"></i>
                                <p>Belum ada promo</p>
                                <a href="{{ route('admin.promos.create') }}" class="text-primary hover:underline mt-2 inline-block">
                                    Buat promo pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($promos->hasPages())
            <div class="px-6 py-4 border-t">
                {{-- {{ $promos->links() }} --}}
                {{ $promos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@section('styles')
<style>
    .btn-primary {
        background-color: #3b82f6;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Color definitions */
    .bg-primary { background-color: #3b82f6; }
    .text-primary { color: #3b82f6; }
    .bg-primary\/10 { background-color: rgba(59, 130, 246, 0.1); }
    .border-primary { border-color: #3b82f6; }

    .bg-success { background-color: #10b981; }
    .text-success { color: #10b981; }
    .bg-success\/10 { background-color: rgba(16, 185, 129, 0.1); }
    .border-success { border-color: #10b981; }

    .bg-warning { background-color: #f59e0b; }
    .text-warning { color: #f59e0b; }
    .bg-warning\/10 { background-color: rgba(245, 158, 11, 0.1); }
    .border-warning { border-color: #f59e0b; }

    .bg-info { background-color: #06b6d4; }
    .text-info { color: #06b6d4; }
    .bg-info\/10 { background-color: rgba(6, 182, 212, 0.1); }
    .border-info { border-color: #06b6d4; }

    .bg-secondary { background-color: #6b7280; }
    .text-secondary { color: #6b7280; }
    .bg-secondary\/10 { background-color: rgba(107, 114, 128, 0.1); }
    .border-secondary { border-color: #6b7280; }

    .bg-red-100 { background-color: #fee2e2; }
    .text-red-600 { color: #dc2626; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const promoRows = document.querySelectorAll('.promo-row');

        function filterPromos() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            promoRows.forEach(row => {
                const code = row.querySelector('.promo-code').textContent.toLowerCase();
                const description = row.querySelector('.promo-description').textContent.toLowerCase();
                const status = row.getAttribute('data-status');

                const matchesSearch = code.includes(searchTerm) || description.includes(searchTerm);
                const matchesStatus = !statusValue || status.includes(statusValue);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterPromos);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', filterPromos);
        }
    });
</script>
@endsection