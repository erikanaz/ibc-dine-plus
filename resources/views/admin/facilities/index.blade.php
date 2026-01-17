@extends('layouts.admin.app')

@section('title', 'Manajemen Fasilitas')
@section('subtitle', 'Kelola fasilitas restoran')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Manajemen Fasilitas</h2>
        </div>
        {{-- <div class="flex gap-3">
            <form action="{{ route('admin.facilities.seed') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-secondary flex items-center" 
                        onclick="return confirm('Tambahkan 7 fasilitas default?')">
                    <i class="fas fa-seedling mr-2"></i>
                    Tambah Default
                </button>
            </form>
            <a href="{{ route('admin.facilities.create') }}" class="btn-primary flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Fasilitas
            </a>
        </div> --}}
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow p-6 mb-6">
    <form action="{{ route('admin.facilities.index') }}" method="GET" class="flex gap-2">
        <div class="flex-1 relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari fasilitas..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
        <button type="submit" class="btn-primary px-4">
            <i class="fas fa-search mr-2"></i>Cari
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.facilities.index') }}" class="btn-secondary px-4">
            <i class="fas fa-times mr-2"></i>Reset
        </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="font-bold text-lg flex items-center">
            <i class="fas fa-concierge-bell text-primary mr-2"></i>
            Daftar Fasilitas
        </h3>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                Total: <span class="font-semibold">{{ $facilities->total() }}</span> fasilitas
            </div>
            <a href="{{ route('admin.facilities.create') }}" class="btn-primary flex items-center px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>
                Tambah Fasilitas
            </a>
        </div>
        
        
    </div>
    
    @if($facilities->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($facilities as $facility)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg flex items-center justify-center {{ $facility->icon_color_class }}">
                                    <i class="{{ $facility->icon_class }}"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $facility->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($facility->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $facility->type_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $facility->location ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge {{ $facility->status_badge_class }}">
                            {{ $facility->status_text }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.facilities.show', $facility->id) }}" 
                               class="text-primary hover:text-primary/80" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.facilities.edit', $facility->id) }}" 
                               class="text-warning hover:text-warning/80" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.facilities.destroy', $facility->id) }}" 
                                  method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-danger hover:text-danger/80" 
                                        title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($facilities->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $facilities->links() }}
    </div>
    @endif
    
    @else
    <div class="p-8 text-center">
        <div class="flex flex-col items-center justify-center py-8">
            <i class="fas fa-concierge-bell text-6xl text-gray-300 mb-4"></i>
            <h4 class="text-lg font-medium text-gray-700 mb-2">Belum ada fasilitas</h4>
            <div class="flex gap-3">
                <form action="{{ route('admin.facilities.seed') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary inline-flex items-center" 
                            onclick="return confirm('Tambahkan 7 fasilitas default?')">
                        <i class="fas fa-seedling mr-1"></i> Tambah Default
                    </button>
                </form>
                <a href="{{ route('admin.facilities.create') }}" class="btn-primary inline-flex items-center">
                    <i class="fas fa-plus mr-1"></i> Buat Baru
                </a>
            </div>
        </div>
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
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
</style>
@endsection