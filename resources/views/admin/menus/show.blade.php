@extends('layouts.admin.app')

@section('title', 'Detail Menu')
@section('subtitle', 'Detail informasi menu')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Menu</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap tentang menu {{ $menu->name }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.menus.index') }}" 
                   class="btn-secondary inline-flex items-center px-4 py-2">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column - Menu Information -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informasi Dasar Menu
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                            <p class="text-lg font-semibold text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $menu->name }}</p>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                            <p class="text-2xl font-bold text-blue-600 bg-blue-50 p-3 rounded-lg">{{ $menu->formatted_price }}</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <span class="category-badge {{ $menu->category_badge_class }} text-sm">
                                    {{ $menu->formatted_category }}
                                </span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <span class="status-badge {{ $menu->status_badge_class }} text-sm">
                                    <i class="fas fa-circle mr-1" style="font-size: 0.5rem;"></i>
                                    {{ $menu->status_text }}
                                </span>
                            </div>
                        </div>

                        <!-- Stock Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Stok</label>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <span class="inline-flex items-center text-sm font-medium {{ $menu->is_available ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas {{ $menu->is_available ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                    {{ $menu->stock_status }}
                                </span>
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dibuat Pada</label>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    {{ $menu->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Updated Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diupdate Pada</label>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar-edit mr-2"></i>
                                    {{ $menu->updated_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-align-left text-purple-500 mr-2"></i>
                        Deskripsi Menu
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $menu->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Image Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-image text-green-500 mr-2"></i>
                        Gambar Menu
                    </h2>
                </div>
                <div class="p-6">
                    @if($menu->image_url)
                        <div class="relative group">
                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" 
                                 class="w-full h-64 object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-300"></div>
                        </div>
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex flex-col items-center justify-center">
                            <i class="fas fa-camera text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500 font-medium">Tidak ada gambar</p>
                            <p class="text-gray-400 text-sm mt-1">Gambar belum diupload</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Aksi Cepat
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Edit Button -->
                    <a href="{{ route('admin.menus.edit', $menu->id) }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all duration-200 group">
                        <i class="fas fa-edit mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Edit Informasi Menu</span>
                    </a>

                    <!-- Toggle Status Button -->
                    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $menu->name }}">
                        <input type="hidden" name="description" value="{{ $menu->description }}">
                        <input type="hidden" name="price" value="{{ $menu->price }}">
                        <input type="hidden" name="category" value="{{ $menu->category }}">
                        <input type="hidden" name="is_available" value="{{ $menu->is_available ? 0 : 1 }}">
                        
                        @if($menu->is_available)
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-orange-50 border border-orange-200 text-orange-700 rounded-lg hover:bg-orange-100 hover:border-orange-300 transition-all duration-200 group">
                                <i class="fas fa-pause mr-3 group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Nonaktifkan Menu</span>
                            </button>
                        @else
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg hover:bg-green-100 hover:border-green-300 transition-all duration-200 group">
                                <i class="fas fa-play mr-3 group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Aktifkan Menu</span>
                            </button>
                        @endif
                    </form>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini? Tindakan ini tidak dapat dibatalkan.')" 
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200 group">
                            <i class="fas fa-trash mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Hapus Menu</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    border: none;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    border: none;
    box-shadow: 0 2px 4px rgba(107, 114, 128, 0.2);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(107, 114, 128, 0.3);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    border: none;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
}

.category-badge, .status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.text-primary {
    color: #3b82f6;
}

/* Smooth transitions for all interactive elements */
a, button {
    transition: all 0.3s ease;
}

/* Card hover effects */
.bg-white {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.bg-white:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}
</style>
@endsection