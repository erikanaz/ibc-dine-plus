@extends('layouts.admin.app')

@section('title', 'Detail Reservasi - #' . $reservation->id)

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Detail Reservasi</h2>
                    <div class="flex items-center space-x-4 text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-hashtag text-primary mr-2"></i>
                            <span class="font-mono">#{{ $reservation->id }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user text-green-500 mr-2"></i>
                            <span>{{ $reservation->customer_name }}</span>
                            @if($reservation->user_id)
                                <span class="text-xs text-green-600 ml-2">
                                    <i class="fas fa-user-check"></i> Member
                                </span>
                            @else
                                <span class="text-xs text-gray-500 ml-2">
                                    <i class="fas fa-user"></i> Guest
                                </span>
                            @endif
                        </div>
                        <span class="status-badge 
                            @if($reservation->status === 'pending') bg-warning/10 text-warning
                            @elseif($reservation->status === 'confirmed') bg-success/10 text-success
                            @elseif($reservation->status === 'completed') bg-secondary/10 text-secondary
                            @elseif($reservation->status === 'cancelled') bg-red-100 text-red-600
                            @elseif($reservation->status === 'expired') bg-gray-100 text-gray-600 @endif">
                            {{ $reservation->status_label }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if(!in_array($reservation->status, ['completed', 'cancelled', 'expired']))
                        <a href="{{ route('admin.reservations.edit', $reservation->id) }}" 
                        class="btn-primary flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Reservasi
                        </a>
                    @else
                        <button class="bg-gray-300 text-gray-500 py-2 px-4 rounded-lg font-medium flex items-center cursor-not-allowed" disabled>
                            <i class="fas fa-edit mr-2"></i>
                            Edit Reservasi
                        </button>
                    @endif
                    
                    <a href="{{ route('admin.reservations.invoice', $reservation->id) }}" 
                    target="_blank"
                    class="btn-secondary flex items-center">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Invoice
                    </a>
                    <a href="{{ route('admin.reservations.index') }}" 
                    class="bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Informasi Utama -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Reservasi -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Informasi Reservasi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Customer</label>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">
                                            {{ substr($reservation->customer_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $reservation->customer_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $reservation->customer_email }}</p>
                                        @if($reservation->customer_phone)
                                            <p class="text-sm text-gray-500">{{ $reservation->customer_phone }}</p>
                                        @endif
                                        @if($reservation->user_id)
                                            <p class="text-xs text-green-600">
                                                <i class="fas fa-user-check"></i> Customer Terdaftar
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-user"></i> Guest Customer
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Meja</label>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-chair text-green-500"></i>
                                    <span class="font-medium text-gray-900">Meja {{ $reservation->table->number }}</span>
                                    <span class="text-sm text-gray-500">({{ $reservation->table->capacity }} orang)</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 capitalize">{{ $reservation->table->location_label }}</p>
                                <p class="text-xs 
                                    @if($reservation->table->status === 'available') text-green-600
                                    @elseif($reservation->table->status === 'occupied') text-warning
                                    @elseif($reservation->table->status === 'reserved') text-primary
                                    @else text-gray-600 @endif">
                                    Status: {{ $reservation->table->status_label }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal & Waktu</label>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar text-blue-500"></i>
                                    <span class="font-medium text-gray-900">{{ $reservation->reservation_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center space-x-2 mt-1">
                                    <i class="fas fa-clock text-orange-500"></i>
                                    <span class="text-gray-900">{{ $reservation->formatted_time }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Dibuat: {{ $reservation->created_at->format('d M Y H:i') }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Tamu</label>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-users text-purple-500"></i>
                                    <span class="font-medium text-gray-900">{{ $reservation->guest_count }} orang</span>
                                </div>
                            </div>

                            @if($reservation->promo)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Promo</label>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-tag text-green-500"></i>
                                    <span class="font-medium text-gray-900">{{ $reservation->promo->promo_code }}</span>
                                    <span class="text-sm text-green-600">
                                        ({{ $reservation->promo->type == 'percent' ? $reservation->promo->discount . '%' : 'Rp ' . number_format($reservation->promo->discount, 0, ',', '.') }})
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $reservation->promo->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($reservation->notes)
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <label class="block text-sm font-medium text-yellow-800 mb-1">
                            <i class="fas fa-sticky-note mr-1"></i>Catatan Khusus
                        </label>
                        <p class="text-sm text-yellow-700">{{ $reservation->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Pesanan Menu -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold flex items-center text-gray-800">
                            <i class="fas fa-utensils text-orange-500 mr-2"></i>
                            Pesanan Menu
                        </h3>
                        @if(!in_array($reservation->status, ['completed', 'cancelled', 'expired']))
                            <button type="button" 
                                    onclick="openAddMenuModal()"
                                    class="bg-primary text-white py-2 px-4 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center text-sm">
                                <i class="fas fa-plus mr-2"></i> Tambah Menu
                            </button>
                        @else
                            <button class="bg-gray-300 text-gray-500 py-2 px-4 rounded-lg font-medium flex items-center text-sm cursor-not-allowed" disabled>
                                <i class="fas fa-plus mr-2"></i> Tambah Menu
                            </button>
                        @endif
                    </div>
                    
                    @if($reservation->order && $reservation->order->orderItems->count() > 0)
                        <div class="space-y-3">
                            @foreach($reservation->order->orderItems as $item)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-4 flex-1">
                                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensil-spoon text-white"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $item->menu->name }}</h4>
                                            <p class="text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} per item</p>
                                            @if($item->menu->description)
                                                <p class="text-xs text-gray-400 mt-1">{{ Str::limit($item->menu->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">
                                                {{ $item->qty }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </div>
                                            <div class="font-bold text-primary text-lg">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            @if(!in_array($reservation->status, ['completed', 'cancelled', 'expired']))
                                                <button type="button" 
                                                        onclick="openEditMenuModal({{ $item->id }}, {{ $item->qty }})"
                                                        class="text-primary hover:text-primary/80 transition-colors p-2 rounded-lg hover:bg-blue-50"
                                                        title="Edit Jumlah">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.reservations.remove-menu', [$reservation->id, $item->id]) }}" 
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus {{ $item->menu->name }} dari pesanan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-lg hover:bg-red-50"
                                                            title="Hapus Menu">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Total Pesanan -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center text-lg font-bold">
                                    <span>Total Pesanan:</span>
                                    <span class="text-primary text-xl">
                                        Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                            <p class="text-gray-400">Belum ada pesanan menu</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Informasi Tambahan -->
            <div class="space-y-6">
                <!-- Ringkasan Pembayaran -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-receipt text-green-500 mr-2"></i>
                        Ringkasan Pembayaran
                    </h3>
                    
                    <div class="space-y-3">
                        @if($reservation->order)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Pesanan:</span>
                                <span class="font-medium">
                                    Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            @if($reservation->promo)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon Promo:</span>
                                <span class="font-medium">
                                    -Rp 
                                    @if($reservation->promo->type == 'percent')
                                        {{ number_format(($reservation->order->total_price * $reservation->promo->discount / 100), 0, ',', '.') }}
                                    @else
                                        {{ number_format($reservation->promo->discount, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="font-medium">Total Tagihan:</span>
                                <span class="font-bold text-lg text-primary">
                                    Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between text-orange-600">
                                <span>DP Dibayar:</span>
                                <span class="font-medium">-Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                @php
                                    $remaining = $reservation->order->total_price - $reservation->total_DP;
                                @endphp
                                <span class="font-bold">Sisa Pembayaran:</span>
                                <span class="font-bold text-lg 
                                    @if($remaining <= 0) text-green-600 
                                    @else text-red-600 @endif">
                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($remaining <= 0)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2">
                                    <div class="flex items-center text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span class="text-sm font-medium">Lunas</span>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2">
                                    <div class="flex items-center text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <span class="text-sm font-medium">Belum Lunas</span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-receipt text-2xl mb-2 text-gray-300"></i>
                                <p>Belum ada pesanan</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Sistem -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-cog text-gray-500 mr-2"></i>
                        Informasi Sistem
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dibuat:</span>
                            <span class="text-gray-900">{{ $reservation->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diupdate:</span>
                            <span class="text-gray-900">{{ $reservation->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status Sistem:</span>
                            <span class="status-badge 
                                @if($reservation->status === 'pending') bg-warning/10 text-warning
                                @elseif($reservation->status === 'confirmed') bg-success/10 text-success
                                @elseif($reservation->status === 'completed') bg-secondary/10 text-secondary
                                @elseif($reservation->status === 'cancelled') bg-red-100 text-red-600
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $reservation->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Aksi Cepat
                    </h3>
                    
                    <div class="space-y-3">
                        @if($reservation->status === 'confirmed')
                            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" 
                                        class="w-full bg-green-100 text-green-700 py-3 rounded-lg font-medium hover:bg-green-200 transition-colors flex items-center justify-center">
                                    <i class="fas fa-check-circle mr-2"></i> Tandai Selesai
                                </button>
                            </form>
                        @endif

                        @if($reservation->status === 'completed')
                            <div class="w-full bg-gray-100 text-gray-500 py-3 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-check-circle mr-2"></i> Sudah Selesai
                            </div>
                        @endif
                        
                        @if(!in_array($reservation->status, ['cancelled', 'completed', 'expired']))
                            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" 
                                        class="w-full bg-red-100 text-red-700 py-3 rounded-lg font-medium hover:bg-red-200 transition-colors flex items-center justify-center">
                                    <i class="fas fa-times-circle mr-2"></i> Batalkan Reservasi
                                </button>
                            </form>
                        @endif

                        @if($reservation->status === 'cancelled')
                            <div class="w-full bg-gray-100 text-gray-500 py-3 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-times-circle mr-2"></i> Sudah Dibatalkan
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST" class="w-full"
                              onsubmit="return confirm('Yakin ingin menghapus reservasi #{{ $reservation->id }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-trash mr-2"></i> Hapus Reservasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Menu -->
    <div id="menuModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold mb-4" id="modalTitle">Tambah Menu</h3>
            
            <form id="menuForm" method="POST">
                @csrf
                <div id="methodField"></div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Menu</label>
                    <select name="menu_id" id="menuSelect" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            required>
                        <option value="">Pilih Menu</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">
                                {{ $menu->name }} - Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="quantity" id="quantityInput" value="1" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           required>
                </div>

                <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Subtotal: <span id="modalSubtotal" class="font-bold">Rp 0</span>
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeMenuModal()"
                            class="bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-primary text-white py-2 px-4 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
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
    
    .btn-secondary {
        background-color: #6b7280;
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
    
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
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
    let currentEditItemId = null;

    function openAddMenuModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Menu';
        document.getElementById('menuForm').action = '{{ route("admin.reservations.add-menu", $reservation->id) }}';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('menuSelect').value = '';
        document.getElementById('quantityInput').value = '1';
        currentEditItemId = null;
        updateModalSubtotal();
        document.getElementById('menuModal').classList.remove('hidden');
    }

    function openEditMenuModal(itemId, currentQuantity) {
        document.getElementById('modalTitle').textContent = 'Edit Jumlah Menu';
        document.getElementById('menuForm').action = '{{ route("admin.reservations.update-menu", [$reservation->id, 'ITEM_ID']) }}'.replace('ITEM_ID', itemId);
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('quantityInput').value = currentQuantity;
        document.getElementById('menuSelect').disabled = true;
        currentEditItemId = itemId;
        updateModalSubtotal();
        document.getElementById('menuModal').classList.remove('hidden');
    }

    function closeMenuModal() {
        document.getElementById('menuModal').classList.add('hidden');
        document.getElementById('menuSelect').disabled = false;
        currentEditItemId = null;
    }

    function updateModalSubtotal() {
        const menuSelect = document.getElementById('menuSelect');
        const quantityInput = document.getElementById('quantityInput');
        const subtotalElement = document.getElementById('modalSubtotal');
        
        const selectedOption = menuSelect.options[menuSelect.selectedIndex];
        const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const subtotal = price * quantity;
        
        subtotalElement.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    // Event listeners
    document.getElementById('menuSelect').addEventListener('change', updateModalSubtotal);
    document.getElementById('quantityInput').addEventListener('input', updateModalSubtotal);

    // Close modal when clicking outside
    document.getElementById('menuModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMenuModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMenuModal();
        }
    });

    // Initialize subtotal on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateModalSubtotal();
    });
</script>
@endsection