@extends('layouts.admin.app')

@section('title', 'Manajemen Menu')
@section('subtitle', 'Kelola menu restoran Anda')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-1">Manajemen Menu</h2>
                <!-- <p class="text-gray-600 text-base">Kelola menu restoran Anda</p> -->
            </div>
            <!-- <a href="{{ route('admin.menus.create') }}" class="btn-primary flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Menu
            </a> -->
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <form action="{{ route('admin.menus.index') }}" method="GET" class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari menu atau kategori..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" class="btn-primary px-4">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request('search') || request('category') || request('status') || request('sort'))
                    <a href="{{ route('admin.menus.index') }}" class="btn-secondary px-4">
                        <i class="fas fa-times mr-2 mt-3"></i>Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>
        </form>
    </div>

    <!-- Menu Items Table -->
    <div class="bg-white rounded-xl shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg flex items-center">
                <i class="fas fa-list-ul text-primary mr-2"></i>
                Daftar Menu
                @if(request('search') || request('category') || request('status'))
                <span class="text-sm font-normal text-gray-500 ml-2">
                    (Filter aktif)
                </span>
                @endif
            </h3>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                    Total: <span class="font-semibold">{{ $menus->total() }}</span> menu
                </div>
                <a href="{{ route('admin.menus.create') }}" class="btn-primary flex items-center px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Menu
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($menus as $menu)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-lg object-cover" 
                                         src="{{ $menu->image_url }}" 
                                         alt="{{ $menu->name }}"
                                         onerror="this.src='https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $menu->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($menu->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="category-badge {{ $menu->category_badge_class }}">{{ $menu->formatted_category }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $menu->formatted_price }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge {{ $menu->status_badge_class }}">{{ $menu->status_text }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $menu->stock_status }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.menus.show', $menu->id) }}" class="text-primary hover:text-primary/80 mr-3" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-warning hover:text-warning/80 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:text-danger/80" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center py-4">
                                <i class="fas fa-utensils text-4xl text-gray-300 mb-2"></i>
                                <p>Tidak ada data menu.</p>
                                @if(request('search') || request('category') || request('status'))
                                <a href="{{ route('admin.menus.index') }}" class="btn-primary mt-2 inline-flex items-center">
                                    <i class="fas fa-times mr-1"></i> Reset Filter
                                </a>
                                @else
                                <a href="{{ route('admin.menus.create') }}" class="btn-primary mt-2 inline-flex items-center">
                                    <i class="fas fa-plus mr-1"></i> Tambah Menu Pertama
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination - Updated to match promo style -->
        @if($menus->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $menus->links() }}
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
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
    }
    
    .category-badge, .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }

    /* Pagination Styles - Matching promo style */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .pagination .flex {
        display: flex;
        gap: 0.5rem;
    }

    .pagination .flex-1 {
        display: none;
    }

    .pagination span[aria-hidden="true"] > span {
        display: none;
    }

    .pagination .relative {
        display: none;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination a {
        background-color: white;
        color: #374151;
        border-color: #d1d5db;
    }

    .pagination a:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
    }

    .pagination span[aria-current="page"] span {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination .text-gray-500 {
        background-color: #f9fafb;
        color: #6b7280;
        border-color: #d1d5db;
        cursor: not-allowed;
    }

    /* Ensure the pagination links are properly styled */
    .pagination .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .pagination .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    /* Mobile responsive */
    @media (max-width: 640px) {
        .pagination {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .pagination a,
        .pagination span {
            min-width: 2.25rem;
            height: 2.25rem;
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@push('scripts')
<script>
// Auto submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('select[name="category"], select[name="status"], select[name="sort"]');
    filters.forEach(filter => {
        filter.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush