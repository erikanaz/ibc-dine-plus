@extends('layouts.admin.app')

@section('title', 'Buat Reservasi Baru')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Buat Reservasi Baru</h2>
            <p class="text-gray-600">Buat reservasi beserta pesanan menu untuk customer</p>
        </div>

        <form action="{{ route('admin.reservations.store') }}" method="POST" id="reservationForm">
            @csrf
            
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
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" 
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
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" 
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
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" 
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
                                    <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                                        Meja {{ $table->number }} ({{ $table->capacity }} orang) - {{ $table->location_label }}
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
                            <input type="number" name="guest_count" value="{{ old('guest_count', 2) }}" 
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
                            <input type="date" name="reservation_date" value="{{ old('reservation_date', date('Y-m-d')) }}"
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
                            <input type="time" name="reservation_time" value="{{ old('reservation_time', '18:00') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            @error('reservation_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pesanan Menu -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-utensils text-orange-500 mr-2"></i>
                        Pesanan Menu
                        <span class="ml-2 text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">Wajib pilih minimal 1 menu</span>
                    </h3>
                    
                    <div id="menu-items-container">
                        <!-- Item menu pertama -->
                        <div class="menu-item border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Menu <span class="text-red-500">*</span>
                                    </label>
                                    <select name="menus[0][menu_id]" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent menu-select"
                                            required>
                                        <option value="">Pilih Menu</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">
                                                {{ $menu->name }} - Rp {{ number_format($menu->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="menus[0][quantity]" value="1" min="1"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent quantity-input"
                                           required>
                                </div>
                                <div class="md:col-span-2">
                                    <button type="button" 
                                            class="w-full bg-red-100 text-red-600 py-2 rounded-lg font-medium hover:bg-red-200 transition-colors remove-menu hidden"
                                            onclick="removeMenuItem(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600 menu-subtotal">
                                Subtotal: Rp 0
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addMenuItem()" 
                            class="bg-green-100 text-green-700 py-2 px-4 rounded-lg font-medium hover:bg-green-200 transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i> Tambah Menu Lain
                    </button>
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
                                    <option value="{{ $promo->id }}" {{ old('promo_id') == $promo->id ? 'selected' : '' }}>
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
                                <input type="number" name="total_DP" value="{{ old('total_DP', 0) }}" 
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
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                                      placeholder="Catatan khusus untuk reservasi...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Ringkasan -->
                <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-receipt text-green-500 mr-2"></i>
                        Ringkasan Reservasi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Detail Pesanan</h4>
                            <div id="orderSummary" class="space-y-2 text-sm text-gray-600">
                                <!-- Ringkasan menu akan ditampilkan di sini -->
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="font-medium text-gray-700 mb-3">Total Pembayaran</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span id="subtotalAmount">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-green-600" id="discountRow" style="display: none;">
                                    <span>Diskon:</span>
                                    <span id="discountAmount">-Rp 0</span>
                                </div>
                                <div class="flex justify-between border-t pt-2 font-bold">
                                    <span>Total Tagihan:</span>
                                    <span id="totalAmount" class="text-primary text-lg">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-orange-600">
                                    <span>DP:</span>
                                    <span id="dpAmount">-Rp 0</span>
                                </div>
                                <div class="flex justify-between border-t pt-2 font-bold">
                                    <span>Sisa Bayar:</span>
                                    <span id="remainingAmount" class="text-lg">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-primary text-white py-3 px-8 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center shadow-lg shadow-primary/25">
                        <i class="fas fa-save mr-2"></i> Simpan Reservasi
                    </button>
                    <a href="{{ route('admin.reservations.index') }}" 
                       class="bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="button" onclick="resetForm()"
                            class="bg-orange-100 text-orange-700 py-3 px-6 rounded-lg font-medium hover:bg-orange-200 transition-colors flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    let menuItemCount = 1;

    document.addEventListener('DOMContentLoaded', function() {
        updateOrderSummary();
        
        // Event listeners untuk update real-time
        document.querySelectorAll('.menu-select, .quantity-input').forEach(element => {
            element.addEventListener('change', updateOrderSummary);
        });
        
        document.querySelector('select[name="promo_id"]').addEventListener('change', updateOrderSummary);
        document.querySelector('input[name="total_DP"]').addEventListener('input', updateOrderSummary);
    });

    function addMenuItem() {
        const container = document.getElementById('menu-items-container');
        const template = `
            <div class="menu-item border border-gray-200 rounded-lg p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Menu <span class="text-red-500">*</span>
                        </label>
                        <select name="menus[${menuItemCount}][menu_id]" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent menu-select"
                                required>
                            <option value="">Pilih Menu</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">
                                    {{ $menu->name }} - Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="menus[${menuItemCount}][quantity]" value="1" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent quantity-input"
                               required>
                    </div>
                    <div class="md:col-span-2">
                        <button type="button" 
                                class="w-full bg-red-100 text-red-600 py-2 rounded-lg font-medium hover:bg-red-200 transition-colors remove-menu"
                                onclick="removeMenuItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-600 menu-subtotal">
                    Subtotal: Rp 0
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', template);
        
        // Add event listeners to new elements
        const newItem = container.lastElementChild;
        newItem.querySelector('.menu-select').addEventListener('change', updateOrderSummary);
        newItem.querySelector('.quantity-input').addEventListener('input', updateOrderSummary);
        
        // Show remove buttons if there's more than 1 item
        if (document.querySelectorAll('.menu-item').length > 1) {
            document.querySelectorAll('.remove-menu').forEach(btn => {
                btn.classList.remove('hidden');
            });
        }
        
        menuItemCount++;
    }

    function removeMenuItem(button) {
        const menuItems = document.querySelectorAll('.menu-item');
        if (menuItems.length > 1) {
            button.closest('.menu-item').remove();
            updateOrderSummary();
            
            // Hide remove button if only 1 item left
            if (menuItems.length === 2) { // Karena kita baru hapus 1, jadi sisa 1
                document.querySelectorAll('.remove-menu').forEach(btn => {
                    btn.classList.add('hidden');
                });
            }
        }
    }

    function updateOrderSummary() {
        let subtotal = 0;
        const orderSummary = document.getElementById('orderSummary');
        let summaryHTML = '';

        // Calculate subtotal and build summary
        document.querySelectorAll('.menu-item').forEach((item, index) => {
            const select = item.querySelector('.menu-select');
            const quantityInput = item.querySelector('.quantity-input');
            const subtotalElement = item.querySelector('.menu-subtotal');
            
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
            const quantity = parseInt(quantityInput.value) || 0;
            const itemSubtotal = price * quantity;
            
            subtotal += itemSubtotal;
            
            // Update individual item subtotal
            if (subtotalElement) {
                subtotalElement.textContent = `Subtotal: Rp ${formatCurrency(itemSubtotal)}`;
            }
            
            // Build summary
            if (selectedOption && selectedOption.value) {
                summaryHTML += `
                    <div class="flex justify-between">
                        <span>${selectedOption.text.split(' - ')[0]} × ${quantity}</span>
                        <span>Rp ${formatCurrency(itemSubtotal)}</span>
                    </div>
                `;
            }
        });

        // Apply promo discount
        const promoSelect = document.querySelector('select[name="promo_id"]');
        const selectedPromo = promoSelect.options[promoSelect.selectedIndex];
        let discount = 0;
        let total = subtotal;

        if (selectedPromo && selectedPromo.value) {
            const promoText = selectedPromo.text;
            if (promoText.includes('%')) {
                // Percent discount
                const discountPercent = parseFloat(promoText.match(/(\d+)%/)[1]);
                discount = subtotal * (discountPercent / 100);
            } else {
                // Fixed discount
                discount = parseFloat(promoText.match(/Rp ([\d.,]+)/)[1].replace(/\./g, ''));
            }
            total = subtotal - discount;
            
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountAmount').textContent = `-Rp ${formatCurrency(discount)}`;
        } else {
            document.getElementById('discountRow').style.display = 'none';
        }

        // Get DP value
        const dp = parseFloat(document.querySelector('input[name="total_DP"]').value) || 0;
        const remaining = total - dp;

        // Update summary elements
        orderSummary.innerHTML = summaryHTML || '<p class="text-gray-400">Belum ada menu dipilih</p>';
        document.getElementById('subtotalAmount').textContent = `Rp ${formatCurrency(subtotal)}`;
        document.getElementById('totalAmount').textContent = `Rp ${formatCurrency(total)}`;
        document.getElementById('dpAmount').textContent = `-Rp ${formatCurrency(dp)}`;
        
        const remainingElement = document.getElementById('remainingAmount');
        remainingElement.textContent = `Rp ${formatCurrency(remaining)}`;
        remainingElement.className = `text-lg ${remaining <= 0 ? 'text-green-600' : 'text-red-600'}`;
    }

    function formatCurrency(amount) {
        return Math.round(amount).toLocaleString('id-ID');
    }

    function resetForm() {
        if (confirm('Yakin ingin mengosongkan semua form?')) {
            document.getElementById('reservationForm').reset();
            // Reset menu items to only one
            const container = document.getElementById('menu-items-container');
            const menuItems = document.querySelectorAll('.menu-item');
            
            for (let i = menuItems.length - 1; i > 0; i--) {
                menuItems[i].remove();
            }
            
            // Reset first menu item
            const firstItem = container.querySelector('.menu-item');
            firstItem.querySelector('.menu-select').value = '';
            firstItem.querySelector('.quantity-input').value = '1';
            firstItem.querySelector('.menu-subtotal').textContent = 'Subtotal: Rp 0';
            
            // Hide remove button
            document.querySelectorAll('.remove-menu').forEach(btn => {
                btn.classList.add('hidden');
            });
            
            menuItemCount = 1;
            updateOrderSummary();
        }
    }
</script>
@endsection