@extends('layouts.admin.app')

@section('title', 'Edit Promo - ' . $promo->promo_code)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Edit Promo</h2>
                    <p class="text-gray-600">Ubah detail promo <span class="font-semibold text-primary">{{ $promo->promo_code }}</span></p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="status-badge 
                        @if($promo->status === 'active') bg-success/10 text-success
                        @elseif($promo->status === 'upcoming') bg-info/10 text-info
                        @elseif($promo->status === 'expired') bg-warning/10 text-warning
                        @else bg-secondary/10 text-secondary @endif">
                        {{ $promo->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST">
            @csrf
            @method('PUT')
            
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
                            <input type="text" name="promo_code" value="{{ old('promo_code', $promo->promo_code) }}" 
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
                                      placeholder="Jelaskan detail promo, syarat dan ketentuan...">{{ old('description', $promo->description) }}</textarea>
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
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Diskon <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="type" value="percent" 
                                           {{ old('type', $promo->type) == 'percent' ? 'checked' : '' }}
                                           class="text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        <i class="fas fa-percentage text-green-500 mr-1"></i>
                                        Persentase (%)
                                    </span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="type" value="fixed"
                                           {{ old('type', $promo->type) == 'fixed' ? 'checked' : '' }}
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
                        </div> --}}
                        
                        <!-- Nilai Diskon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Diskon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="discount" value="{{ old('discount', $promo->discount) }}" 
                                       min="0" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent pr-12"
                                       placeholder="0.00"
                                       required
                                       id="discountInput">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm" id="discountSuffix">
                                        {{ $promo->type == 'percent' ? '%' : 'Rp' }}
                                    </span>
                                </div>
                            </div>
                            @error('discount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1" id="discountHelp">
                                {{ $promo->type == 'percent' ? 'Masukkan nilai diskon dalam persentase' : 'Masukkan nilai diskon dalam Rupiah' }}
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
                            <input type="date" name="start_date" value="{{ old('start_date', $promo->start_date ? $promo->start_date->format('Y-m-d') : '') }}"
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
                            <input type="date" name="end_date" value="{{ old('end_date', $promo->end_date ? $promo->end_date->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   id="endDate"
                                   min="{{ $promo->start_date ? $promo->start_date->format('Y-m-d') : date('Y-m-d') }}">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika promo berlaku selamanya</p>
                        </div>
                    </div>
                    
                    <!-- Info Periode Saat Ini -->
                    @if($promo->start_date || $promo->end_date)
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span>
                                @if($promo->start_date && $promo->end_date)
                                    Periode saat ini: 
                                    <strong>{{ $promo->start_date->format('d M Y') }} - {{ $promo->end_date->format('d M Y') }}</strong>
                                    ({{ $promo->start_date->diffInDays($promo->end_date) }} hari)
                                @elseif($promo->start_date && !$promo->end_date)
                                    Mulai berlaku: <strong>{{ $promo->start_date->format('d M Y') }}</strong> (tanpa batas waktu)
                                @else
                                    Berlaku: <strong>Segera - Selamanya</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif
                    
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
                            <input type="number" name="usage_limit" value="{{ old('usage_limit', $promo->usage_limit) }}" 
                                   min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Kosongkan untuk unlimited">
                            @error('usage_limit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Jumlah maksimal penggunaan promo</p>
                        </div>
                        
                        <!-- Info Penggunaan Saat Ini -->
                        <div class="flex items-end">
                            <div class="text-sm text-gray-600">
                                <p class="font-medium">Info Penggunaan:</p>
                                <div class="mt-1">
                                    @php
                                        $usedCount = $promo->reservations()->count();
                                        $remaining = $promo->usage_limit ? $promo->usage_limit - $usedCount : 'Unlimited';
                                    @endphp
                                    <div class="flex items-center space-x-2 text-xs">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Digunakan: {{ $usedCount }}x</span>
                                        @if($promo->usage_limit)
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Sisa: {{ $remaining }}x</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">Tidak terbatas</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contoh Batas -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contoh Batas Penggunaan:
                        </label>
                        <div class="grid grid-cols-3 gap-2 max-w-md">
                            <button type="button" onclick="setUsageLimit(50)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded transition-colors">
                                50x
                            </button>
                            <button type="button" onclick="setUsageLimit(100)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded transition-colors">
                                100x
                            </button>
                            <button type="button" onclick="setUsageLimit(500)" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded transition-colors">
                                500x
                            </button>
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
                                    <span class="text-white font-bold text-sm" id="previewCode">{{ substr($promo->promo_code, 0, 3) }}</span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-lg" id="previewDiscount">
                                        @if($promo->type == 'percent')
                                            {{ $promo->discount }}%
                                        @else
                                            Rp {{ number_format($promo->discount, 0, ',', '.') }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600" id="previewType">
                                        {{ $promo->type == 'percent' ? 'Diskon Persentase' : 'Diskon Nominal' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500" id="previewPeriod">
                                    @if($promo->start_date && $promo->end_date)
                                        {{ $promo->start_date->format('d M') }} - {{ $promo->end_date->format('d M Y') }}
                                    @elseif($promo->start_date && !$promo->end_date)
                                        Mulai {{ $promo->start_date->format('d M Y') }}
                                    @else
                                        Segera - Selamanya
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400" id="previewUsage">
                                    @if($promo->usage_limit)
                                        Maksimal {{ $promo->usage_limit }} penggunaan
                                    @else
                                        Tidak terbatas
                                    @endif
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600" id="previewDescription">{{ $promo->description ?: 'Deskripsi promo akan muncul di sini' }}</p>
                    </div>
                </div> -->

                <!-- Informasi Sistem -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-md font-semibold mb-2 flex items-center text-gray-700">
                        <i class="fas fa-database text-gray-500 mr-2"></i>
                        Informasi Sistem
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Dibuat:</span> 
                            {{ $promo->created_at->format('d M Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Diubah:</span> 
                            {{ $promo->updated_at->format('d M Y H:i') }}
                        </div>
                        <div class="md:col-span-2">
                            <span class="font-medium">Status Saat Ini:</span> 
                            <span class="status-badge 
                                @if($promo->status === 'active') bg-success/10 text-success
                                @elseif($promo->status === 'upcoming') bg-info/10 text-info
                                @elseif($promo->status === 'expired') bg-warning/10 text-warning
                                @else bg-secondary/10 text-secondary @endif">
                                {{ $promo->status_label }}
                            </span>
                            @if($promo->status === 'active' && $promo->end_date)
                                - Berakhir dalam {{ $promo->end_date->diffForHumans() }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-primary text-white py-3 px-8 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center shadow-lg shadow-primary/25">
                        <i class="fas fa-save mr-2"></i> Update Promo
                    </button>
                    <a href="{{ route('admin.promos.index') }}" 
                       class="bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <!-- <button type="button" onclick="resetToOriginal()"
                            class="bg-orange-100 text-orange-700 py-3 px-6 rounded-lg font-medium hover:bg-orange-200 transition-colors flex items-center">
                        <i class="fas fa-history mr-2"></i> Reset
                    </button>
                    <a href="{{ route('admin.promos.create') }}" 
                       class="bg-green-100 text-green-700 py-3 px-6 rounded-lg font-medium hover:bg-green-200 transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i> Buat Baru
                    </a> -->
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
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
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

        // Store original values for reset
        const originalValues = {
            promo_code: '{{ $promo->promo_code }}',
            description: `{{ $promo->description }}`,
            type: '{{ $promo->type }}',
            discount: '{{ $promo->discount }}',
            start_date: '{{ $promo->start_date ? $promo->start_date->format("Y-m-d") : "" }}',
            end_date: '{{ $promo->end_date ? $promo->end_date->format("Y-m-d") : "" }}',
            usage_limit: '{{ $promo->usage_limit }}'
        };

        // Update preview when inputs change
        function updatePreview() {
            // Kode promo
            const code = promoCodeInput.value || originalValues.promo_code;
            previewCode.textContent = code.substring(0, 3).toUpperCase();
            
            // Diskon
            const discountValue = discountInput.value || originalValues.discount;
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
            } else if (!startDate && endDate) {
                previewPeriod.textContent = `Sampai ${formatDate(new Date(endDate))}`;
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
            previewDescription.textContent = descriptionInput.value || originalValues.description || 'Deskripsi promo akan muncul di sini';
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
            const startDate = startDateInput.value ? new Date(startDateInput.value) : new Date();
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + days);
            
            startDateInput.value = startDate.toISOString().split('T')[0];
            endDateInput.value = endDate.toISOString().split('T')[0];
            updatePreview();
        }

        // Set no end date
        window.setNoEndDate = function() {
            endDateInput.value = '';
            updatePreview();
        }

        // Set usage limit
        window.setUsageLimit = function(limit) {
            usageLimitInput.value = limit;
            updatePreview();
        }

        // Reset to original values
        window.resetToOriginal = function() {
            if (confirm('Yakin ingin mengembalikan ke nilai semula?')) {
                promoCodeInput.value = originalValues.promo_code;
                descriptionInput.value = originalValues.description;
                
                // Reset radio buttons
                document.querySelectorAll('input[name="type"]').forEach(radio => {
                    radio.checked = radio.value === originalValues.type;
                });
                
                discountInput.value = originalValues.discount;
                startDateInput.value = originalValues.start_date;
                endDateInput.value = originalValues.end_date;
                usageLimitInput.value = originalValues.usage_limit;
                
                // Update suffix based on type
                if (originalValues.type === 'percent') {
                    discountSuffix.textContent = '%';
                    discountHelp.textContent = 'Masukkan nilai diskon dalam persentase (0-100)';
                } else {
                    discountSuffix.textContent = 'Rp';
                    discountHelp.textContent = 'Masukkan nilai diskon dalam Rupiah';
                }
                
                updatePreview();
            }
        }

        // Set minimum end date based on start date
        startDateInput.addEventListener('change', function() {
            if (this.value) {
                endDateInput.min = this.value;
            }
        });

        // Initialize preview
        updatePreview();
    });
</script>
@endsection