@extends('layouts.admin.app')

@section('title', 'Tambah Promo Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Tambah Promo Baru</h2>
        </div>

        <form action="{{ route('admin.promos.store') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-xl shadow p-6">
                <!-- Informasi Dasar Promo -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-tag text-primary mr-2"></i>
                        Informasi Dasar Promo
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Promo -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Promo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="promo_code" value="{{ old('promo_code') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent uppercase font-mono"
                                   placeholder="CONTOH: WELCOME10"
                                   required
                                   maxlength="50">
                            @error('promo_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Gunakan huruf kapital dan angka. Kode harus unik.</p>
                        </div>
                        
                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Promo
                            </label>
                            <textarea name="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Jelaskan detail promo, syarat dan ketentuan...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Deskripsi akan ditampilkan kepada pelanggan.</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Diskon -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-percentage text-green-500 mr-2"></i>
                        Detail Diskon
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nilai Diskon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Diskon (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="discount" value="{{ old('discount') }}" 
                                       min="0" max="100" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent pr-12"
                                       placeholder="0"
                                       required
                                       id="discountInput">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">%</span>
                                </div>
                            </div>
                            @error('discount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Masukkan nilai diskon dalam persentase (0-100%)</p>
                        </div>

                        <!-- Kolom kosong untuk layout -->
                        <div></div>
                    </div>
                </div>

                <!-- Periode Berlaku -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Periode Berlaku
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   id="startDate">
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika promo langsung berlaku</p>
                        </div>
                        
                        <!-- Tanggal Berakhir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Berakhir
                            </label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   id="endDate"
                                   min="{{ date('Y-m-d') }}">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika promo berlaku selamanya</p>
                        </div>
                    </div>
                </div>

                <!-- Batas Penggunaan -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-users text-purple-500 mr-2"></i>
                        Batas Penggunaan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Batas Penggunaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Batas Penggunaan
                            </label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" 
                                   min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Kosongkan untuk unlimited">
                            @error('usage_limit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Jumlah maksimal penggunaan promo</p>
                        </div>
                        
                        <!-- Contoh Batas -->
                        <div class="flex items-end">
                            <div class="text-sm text-gray-600">
                                <p class="font-medium">Contoh batas penggunaan:</p>
                                <div class="grid grid-cols-3 gap-1 mt-1">
                                    <button type="button" onclick="setUsageLimit(50)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-1 px-2 rounded">
                                        50x
                                    </button>
                                    <button type="button" onclick="setUsageLimit(100)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-1 px-2 rounded">
                                        100x
                                    </button>
                                    <button type="button" onclick="setUsageLimit(500)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-1 px-2 rounded">
                                        500x
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-primary text-white py-3 px-8 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center shadow-lg shadow-primary/25">
                        <i class="fas fa-save mr-2"></i> Simpan Promo
                    </button>
                    <a href="{{ route('admin.promos.index') }}" 
                       class="bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountInput = document.getElementById('discountInput');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const usageLimitInput = document.querySelector('input[name="usage_limit"]');

        // Set usage limit
        window.setUsageLimit = function(limit) {
            usageLimitInput.value = limit;
        }

        // Set minimum end date based on start date
        startDateInput.addEventListener('change', function() {
            if (this.value) {
                endDateInput.min = this.value;
            }
        });

        // Set quick period
        window.setQuickPeriod = function(days) {
            const startDate = new Date();
            const endDate = new Date();
            endDate.setDate(startDate.getDate() + days);
            
            startDateInput.value = startDate.toISOString().split('T')[0];
            endDateInput.value = endDate.toISOString().split('T')[0];
        }

        // Set no end date
        window.setNoEndDate = function() {
            const startDate = new Date();
            startDateInput.value = startDate.toISOString().split('T')[0];
            endDateInput.value = '';
        }
    });
</script>
@endsection