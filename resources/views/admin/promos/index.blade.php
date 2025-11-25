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
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Total Promos -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-primary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Promo</p>
                    <p class="text-3xl font-bold mt-2">{{ $promos->total() }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="fas fa-tags text-primary text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Active Promos -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-success transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Promo Aktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $activePromosCount }}</p>
                </div>
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-success text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Expired Promos -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-warning transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Promo Kadaluarsa</p>
                    <p class="text-3xl font-bold mt-2">{{ $expiredPromosCount }}</p>
                </div>
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-warning text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

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
                    
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.promos.index') }}" class="btn-secondary px-4 py-2 flex items-center">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                    @endif
                </form>
            </div>
            
            <!-- Additional Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Status Filter -->
                {{-- <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600 whitespace-nowrap">Status:</label>
                    <select name="status" onchange="this.form.submit()" 
                            class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
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
                        <tr class="hover:bg-gray-50 transition-colors promo-row" data-status="{{ strtolower($promo->status) }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($promo->promo_code, 0, 3) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 promo-code">{{ $promo->promo_code }}</div>
                                        
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 promo-description">
                                    {{ $promo->description ? Str::limit($promo->description, 60) : 'Tidak ada deskripsi' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-medium text-green-600">
                                    {{ $promo->discount_formatted }}
                                </div>
                                
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $promo->start_date ? $promo->start_date->format('d M Y') : 'Segera' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $promo->end_date ? $promo->end_date->format('d M Y') : 'Selamanya' }}
                                </div>
                                @if($promo->start_date && $promo->end_date)
                                    {{-- <div class="text-xs text-gray-500">
                                        {{ $promo->start_date->diffInDays($promo->end_date) }} hari
                                    </div> --}}
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
                                            $remaining = $promo->usage_limit - $promo->used_count;
                                        @endphp
                                        Sisa: {{ $remaining }}
                                    @else
                                        Tidak terbatas
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge 
                                    @if($promo->status === 'active') bg-success/10 text-success
                                    @elseif($promo->status === 'upcoming') bg-info/10 text-info
                                    @elseif($promo->status === 'expired') bg-warning/10 text-warning
                                    @else bg-secondary/10 text-secondary @endif">
                                    {{ $promo->status_label }}
                                </span>
                                @if($promo->start_date && $promo->end_date)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($promo->status === 'active')
                                            Berakhir {{ $promo->end_date->diffForHumans() }}
                                        @elseif($promo->status === 'upcoming')
                                            Mulai {{ $promo->start_date->diffForHumans() }}
                                        @elseif($promo->status === 'expired')
                                            Kadaluarsa {{ $promo->end_date->diffForHumans() }}
                                        @endif
                                    </div>
                                @endif
                                @if(!$promo->can_be_used && $promo->status === 'active')
                                    <div class="text-xs text-red-500 mt-1">
                                        ⚠️ Batas penggunaan tercapai
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
                {{ $promos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

<!-- Styles dan Scripts tetap sama -->

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