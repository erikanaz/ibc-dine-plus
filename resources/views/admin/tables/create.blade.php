@extends('layouts.admin.app')

@section('title', 'Tambah Meja Baru')
@section('subtitle', 'Tambah meja baru ke dalam sistem')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-800">Tambah Meja Baru</h2>
            <!-- <a href="{{ route('admin.tables.index') }}" class="btn-secondary flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a> -->
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow">
        <form action="{{ route('admin.tables.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-8">
                
                <!-- Informasi Dasar -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nomor Meja -->
                        <div>
                            <label for="number" class="form-label">Nomor Meja : </label>
                            <input 
                                type="text" 
                                name="number" 
                                id="number"
                                value="{{ old('number') }}"
                                class="form-input @error('number') border-red-500 @enderror"
                                placeholder="Contoh: 01, 02, A1, B2"
                                required
                            >
                            @error('number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Nomor unik untuk identifikasi meja</p>
                        </div>

                        <!-- Kapasitas -->
                        <div>
                            <label for="capacity" class="form-label">Kapasitas :</label>
                            <select 
                                name="capacity" 
                                id="capacity"
                                class="form-input @error('capacity') border-red-500 @enderror"
                                required
                            >
                                <option value="">Pilih Kapasitas</option>
                                <option value="4" {{ old('capacity') == '4' ? 'selected' : '' }}>4 Orang</option>
                                <option value="5" {{ old('capacity') == '5' ? 'selected' : '' }}>5 Orang</option>
                                <option value="6" {{ old('capacity') == '6' ? 'selected' : '' }}>6 Orang</option>
                            </select>
                            @error('capacity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Lokasi -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                        Informasi Lokasi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lokasi -->
                        <div>
                            <label for="location" class="form-label">Lokasi :</label>
                            <select 
                                name="location" 
                                id="location"
                                class="form-input @error('location') border-red-500 @enderror"
                                required
                            >
                                <option value="">Pilih Lokasi</option>
                                @foreach($locations as $value => $label)
                                    <option value="{{ $value }}" {{ old('location') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label">Status :</label>
                            <select 
                                name="status" 
                                id="status"
                                class="form-input @error('status') border-red-500 @enderror"
                                required
                            >
                                <option value="">Pilih Status</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t rounded-b-xl flex justify-between items-center">
                <a href="{{ route('admin.tables.index') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Simpan Meja
                </button>
            </div>
        </form>
    </div>

    <!-- Tips -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
            <i class="fas fa-lightbulb mr-2"></i> Tips
        </h4>
        <ul class="text-blue-700 text-sm space-y-1">
            <li>• Pastikan nomor meja unik dan tidak duplikat</li>
            <li>• Pilih kapasitas sesuai dengan ukuran meja sebenarnya</li>
            <li>• Tentukan lokasi untuk memudahkan penempatan</li>
            <li>• Set status sesuai kondisi meja saat ini</li>
        </ul>
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-primary {
        background-color: #3b82f6;
        color: white;
        padding: 0.75rem 1.5rem;
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
        padding: 0.75rem 1.5rem;
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
    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background-color: white;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endsection
