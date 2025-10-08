@extends('layouts.admin.app')

@section('title', 'Tambah Promo Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Tambah Promo Baru</h2>
            <!-- <p class="text-gray-600">Buat promo atau diskon baru untuk pelanggan</p> -->
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
                        <!-- Tipe Diskon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Diskon <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="type" value="percent" 
                                           {{ old('type', 'percent') == 'percent' ? 'checked' : '' }}
                                           class="text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        <i class="fas fa-percentage text-green-500 mr-1"></i>
                                        Persentase (%)
                                    </span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="type" value="fixed"
                                           {{ old('type') == 'fixed' ? 'checked' : '' }}
                                           class="text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        <i class="fas fa-money-bill-wave text-blue-500 mr-1"></i>
                                        Nominal (Rp)
                                    </span>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nilai Diskon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Diskon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="discount" value="{{ old('discount') }}" 
                                       min="0" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent pr-12"
                                       placeholder="0.00"
                                       required
                                       id="discountInput">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm" id="discountSuffix">%</span>
                                </div>
                            </div>
                            @error('discount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1" id="discountHelp">
                                Masukkan nilai diskon dalam persentase
                            </p>
                        </div>
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
                    
                    <!-- Periode Cepat -->
                    <!-- <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Periode Cepat
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button type="button" onclick="setQuickPeriod(7)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded transition-colors">
                                1 Minggu
                            </button>
                            <button type="button" onclick="setQuickPeriod(30)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded transition-colors">
                                1 Bulan
                            </button>
                            <button type="button" onclick="setQuickPeriod(90)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded transition-colors">
                                3 Bulan
                            </button>
                            <button type="button" onclick="setNoEndDate()" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded transition-colors">
                                Selamanya
                            </button>
                        </div>
                    </div> -->
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

                <!-- Preview Promo -->
                <!-- <div class="mb-8 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-bold mb-3 flex items-center text-gray-800">
                        <i class="fas fa-eye text-indigo-500 mr-2"></i>
                        Preview Promo
                    </h3>
                    
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm" id="previewCode">CODE</span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-lg" id="previewDiscount">0%</div>
                                    <div class="text-sm text-gray-600" id="previewType">Diskon Persentase</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500" id="previewPeriod">Periode</div>
                                <div class="text-xs text-gray-400" id="previewUsage">Batas Penggunaan</div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600" id="previewDescription">Deskripsi promo akan muncul di sini</p>
                    </div>
                </div> -->

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
                    <!-- <button type="button" onclick="resetForm()"
                            class="bg-orange-100 text-orange-700 py-3 px-6 rounded-lg font-medium hover:bg-orange-200 transition-colors flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button> -->
                </div>
            </div>
        </form>
    </div>
@endsection

@section('styles')
<style>
    input[type="radio"]:checked + span {
        color: #3b82f6;
        font-weight: 600;
    }
    
    input[type="radio"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeRadios = document.querySelectorAll('input[name="type"]');
        const discountInput = document.getElementById('discountInput');
        const discountSuffix = document.getElementById('discountSuffix');
        const discountHelp = document.getElementById('discountHelp');
        
        const previewCode = document.getElementById('previewCode');
        const previewDiscount = document.getElementById('previewDiscount');
        const previewType = document.getElementById('previewType');
        const previewPeriod = document.getElementById('previewPeriod');
        const previewUsage = document.getElementById('previewUsage');
        const previewDescription = document.getElementById('previewDescription');
        
        const promoCodeInput = document.querySelector('input[name="promo_code"]');
        const descriptionInput = document.querySelector('textarea[name="description"]');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const usageLimitInput = document.querySelector('input[name="usage_limit"]');

        // Update preview when inputs change
        function updatePreview() {
            // Kode promo
            const code = promoCodeInput.value || 'CODE';
            previewCode.textContent = code.substring(0, 3).toUpperCase();
            
            // Diskon
            const discountValue = discountInput.value || '0';
            const discountType = document.querySelector('input[name="type"]:checked').value;
            
            if (discountType === 'percent') {
                previewDiscount.textContent = `${discountValue}%`;
                previewType.textContent = 'Diskon Persentase';
            } else {
                previewDiscount.textContent = `Rp ${formatCurrency(discountValue)}`;
                previewType.textContent = 'Diskon Nominal';
            }
            
            // Periode
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                previewPeriod.textContent = `${formatDate(start)} - ${formatDate(end)}`;
            } else if (startDate && !endDate) {
                previewPeriod.textContent = `Mulai ${formatDate(new Date(startDate))}`;
            } else {
                previewPeriod.textContent = 'Segera - Selamanya';
            }
            
            // Batas penggunaan
            const usageLimit = usageLimitInput.value;
            if (usageLimit) {
                previewUsage.textContent = `Maksimal ${usageLimit} penggunaan`;
            } else {
                previewUsage.textContent = 'Tidak terbatas';
            }
            
            // Deskripsi
            previewDescription.textContent = descriptionInput.value || 'Deskripsi promo akan muncul di sini';
        }

        // Format currency
        function formatCurrency(amount) {
            return parseInt(amount).toLocaleString('id-ID');
        }

        // Format date
        function formatDate(date) {
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }

        // Handle discount type change
        typeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'percent') {
                    discountSuffix.textContent = '%';
                    discountHelp.textContent = 'Masukkan nilai diskon dalam persentase (0-100)';
                    discountInput.setAttribute('max', '100');
                    discountInput.setAttribute('step', '0.01');
                } else {
                    discountSuffix.textContent = 'Rp';
                    discountHelp.textContent = 'Masukkan nilai diskon dalam Rupiah';
                    discountInput.removeAttribute('max');
                    discountInput.setAttribute('step', '1');
                }
                updatePreview();
            });
        });

        // Update preview on input changes
        [promoCodeInput, discountInput, descriptionInput, startDateInput, endDateInput, usageLimitInput]
            .forEach(input => {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            });

        // Set quick period
        window.setQuickPeriod = function(days) {
            const startDate = new Date();
            const endDate = new Date();
            endDate.setDate(startDate.getDate() + days);
            
            startDateInput.value = startDate.toISOString().split('T')[0];
            endDateInput.value = endDate.toISOString().split('T')[0];
            updatePreview();
        }

        // Set no end date
        window.setNoEndDate = function() {
            const startDate = new Date();
            startDateInput.value = startDate.toISOString().split('T')[0];
            endDateInput.value = '';
            updatePreview();
        }

        // Set usage limit
        window.setUsageLimit = function(limit) {
            usageLimitInput.value = limit;
            updatePreview();
        }

        // Reset form
        window.resetForm = function() {
            if (confirm('Yakin ingin mengosongkan semua form?')) {
                document.querySelector('form').reset();
                // Reset radio buttons to default
                document.querySelector('input[name="type"][value="percent"]').checked = true;
                discountSuffix.textContent = '%';
                discountHelp.textContent = 'Masukkan nilai diskon dalam persentase (0-100)';
                updatePreview();
            }
        }

        // Set minimum end date based on start date
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });

        // Initialize preview
        updatePreview();
    });
</script>
@endsection