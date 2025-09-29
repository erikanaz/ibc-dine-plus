@extends('layouts.admin.app')

@section('title', 'Edit Menu')
@section('subtitle', 'Edit data menu restoran')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Menu</h2>
            <p class="text-gray-600">Edit data menu {{ $menu->name }}</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="btn-secondary flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Menu -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Menu *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" 
                       placeholder="Contoh: Gurame Bakar" required>
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi *</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary"
                          placeholder="Deskripsi detail tentang menu..." required>{{ old('description', $menu->description) }}</textarea>
            </div>

            <!-- Harga -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Harga *</label>
                <input type="number" name="price" id="price" min="0" step="1000" 
                       value="{{ old('price', $menu->price) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" 
                       placeholder="Contoh: 65000" required>
            </div>

            <!-- Kategori -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Kategori *</label>
                <select name="category" id="category"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="signatures" {{ old('category', $menu->category) == 'signatures' ? 'selected' : '' }}>Signature</option>
                    <option value="vegetables" {{ old('category', $menu->category) == 'vegetables' ? 'selected' : '' }}>Sayuran</option>
                    <option value="tempoe-doeloe" {{ old('category', $menu->category) == 'tempoe-doeloe' ? 'selected' : '' }}>Tempo Doeloe</option>
                    <option value="mie-ayam h&w" {{ old('category', $menu->category) == 'mie-ayam h&w' ? 'selected' : '' }}>Mie Ayam H&W</option>
                    <option value="drinks" {{ old('category', $menu->category) == 'drinks' ? 'selected' : '' }}>Minuman</option>
                </select>
            </div>

            <!-- Status Ketersediaan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Ketersediaan *</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_available" value="1" 
                               class="text-primary focus:ring-primary" 
                               {{ old('is_available', $menu->is_available) == '1' ? 'checked' : '' }} required>
                        <span class="ml-2">Tersedia (Aktif)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_available" value="0"
                               class="text-primary focus:ring-primary" 
                               {{ old('is_available', $menu->is_available) == '0' ? 'checked' : '' }}>
                        <span class="ml-2">Habis (Tidak Aktif)</span>
                    </label>
                </div>
            </div>

            <!-- Gambar Menu Saat Ini -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                <div class="flex items-center space-x-4">
                    @if($menu->image_url)
                        <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" 
                             class="w-32 h-32 object-cover rounded-lg border">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded-lg border flex items-center justify-center">
                            <span class="text-gray-500 text-sm">Tidak ada gambar</span>
                        </div>
                    @endif
                    <div class="text-sm text-gray-600">
                        <p>Gambar saat ini akan diganti jika Anda mengupload gambar baru.</p>
                        @if($menu->image)
                            <p class="mt-1">File: {{ basename($menu->image) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Gambar Baru -->
            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Baru (Opsional)</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary"
                       onchange="previewImage(this)">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF, WEBP, AVIF. Max: 2MB</p>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview Gambar Baru:</p>
                    <img id="preview" class="w-32 h-32 object-cover rounded-lg border">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-6 border-t">
            <a href="{{ route('admin.menus.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition duration-200">
                <i class="fas fa-save mr-2"></i>Update Menu
            </button>
        </div>
    </form>

    <!-- Hapus Menu -->
    <!-- <div class="mt-8 pt-6 border-t">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-red-800 mb-2">Hapus Menu</h3>
            <p class="text-red-600 text-sm mb-3">Tindakan ini tidak dapat dibatalkan. Menu akan dihapus permanen dari sistem.</p>
            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                    <i class="fas fa-trash mr-2"></i>Hapus Menu
                </button>
            </form>
        </div>
    </div> -->
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            imagePreview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        imagePreview.classList.add('hidden');
    }
}
</script>

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
</style>
@endsection