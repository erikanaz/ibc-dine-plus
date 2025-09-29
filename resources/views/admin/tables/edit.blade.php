@extends('layouts.admin.app')

@section('title', 'Edit Meja - ' . $table->number)
@section('subtitle', 'Edit informasi meja ' . $table->number)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">Edit Meja {{ $table->number }}</h2>
                    <p class="text-gray-600 text-base">Edit informasi meja di sistem restoran</p>
                </div>
                <div>
                    <a href="{{ route('admin.tables.index', ['selected_table' => $table->id]) }}" class="btn-secondary flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow">
            <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            Informasi Dasar
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Table Number -->
                            <div>
                                <label for="number" class="form-label">Nomor Meja *</label>
                                <input type="text" 
                                       name="number" 
                                       id="number"
                                       value="{{ old('number', $table->number) }}"
                                       class="form-input @error('number') border-red-500 @enderror"
                                       placeholder="Contoh: 01, 02, A1, B2"
                                       required>
                                @error('number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-xs mt-1">Nomor unik untuk identifikasi meja</p>
                            </div>

                            <!-- Capacity -->
                            <div>
                                <label for="capacity" class="form-label">Kapasitas *</label>
                                <select name="capacity" 
                                        id="capacity"
                                        class="form-input @error('capacity') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Kapasitas</option>
                                    <option value="4" {{ old('capacity', $table->capacity) == '4' ? 'selected' : '' }}>4 Orang</option>
                                    <option value="5" {{ old('capacity', $table->capacity) == '5' ? 'selected' : '' }}>5 Orang</option>
                                    <option value="6" {{ old('capacity', $table->capacity) == '6' ? 'selected' : '' }}>6 Orang</option>
                                </select>
                                @error('capacity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                            Informasi Lokasi
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Location -->
                            <div>
                                <label for="location" class="form-label">Lokasi *</label>
                                <select name="location" 
                                        id="location"
                                        class="form-input @error('location') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($locations as $value => $label)
                                        <option value="{{ $value }}" {{ old('location', $table->location) == $value ? 'selected' : '' }}>
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
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" 
                                        id="status"
                                        class="form-input @error('status') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Status</option>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $table->status) == $value ? 'selected' : '' }}>
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

                    <!-- Status Visualization -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-palette text-primary mr-2"></i>
                            Preview Status
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="statusPreview">
                            @foreach($statuses as $value => $label)
                                <div class="status-preview-card 
                                    @if(old('status', $table->status) == $value) selected @endif
                                    " data-status="{{ $value }}">
                                    <div class="text-center p-4 rounded-lg border-2 transition-all">
                                        <div class="mb-2">
                                            @if($value == 'available')
                                                <i class="fas fa-check-circle text-success text-xl"></i>
                                            @elseif($value == 'occupied')
                                                <i class="fas fa-users text-warning text-xl"></i>
                                            @elseif($value == 'reserved')
                                                <i class="fas fa-calendar-check text-secondary text-xl"></i>
                                            @else
                                                <i class="fas fa-tools text-gray-500 text-xl"></i>
                                            @endif
                                        </div>
                                        <div class="font-medium">{{ $label }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Klik untuk memilih</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="status" id="selectedStatus" value="{{ old('status', $table->status) }}">
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-sticky-note text-primary mr-2"></i>
                            Informasi Tambahan
                        </h3>
                        
                        <div>
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      class="form-input @error('notes') border-red-500 @enderror"
                                      placeholder="Catatan tambahan tentang meja (opsional)">{{ old('notes', $table->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Catatan khusus tentang meja ini</p>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3">Informasi Sistem</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Dibuat pada:</span>
                                <span class="font-medium">{{ $table->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Diupdate pada:</span>
                                <span class="font-medium">{{ $table->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t rounded-b-xl flex justify-between items-center">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.tables.index', ['selected_table' => $table->id]) }}" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="button" 
                                onclick="confirmDelete()" 
                                class="btn-danger flex items-center">
                            <i class="fas fa-trash mr-2"></i>Hapus Meja
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form -->
            <form id="deleteForm" action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <!-- Current Status & Tips -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Quick Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>Tips
                </h4>
                <ul class="text-blue-700 text-sm space-y-1">
                    <li>• Pastikan nomor meja unik dan tidak duplikat</li>
                    <li>• Pilih kapasitas sesuai dengan ukuran meja sebenarnya</li>
                    <li>• Tentukan lokasi untuk memudahkan penempatan</li>
                    <li>• Perbarui status sesuai kondisi terkini meja</li>
                </ul>
            </div>

            <!-- Current Table Info -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Saat Ini
                </h4>
                <div class="text-green-700 text-sm space-y-2">
                    <div class="flex justify-between">
                        <span>Status:</span>
                        <span class="font-medium">{{ $table->status_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Lokasi:</span>
                        <span class="font-medium">{{ $table->location_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kapasitas:</span>
                        <span class="font-medium">{{ $table->capacity }} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Catatan:</span>
                        <span class="font-medium">{{ $table->notes ?: '-' }}</span>
                    </div>
                </div>
            </div>
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background-color: #ef4444;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
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
        transition: all 0.2s;
        background-color: white;
        font-size: 0.875rem;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .border-red-500 {
        border-color: #ef4444;
    }
    
    .text-red-500 {
        color: #ef4444;
    }
    
    .status-preview-card {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .status-preview-card .border-2 {
        border-color: #e5e7eb;
        background-color: white;
    }
    
    .status-preview-card.selected .border-2 {
        border-color: #3b82f6;
        background-color: #eff6ff;
        transform: scale(1.05);
    }
    
    .status-preview-card:hover .border-2 {
        border-color: #3b82f6;
        transform: translateY(-2px);
    }

    /* Status colors */
    .text-success { color: #10b981; }
    .text-warning { color: #f59e0b; }
    .text-secondary { color: #6b7280; }

    /* Background colors for info boxes */
    .bg-blue-50 { background-color: #eff6ff; }
    .border-blue-200 { border-color: #bfdbfe; }
    .text-blue-800 { color: #1e40af; }
    .text-blue-700 { color: #1d4ed8; }

    .bg-green-50 { background-color: #f0fdf4; }
    .border-green-200 { border-color: #bbf7d0; }
    .text-green-800 { color: #166534; }
    .text-green-700 { color: #15803d; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status preview card selection
        const statusCards = document.querySelectorAll('.status-preview-card');
        const statusSelect = document.getElementById('status');
        const selectedStatusInput = document.getElementById('selectedStatus');
        
        function selectStatusCard(card) {
            const status = card.getAttribute('data-status');
            
            // Remove selected class from all cards
            statusCards.forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            card.classList.add('selected');
            
            // Update select and hidden input
            statusSelect.value = status;
            selectedStatusInput.value = status;
        }
        
        statusCards.forEach(card => {
            card.addEventListener('click', function() {
                selectStatusCard(this);
            });
        });

        // Sync select change with cards
        statusSelect.addEventListener('change', function() {
            const status = this.value;
            const targetCard = document.querySelector(`[data-status="${status}"]`);
            if (targetCard) {
                selectStatusCard(targetCard);
            }
        });

        // Auto-capitalize table number
        const numberInput = document.getElementById('number');
        if (numberInput) {
            numberInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }

        // Initialize with current status
        const currentStatus = selectedStatusInput.value;
        if (currentStatus) {
            const currentCard = document.querySelector(`[data-status="${currentStatus}"]`);
            if (currentCard) {
                selectStatusCard(currentCard);
            }
        }
    });

    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus meja {{ $table->number }}? Tindakan ini tidak dapat dibatalkan!')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection