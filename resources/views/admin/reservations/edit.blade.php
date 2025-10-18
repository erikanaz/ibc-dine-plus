@extends('layouts.admin.app')

@section('title', 'Edit Reservasi - #' . $reservation->id)

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Edit Reservasi</h2>
            <p class="text-gray-600">Edit informasi reservasi dan pesanan</p>
        </div>

        <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST" id="reservationForm">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-xl shadow p-6">
                <!-- Informasi Customer -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-user text-primary mr-2"></i>
                        Informasi Customer
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Customer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Customer <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $reservation->customer_name) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Masukkan nama customer" required>
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon Customer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon Customer <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', $reservation->customer_phone) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Contoh: 081234567890" required>
                            @error('customer_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Customer -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Customer <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $reservation->customer_email) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="email@example.com" required>
                            @error('customer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Reservasi -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-calendar text-blue-500 mr-2"></i>
                        Informasi Reservasi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Meja -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Meja <span class="text-red-500">*</span>
                            </label>
                            <select name="table_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required>
                                <option value="">Pilih Meja</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" 
                                        {{ old('table_id', $reservation->table_id) == $table->id ? 'selected' : '' }}
                                        {{ $table->status === 'occupied' && $table->id != $reservation->table_id ? 'disabled' : '' }}>
                                        Meja {{ $table->number }} ({{ $table->capacity }} orang) - {{ $table->location_label }}
                                        @if($table->status === 'occupied')
                                            - ⚠️ Sedang Dipakai
                                        @elseif($table->status === 'reserved' && $table->id != $reservation->table_id)
                                            - 🔒 Sudah Dipesan
                                        @elseif($table->id == $reservation->table_id)
                                            - ✅ Meja Saat Ini
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('table_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Tamu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Tamu <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="guest_count" value="{{ old('guest_count', $reservation->guest_count) }}" 
                                   min="1" max="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            @error('guest_count')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Reservasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Reservasi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="reservation_date" value="{{ old('reservation_date', $reservation->reservation_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            @error('reservation_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu Reservasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Waktu Reservasi <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="reservation_time" value="{{ old('reservation_time', $reservation->reservation_time) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            @error('reservation_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informasi Tambahan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Promo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Promo (Opsional)
                            </label>
                            <select name="promo_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Tidak Pakai Promo</option>
                                @foreach($promos as $promo)
                                    <option value="{{ $promo->id }}" 
                                        {{ old('promo_id', $reservation->promo_id) == $promo->id ? 'selected' : '' }}
                                        {{ $promo->status !== 'active' ? 'disabled' : '' }}>
                                        {{ $promo->promo_code }} - 
                                        @if($promo->type == 'percent')
                                            {{ $promo->discount }}%
                                        @else
                                            Rp {{ number_format($promo->discount, 0, ',', '.') }}
                                        @endif
                                        @if($promo->status !== 'active')
                                            ({{ $promo->status_label }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('promo_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- DP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Down Payment (DP) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                <input type="number" name="total_DP" value="{{ old('total_DP', $reservation->total_DP) }}" 
                                       min="0"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                       required>
                            </div>
                            @error('total_DP')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Masukkan 0 jika tidak ada DP</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Reservasi <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required>
                                <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ old('status', $reservation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="expired" {{ old('status', $reservation->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Khusus (Opsional)
                            </label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Catatan khusus untuk reservasi...">{{ old('notes', $reservation->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Pesanan Saat Ini -->
                @if($reservation->order && $reservation->order->orderItems->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-utensils text-orange-500 mr-2"></i>
                        Pesanan Saat Ini
                    </h3>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            @foreach($reservation->order->orderItems as $item)
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg border">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensil-spoon text-orange-500 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->menu->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->qty }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <div class="flex justify-between items-center font-bold text-lg">
                                    <span>Total Pesanan:</span>
                                    <span class="text-primary">
                                        Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-700 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Untuk mengubah pesanan menu, silakan gunakan fitur tambah/edit menu di halaman detail reservasi.
                            </p>
                            <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                               class="text-primary hover:underline text-sm mt-2 inline-block">
                                <i class="fas fa-external-link-alt mr-1"></i> Pergi ke Detail Reservasi
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Ringkasan Perubahan -->
                <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-receipt text-green-500 mr-2"></i>
                        Ringkasan Perubahan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Informasi Customer</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Nama:</span>
                                    <span class="font-medium">{{ $reservation->customer_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Email:</span>
                                    <span class="font-medium">{{ $reservation->customer_email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Telepon:</span>
                                    <span class="font-medium">{{ $reservation->customer_phone }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="font-medium text-gray-700 mb-3">Status Saat Ini</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Meja:</span>
                                    <span class="font-medium">Meja {{ $reservation->table->number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Tanggal:</span>
                                    <span class="font-medium">{{ $reservation->reservation_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Waktu:</span>
                                    <span class="font-medium">{{ $reservation->formatted_time }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="font-medium">Status:</span>
                                    <span class="status-badge 
                                        @if($reservation->status === 'pending') bg-warning/10 text-warning
                                        @elseif($reservation->status === 'confirmed') bg-success/10 text-success
                                        @elseif($reservation->status === 'completed') bg-secondary/10 text-secondary
                                        @elseif(in_array($reservation->status, ['cancelled', 'expired'])) bg-red-100 text-red-600 @endif">
                                        {{ $reservation->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-primary text-white py-3 px-8 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center shadow-lg shadow-primary/25">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                       class="bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <a href="{{ route('admin.reservations.index') }}" 
                       class="bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </form>
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }

    /* Color definitions */
    .bg-primary { background-color: #3b82f6; }
    .text-primary { color: #3b82f6; }
    .bg-primary\/10 { background-color: rgba(59, 130, 246, 0.1); }
    .border-primary { border-color: #3b82f6; }

    .bg-success { background-color: #10b981; }
    .text-success { color: #10b981; }
    .bg-success\/10 { background-color: rgba(16, 185, 129, 0.1); }
    .border-success { border-color: #10b981; }

    .bg-warning { background-color: #f59e0b; }
    .text-warning { color: #f59e0b; }
    .bg-warning\/10 { background-color: rgba(245, 158, 11, 0.1); }
    .border-warning { border-color: #f59e0b; }

    .bg-secondary { background-color: #6b7280; }
    .text-secondary { color: #6b7280; }
    .bg-secondary\/10 { background-color: rgba(107, 114, 128, 0.1); }
    .border-secondary { border-color: #6b7280; }

    .bg-red-100 { background-color: #fee2e2; }
    .text-red-600 { color: #dc2626; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validasi jumlah tamu berdasarkan kapasitas meja
        const tableSelect = document.querySelector('select[name="table_id"]');
        const guestCountInput = document.querySelector('input[name="guest_count"]');

        function validateGuestCount() {
            const selectedTable = tableSelect.options[tableSelect.selectedIndex];
            if (selectedTable.value) {
                const tableInfo = selectedTable.text;
                const capacityMatch = tableInfo.match(/\((\d+) orang\)/);
                if (capacityMatch) {
                    const capacity = parseInt(capacityMatch[1]);
                    const guestCount = parseInt(guestCountInput.value);
                    
                    if (guestCount > capacity) {
                        guestCountInput.setCustomValidity(`Jumlah tamu melebihi kapasitas meja (maksimal ${capacity} orang)`);
                    } else {
                        guestCountInput.setCustomValidity('');
                    }
                }
            }
        }

        if (tableSelect && guestCountInput) {
            tableSelect.addEventListener('change', validateGuestCount);
            guestCountInput.addEventListener('input', validateGuestCount);
        }

        // Validasi tanggal tidak boleh di masa lalu
        const dateInput = document.querySelector('input[name="reservation_date"]');
        const today = new Date().toISOString().split('T')[0];
        
        if (dateInput) {
            dateInput.min = today;
            
            dateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    this.setCustomValidity('Tanggal reservasi tidak boleh di masa lalu');
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        // Show confirmation when leaving page with unsaved changes
        let formChanged = false;
        const form = document.getElementById('reservationForm');
        const initialFormData = new FormData(form);

        form.addEventListener('change', function() {
            formChanged = true;
        });

        form.addEventListener('submit', function() {
            formChanged = false;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'Perubahan yang belum disimpan akan hilang. Yakin ingin meninggalkan halaman?';
            }
        });

        // Prevent form submission when pressing Enter in inputs
        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });
    });
</script>
@endsection