@extends('layouts.admin.app')

@section('title', 'Tambah Fasilitas')
@section('subtitle', 'Tambah fasilitas baru untuk restoran')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Tambah Fasilitas Baru</h2>

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
    <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Fasilitas -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Fasilitas *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" 
                       placeholder="Contoh: Wi-Fi Gratis, Area Parkir, dll." required>
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi *</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary"
                          placeholder="Deskripsi detail fasilitas..." required>{{ old('description') }}</textarea>
            </div>

            <!-- Jenis Fasilitas (Icon) -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700">Jenis Fasilitas *</label>
                <select name="icon" id="icon"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="wifi" {{ old('icon') == 'wifi' ? 'selected' : '' }}>Wi-Fi</option>
                    <option value="socket" {{ old('icon') == 'socket' ? 'selected' : '' }}>Stop Kontak</option>
                    <option value="baby-chair" {{ old('icon') == 'baby-chair' ? 'selected' : '' }}>Kursi Bayi</option>
                    <option value="ac" {{ old('icon') == 'ac' ? 'selected' : '' }}>AC</option>
                    <option value="toilet" {{ old('icon') == 'toilet' ? 'selected' : '' }}>Toilet</option>
                    <option value="smoking-area" {{ old('icon') == 'smoking-area' ? 'selected' : '' }}>Area Merokok</option>
                    <option value="parking" {{ old('icon') == 'parking' ? 'selected' : '' }}>Parkir</option>
                </select>
                
                <!-- Icon Preview -->
                <div id="iconPreview" class="mt-3 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview Icon:</p>
                    <div id="selectedIcon" class="inline-flex items-center justify-center w-12 h-12 rounded-lg">
                        <i id="iconPreviewIcon" class="text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}"
                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary" 
                       placeholder="Contoh: Depan restoran, Area utama, dll.">
            </div>

            <!-- Status Ketersediaan -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Ketersediaan *</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_available" value="1" 
                               class="text-primary focus:ring-primary" 
                               {{ old('is_available', '1') == '1' ? 'checked' : '' }} required>
                        <span class="ml-2">Tersedia</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_available" value="0"
                               class="text-primary focus:ring-primary" 
                               {{ old('is_available') == '0' ? 'checked' : '' }}>
                        <span class="ml-2">Tidak Tersedia</span>
                    </label>
                </div>
            </div>

            <!-- Gambar Fasilitas -->
            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Fasilitas</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary"
                       onchange="previewImage(this)">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF, WEBP, AVIF. Max: 2MB</p>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview:</p>
                    <img id="preview" class="h-32 w-32 object-cover rounded-lg border">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-6 border-t">
            <a href="{{ route('admin.facilities.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition duration-200">
                Simpan Fasilitas
            </button>
        </div>
    </form>
</div>

<script>
// Preview image
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

// Preview icon based on selection
document.getElementById('icon').addEventListener('change', function() {
    const iconPreview = document.getElementById('iconPreview');
    const iconPreviewIcon = document.getElementById('iconPreviewIcon');
    const selectedIconDiv = document.getElementById('selectedIcon');
    
    // Icon classes mapping
    const iconClasses = {
        'wifi': 'fas fa-wifi',
        'socket': 'fas fa-plug',
        'baby-chair': 'fas fa-baby',
        'ac': 'fas fa-snowflake',
        'toilet': 'fas fa-restroom',
        'smoking-area': 'fas fa-smoking',
        'parking': 'fas fa-parking'
    };
    
    // Color classes mapping
    const colorClasses = {
        'wifi': 'bg-blue-100 text-blue-600',
        'socket': 'bg-yellow-100 text-yellow-600',
        'baby-chair': 'bg-pink-100 text-pink-600',
        'ac': 'bg-cyan-100 text-cyan-600',
        'toilet': 'bg-purple-100 text-purple-600',
        'smoking-area': 'bg-orange-100 text-orange-600',
        'parking': 'bg-gray-100 text-gray-600'
    };
    
    const selectedValue = this.value;
    
    if (selectedValue && iconClasses[selectedValue]) {
        // Update icon
        iconPreviewIcon.className = iconClasses[selectedValue];
        
        // Update color
        selectedIconDiv.className = `inline-flex items-center justify-center w-12 h-12 rounded-lg ${colorClasses[selectedValue]}`;
        
        // Show preview
        iconPreview.classList.remove('hidden');
    } else {
        iconPreview.classList.add('hidden');
    }
});

// Initialize icon preview if there's already a selected value
document.addEventListener('DOMContentLoaded', function() {
    const iconSelect = document.getElementById('icon');
    if (iconSelect.value) {
        iconSelect.dispatchEvent(new Event('change'));
    }
});
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
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }
</style>
@endsection