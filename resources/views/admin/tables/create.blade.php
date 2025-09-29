@extends('layouts.admin.app')

@section('title', 'Tambah Meja Baru')
@section('subtitle', 'Tambah meja baru ke dalam sistem')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">Tambah Meja Baru</h2>
                    <p class="text-gray-600 text-base">Tambahkan meja baru ke dalam sistem restoran</p>
                </div>
                <div>
                    <a href="{{ route('admin.tables.index') }}" class="btn-secondary flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow">
            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                
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
                                       value="{{ old('number') }}"
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
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" 
                                        id="status"
                                        class="form-input @error('status') border-red-500 @enderror"
                                        required>
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

                    <!-- Status Visualization -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-palette text-primary mr-2"></i>
                            Preview Status
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="statusPreview">
                            @foreach($statuses as $value => $label)
                                <div class="status-preview-card 
                                    @if(old('status') == $value) selected 
                                    @elseif($loop->first && !old('status')) selected @endif
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
                        <input type="hidden" name="status" id="selectedStatus" value="{{ old('status', 'available') }}">
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
                                      placeholder="Catatan tambahan tentang meja (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Catatan khusus tentang meja ini</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t rounded-b-xl flex justify-between items-center">
                    <a href="{{ route('admin.tables.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Meja
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                <i class="fas fa-lightbulb mr-2"></i>Tips
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
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
</script>
@endsection