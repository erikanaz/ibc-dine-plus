@extends('layouts.customer.app')

@section('title', 'Pembayaran DP - IBC Batu Tulis')

@section('content')
<div class="container mx-auto px-4 max-w-4xl mt-8 mb-12">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <h1 class="text-xl font-bold text-white">Pembayaran Down Payment</h1>
                </div>
                <span class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm font-medium">
                    Reservasi #{{ $reservation->id }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <!-- Countdown Deadline -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-orange-800">Batas Waktu Pembayaran</p>
                            <p class="text-sm text-orange-600">
                                @if($reservation->payment_deadline)
                                    {{ $reservation->payment_deadline->translatedFormat('l, d F Y H:i') }}
                                @else
                                    Tidak ada batas waktu
                                @endif
                            </p>
                        </div>
                    </div>
                    <div id="countdown-timer" class="text-lg font-bold text-orange-600">
                        <!-- Countdown akan diisi oleh JavaScript -->
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Informasi Pembayaran -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h2>
                    
                    <div class="space-y-4">
                        <!-- Informasi Reservasi -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Detail Reservasi</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal</span>
                                    <span class="font-medium">
                                        @if($reservation->reservation_date)
                                            {{ $reservation->reservation_date->translatedFormat('l, d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Waktu</span>
                                    <span class="font-medium">{{ $reservation->reservation_time ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Meja</span>
                                    <span class="font-medium text-right">
                                        @if($reservation->table_numbers)
                                            {{ $reservation->table_numbers }}
                                        @elseif($reservation->tables && $reservation->tables->count() > 0)
                                            {{ $reservation->tables->pluck('number')->implode(', ') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jumlah Tamu</span>
                                    <span class="font-medium">{{ $reservation->guest_count ?? 0 }} Orang</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jumlah Meja</span>
                                    <span class="font-medium">
                                        @if($reservation->total_tables)
                                            {{ $reservation->total_tables }} meja
                                        @elseif($reservation->tables)
                                            {{ $reservation->tables->count() }} meja
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                {{-- <div class="flex justify-between">
                                    <span class="text-gray-600">Total Kapasitas</span>
                                    <span class="font-medium">
                                        @if($reservation->total_capacity)
                                            {{ $reservation->total_capacity }} orang
                                        @elseif($reservation->tables)
                                            {{ $reservation->tables->sum('capacity') }} orang
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div> --}}
                            </div>
                        </div>

                        <!-- Informasi Bank -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <h3 class="text-sm font-medium text-blue-700 mb-3">Transfer Bank</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-blue-600">Bank</span>
                                    <span class="font-semibold text-blue-800">BCA</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-blue-600">No. Rekening</span>
                                    <span class="font-semibold text-blue-800">123 456 7890</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-blue-600">Atas Nama</span>
                                    <span class="font-semibold text-blue-800">IBC BATU TULIS</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-blue-600">Jumlah Transfer</span>
                                    <span class="font-bold text-blue-800 text-lg">Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Penting -->
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <h3 class="text-sm font-medium text-yellow-700 mb-2">Catatan Penting</h3>
                            <ul class="text-sm text-yellow-600 space-y-1">
                                <li>• Transfer tepat sesuai jumlah di atas</li>
                                <li>• Simpan bukti transfer yang valid</li>
                                <li>• Upload bukti transfer sebelum batas waktu</li>
                                <li>• Reservasi akan otomatis dibatalkan jika melewati batas waktu</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form Upload Bukti Transfer -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Upload Bukti Transfer</h2>
                    
                    <form action="{{ route('reservation.upload-payment', $reservation) }}" method="POST" enctype="multipart/form-data" id="payment-form">
                        @csrf
                        
                        <div class="space-y-4">
                            <!-- Upload Area -->
                            <div x-data="{ file: null, previewUrl: null }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bukti Transfer *
                                </label>
                                
                                <!-- Preview Image -->
                                <div x-show="previewUrl" class="mb-4">
                                    <img :src="previewUrl" alt="Preview bukti transfer" class="w-full max-w-xs h-auto rounded-lg border border-gray-300">
                                    <div class="mt-2 flex items-center gap-3">
                                        <button type="button" @click="$refs.fileInput.value = null; $refs.fileInput.click()" class="text-sm text-yellow-600 hover:text-yellow-800 bg-yellow-50 px-3 py-1 rounded-md">
                                            Ganti
                                        </button>
                                        <button type="button" @click="file = null; previewUrl = null; $refs.fileInput.value = null" class="text-sm text-red-600 hover:text-red-800 bg-red-50 px-3 py-1 rounded-md">
                                            Hapus
                                        </button>
                                    </div>
                                </div>

                                <!-- Upload Box -->
                                <div x-show="!previewUrl" 
                                     class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-500 transition cursor-pointer"
                                     @click="$refs.fileInput.value = null; $refs.fileInput.click()">
                                    <input type="file" 
                                           name="bukti_transfer" 
                                           id="bukti_transfer"
                                           x-ref="fileInput"
                                           class="hidden"
                                           accept="image/*"
                                           @change="file = $event.target.files[0]; previewUrl = file ? URL.createObjectURL(file) : null">
                                    
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    
                                    <p class="text-sm text-gray-600 mb-1">
                                        <span class="text-yellow-600 font-medium">Klik untuk upload</span> atau drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, JPEG (Maks. 2MB)
                                    </p>
                                </div>

                                <!-- Error Message -->
                                @error('bukti_transfer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Informasi Tambahan -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Tambahan (Opsional)
                                </label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                                          placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex justify-center space-x-3 pt-4">
                                <a href="{{ route('reservation.history') }}" 
                                   class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition flex items-center justify-center">
                                    Kembali
                                </a>
                                <button type="submit" 
                                        id="submit-btn"
                                        class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                                    <span id="submit-text">Konfirmasi Pembayaran</span>
                                    <span id="loading-text" class="hidden">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses...
                                    </span>
                                </button>
                            </div>

                            <!-- Warning Expired -->
                            <div id="expired-warning" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-red-600 font-medium">Batas waktu pembayaran telah habis. Reservasi akan otomatis dibatalkan.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown-timer');
    const expiredWarning = document.getElementById('expired-warning');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const loadingText = document.getElementById('loading-text');
    const paymentForm = document.getElementById('payment-form');
    
    console.log('🟢 Payment Page Loaded');
    
    // Cek jika payment deadline ada
    @if($reservation->payment_deadline)
        const serverDeadline = new Date('{{ $reservation->payment_deadline->toISOString() }}');
        console.log('Parsed deadline:', serverDeadline);
        
        // Countdown function
        function updateCountdown() {
            const now = new Date();
            const distance = serverDeadline - now;
            
            console.log('Countdown - Now:', now);
            console.log('Countdown - Distance:', distance);
            
            if (distance < 0) {
                // Waktu habis
                countdownElement.innerHTML = "WAKTU HABIS";
                countdownElement.className = "text-lg font-bold text-red-600 animate-pulse";
                if (expiredWarning) {
                    expiredWarning.classList.remove('hidden');
                }
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span>Waktu Habis</span>';
                }
                return;
            }
            
            // Hitung waktu tersisa
            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Format tampilan
            let countdownText = '';
            if (hours > 0) {
                countdownText = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            } else {
                countdownText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            
            // Update warna berdasarkan waktu tersisa
            if (hours === 0 && minutes < 30) {
                countdownElement.className = "text-lg font-bold text-red-600 animate-pulse";
            } else if (hours === 0) {
                countdownElement.className = "text-lg font-bold text-orange-500";
            } else {
                countdownElement.className = "text-lg font-bold text-orange-600";
            }
            
            countdownElement.innerHTML = countdownText;
            
            // Update button status
            if (submitBtn && distance < 0) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');
                submitBtn.classList.add('bg-gray-400');
            }
        }
        
        // Update countdown setiap detik
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
        
        // Form submission handler untuk validasi waktu
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                const now = new Date();
                const distance = serverDeadline - now;
                
                // Validasi waktu (jika waktu sudah habis)
                if (distance < 0) {
                    e.preventDefault();
                    alert('Batas waktu pembayaran telah habis. Reservasi akan otomatis dibatalkan.');
                    return;
                }
                
                // Validasi file
                const fileInput = document.getElementById('bukti_transfer');
                if (!fileInput || !fileInput.files.length) {
                    e.preventDefault();
                    alert('Silakan pilih bukti transfer terlebih dahulu.');
                    return;
                }
                
                // Validasi ukuran file (max 2MB)
                const file = fileInput.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    e.preventDefault();
                    alert('Ukuran file maksimal 2MB.');
                    return;
                }
                
                // Validasi tipe file
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    e.preventDefault();
                    alert('Hanya file JPG, JPEG, dan PNG yang diizinkan.');
                    return;
                }
                
                // Show loading state
                if (submitText && loadingText && submitBtn) {
                    submitText.classList.add('hidden');
                    loadingText.classList.remove('hidden');
                    submitBtn.disabled = true;
                }
            });
        }
        
        // Cleanup interval ketika halaman ditutup
        window.addEventListener('beforeunload', function() {
            clearInterval(countdownInterval);
        });
    @else
        // Jika tidak ada payment deadline
        countdownElement.innerHTML = "Tidak ada batas waktu";
        countdownElement.className = "text-lg font-bold text-gray-600";
    @endif
    
    // Drag and drop functionality
    const dropArea = document.querySelector('[x-show="!previewUrl"]');
    
    if (dropArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropArea.classList.add('border-yellow-500', 'bg-yellow-50');
        }
        
        function unhighlight() {
            dropArea.classList.remove('border-yellow-500', 'bg-yellow-50');
        }
        
        dropArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const fileInput = document.getElementById('bukti_transfer');
            
            if (files.length && fileInput) {
                fileInput.files = files;
                
                // Trigger change event untuk Alpine.js
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }
    }
});
</script>

<style>
.border-dashed:hover {
    border-color: #f59e0b;
    background-color: #fffbeb;
}

#countdown-timer {
    transition: all 0.3s ease;
}

.animate-pulse {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>
@endpush