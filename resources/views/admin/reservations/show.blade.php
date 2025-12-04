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
                            <span class="font-mono">{{ $reservation->id }}</span>
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($reservation->status === 'waiting_payment') bg-blue-100 text-blue-800
                            @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($reservation->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($reservation->status === 'completed') bg-blue-100 text-blue-800
                            @elseif(in_array($reservation->status, ['cancelled', 'expired'])) bg-red-100 text-red-800 @endif">
                            {{ $reservation->status_label }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3">
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

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <!-- Left Column - Informasi Utama -->
            <div class="xl:col-span-3 space-y-6">
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
                                    @elseif($reservation->table->status === 'occupied') text-yellow-600
                                    @elseif($reservation->table->status === 'reserved') text-blue-600
                                    @else text-gray-600 @endif">
                                    Status: {{ $reservation->table->status_label ?? $reservation->table->status }}
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
                                        ({{ $reservation->promo->discount }}%)
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
                        
                        <!-- Tombol Tambah Pesanan -->
                        @if(in_array($reservation->status, ['confirmed', 'pending']))
                        <button type="button" 
                                onclick="openAddMenuModal()"
                                class="bg-primary text-white py-2 px-4 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Pesanan
                        </button>
                        @endif
                    </div>
                    
                    <!-- Modal Tambah Pesanan -->
                    <div id="addMenuModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
                        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-800">Tambah Pesanan Menu</h3>
                                    <button type="button" 
                                            onclick="closeAddMenuModal()"
                                            class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <form action="{{ route('admin.reservations.add-on-site-order', $reservation->id) }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Menu</label>
                                            <select name="menu_id" 
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
                                                <option value="">-- Pilih Menu --</option>
                                                @foreach($menus as $menu)
                                                    <option value="{{ $menu->id }}" 
                                                            data-price="{{ $menu->price }}">
                                                        {{ $menu->name }} - Rp {{ number_format($menu->price, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="1" 
                                                   min="1" 
                                                   max="20"
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
                                                   required>
                                        </div>
                                        
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Harga per item:</span>
                                                <span id="pricePreview" class="font-medium">Rp 0</span>
                                            </div>
                                            <div class="flex justify-between text-sm mt-1">
                                                <span class="text-gray-600">Subtotal:</span>
                                                <span id="subtotalPreview" class="font-bold text-primary">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-3 mt-6">
                                        <button type="button"
                                                onclick="closeAddMenuModal()"
                                                class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit" 
                                                class="flex-1 bg-primary text-white py-2 px-4 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                                            Tambah ke Pesanan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    @php
                        $order = $reservation->orders->first();
                    @endphp
                    
                    @if($order && $order->orderItems->count() > 0)
                        <div class="space-y-3">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors group">
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
                                            <div class="flex items-center space-x-2 mt-2">
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    @if($item->created_at->diffInMinutes(now()) < 5)
                                                        Baru ditambahkan
                                                    @else
                                                        Ditambahkan: {{ $item->created_at->format('H:i') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <!-- Quantity & Edit -->
                                        <div class="flex items-center space-x-2">
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600 mb-1">
                                                    <form action="{{ route('admin.reservations.edit-order-item', [$reservation->id, $item->id]) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Update quantity {{ $item->menu->name }}?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-xs text-gray-500">Qty:</span>
                                                            <input type="number" 
                                                                   name="quantity" 
                                                                   value="{{ $item->qty }}" 
                                                                   min="1" 
                                                                   max="20"
                                                                   class="w-16 border border-gray-300 rounded px-2 py-1 text-sm text-center focus:outline-none focus:ring-1 focus:ring-primary">
                                                            <button type="submit" 
                                                                    class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50"
                                                                    title="Update Quantity">
                                                                <i class="fas fa-check text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="font-bold text-primary text-lg">
                                                    Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.reservations.delete-order-item', [$reservation->id, $item->id]) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Hapus {{ $item->menu->name }} dari pesanan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-full hover:bg-red-50"
                                                    title="Hapus Pesanan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Total Pesanan -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center text-lg font-bold">
                                    <span>Total Pesanan:</span>
                                    <span class="text-primary text-xl">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 text-right">
                                    {{ $order->orderItems->sum('qty') }} porsi total
                                </div>
                                
                                <!-- Info Actions -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-3">
                                    <div class="flex items-center text-blue-800">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <span class="text-sm">Klik quantity untuk edit, atau tombol trash untuk hapus pesanan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                            <p class="text-gray-400">Belum ada pesanan menu</p>
                            @if(in_array($reservation->status, ['confirmed', 'pending']))
                            <p class="text-sm text-gray-500 mt-2">Klik "Tambah Pesanan" untuk menambah menu</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Informasi Tambahan & Aksi -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Ringkasan Pembayaran & Bukti Pembayaran -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-receipt text-green-500 mr-2"></i>
                        Ringkasan Pembayaran
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Ringkasan Biaya -->
                        <div class="space-y-3">
                            @php
                                $order = $reservation->orders->first();
                                
                                // ✅ BENAR: Hitung subtotal dari item (sebelum diskon)
                                $subtotal = 0;
                                if ($order && $order->orderItems->count() > 0) {
                                    foreach ($order->orderItems as $item) {
                                        $subtotal += $item->price * $item->qty;
                                    }
                                }
                                
                                // ✅ BENAR: Total setelah diskon (ambil dari database)
                                $totalAfterDiscount = $order ? $order->total_price : 0;
                                
                                // ✅ BENAR: Hitung diskon yang sebenarnya
                                $actualDiscount = $subtotal - $totalAfterDiscount;
                                
                                // ✅ BENAR: Sisa pembayaran
                                $remaining = $totalAfterDiscount - $reservation->total_DP;
                            @endphp

                            <!-- Tampilkan subtotal (sebelum diskon) -->
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    @if($order && $order->orderItems->count() > 0)
                                        Subtotal Pesanan:
                                    @else
                                        Biaya Reservasi:
                                    @endif
                                </span>
                                <span class="font-medium">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <!-- Diskon Promo -->
                            @if($reservation->promo && $actualDiscount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon {{ $reservation->promo->name }} ({{ $reservation->promo->discount }}%):</span>
                                <span class="font-medium">
                                    -Rp {{ number_format($actualDiscount, 0, ',', '.') }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Total Setelah Diskon -->
                            <div class="flex justify-between border-t border-gray-200 pt-2 font-medium">
                                <span>Total Setelah Diskon:</span>
                                <span class="text-primary">
                                    Rp {{ number_format($totalAfterDiscount, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <!-- DP yang sudah dibayar -->
                            <div class="flex justify-between text-orange-600">
                                <span>DP Dibayar (30%):</span>
                                <span class="font-medium">-Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                            </div>
                            
                            <!-- Sisa Pembayaran -->
                            <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-lg">
                                <span>Sisa Pembayaran:</span>
                                <span class="@if($remaining <= 0) text-green-600 @else text-red-600 @endif">
                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Status Lunas/Belum -->
                            @if($reservation->is_fully_paid)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2 text-center">
                                    <p class="text-sm text-green-700">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <strong>LUNAS</strong>
                                        @if($reservation->fully_paid_at)
                                            - Dibayar pada: {{ $reservation->fully_paid_at->format('d M Y H:i') }}
                                        @endif
                                    </p>
                                </div>
                            @elseif($remaining > 0)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2 text-center">
                                    <p class="text-sm text-yellow-700">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Belum lunas. Sisa bayar: <strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Bukti Pembayaran -->
                        @if($reservation->payments && $reservation->payments->count() > 0)
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h4 class="font-bold mb-3 flex items-center text-gray-800">
                                <i class="fas fa-file-invoice-dollar text-blue-500 mr-2"></i>
                                Bukti Pembayaran
                            </h4>
                            
                            <div class="space-y-3">
                                @foreach($reservation->payments as $payment)
                                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                    <!-- Tanggal & Jumlah -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $payment->created_at->format('d M Y H:i') }}
                                            </p>
                                            <p class="text-lg font-bold text-primary">
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                            </p>
                                            @if($payment->payment_type === 'final')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Pembayaran Final
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    DP
                                                </span>
                                            @endif
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            @if($payment->status === 'verifying') bg-yellow-100 text-yellow-800
                                            @elseif($payment->status === 'paid') bg-green-100 text-green-800
                                            @elseif($payment->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $payment->status_label ?? ucfirst($payment->status) }}
                                        </span>
                                    </div>

                                    <!-- Foto Bukti Pembayaran -->
                                    @if($payment->payment_proof)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Bukti Transfer</label>
                                        <div class="space-y-3">
                                            <!-- Gambar Lebih Besar -->
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . str_replace('public/', '', $payment->payment_proof)) }}" 
                                                    alt="Bukti Pembayaran"
                                                    class="w-full max-w-md h-80 object-contain rounded-lg border-2 border-gray-300 cursor-pointer hover:border-primary transition-colors"
                                                    onclick="openImageModal('{{ asset('storage/' . str_replace('public/', '', $payment->payment_proof)) }}')">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 rounded-lg transition-all cursor-pointer"></div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex space-x-3">
                                                <button type="button"
                                                        onclick="openImageModal('{{ asset('storage/' . str_replace('public/', '', $payment->payment_proof)) }}')"
                                                        class="flex items-center space-x-2 text-primary hover:text-primary/80 transition-colors">
                                                    <i class="fas fa-expand"></i>
                                                    <span class="text-sm">Perbesar</span>
                                                </button>
                                                <a href="{{ asset('storage/' . str_replace('public/', '', $payment->payment_proof)) }}" 
                                                target="_blank"
                                                class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 transition-colors">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    <span class="text-sm">Buka di Tab Baru</span>
                                                </a>
                                                <a href="{{ asset('storage/' . str_replace('public/', '', $payment->payment_proof)) }}" 
                                                download
                                                class="flex items-center space-x-2 text-green-600 hover:text-green-800 transition-colors">
                                                    <i class="fas fa-download"></i>
                                                    <span class="text-sm">Download</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @elseif($reservation->status === 'waiting_payment')
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h4 class="font-bold mb-2 flex items-center text-gray-800">
                                <i class="fas fa-file-invoice-dollar text-gray-500 mr-2"></i>
                                Bukti Pembayaran
                            </h4>
                            <div class="text-center py-4 text-gray-500 bg-gray-50 rounded-lg">
                                <i class="fas fa-clock text-xl mb-2 text-gray-300"></i>
                                <p class="text-sm text-gray-400">Menunggu upload bukti pembayaran</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Kelola Status
                    </h3>
                    
                    <div class="space-y-3">
                        <!-- Konfirmasi hanya untuk pending (setelah bayar DP) -->
                        @if($reservation->status === 'pending')
                            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" 
                                        class="w-full bg-green-100 text-green-700 py-3 rounded-lg font-medium hover:bg-green-200 transition-colors flex items-center justify-center">
                                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Reservasi
                                </button>
                            </form>
                        @endif

                        @if($reservation->status === 'waiting_payment')
                            <div class="w-full bg-gray-100 text-gray-500 py-3 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-clock mr-2"></i> Menunggu Pembayaran DP
                            </div>
                        @endif

                        <!-- Selesai untuk confirmed -->
                        @if($reservation->status === 'confirmed')
                            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" 
                                        class="w-full bg-blue-100 text-blue-700 py-3 rounded-lg font-medium hover:bg-blue-200 transition-colors flex items-center justify-center">
                                    <i class="fas fa-flag-checkered mr-2"></i> Tandai Selesai
                                </button>
                            </form>
                        @endif

                        @if($reservation->status === 'completed')
                            <div class="w-full bg-gray-100 text-gray-500 py-3 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-check-circle mr-2"></i> Sudah Selesai
                            </div>
                        @endif
                        
                        <!-- Batalkan untuk waiting_payment, pending, confirmed -->
                        @if(in_array($reservation->status, ['waiting_payment', 'pending', 'confirmed']))
                            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin membatalkan reservasi ini?')"
                                        class="w-full bg-red-100 text-red-700 py-3 rounded-lg font-medium hover:bg-red-200 transition-colors flex items-center justify-center">
                                    <i class="fas fa-times-circle mr-2"></i> Batalkan Reservasi
                                </button>
                            </form>
                        @endif

                        @if(in_array($reservation->status, ['cancelled', 'expired']))
                            <div class="w-full bg-gray-100 text-gray-500 py-3 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-times-circle mr-2"></i> Sudah Dibatalkan
                            </div>
                        @endif

                        <!-- Pulihkan untuk cancelled/expired -->
                        @if(in_array($reservation->status, ['cancelled', 'expired']))
                            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" 
                                        class="w-full bg-yellow-100 text-yellow-700 py-3 rounded-lg font-medium hover:bg-yellow-200 transition-colors flex items-center justify-center">
                                    <i class="fas fa-undo mr-2"></i> Pulihkan Reservasi
                                </button>
                            </form>
                        @endif

                        <!-- Pembayaran Final Section -->
                        {{-- @if(in_array($reservation->status, ['confirmed', 'pending']) && $reservation->remaining_payment > 0)
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h4 class="font-bold mb-3 flex items-center text-gray-800">
                                <i class="fas fa-cash-register text-green-500 mr-2"></i>
                                Pembayaran Final
                            </h4>
                            
                            <form action="{{ route('admin.reservations.record-full-payment', $reservation->id) }}" 
                                method="POST" 
                                onsubmit="return confirm('Konfirmasi: Reservasi #{{ $reservation->id }} sudah bayar lunas?\n\nMeja {{ $reservation->table->number }} akan dikosongkan.\nTotal: Rp {{ number_format($reservation->remaining_payment, 0, ',', '.') }}')">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-green-100 text-green-700 py-3 rounded-lg font-medium hover:bg-green-200 transition-colors flex items-center justify-center mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Tandai Sudah Bayar Lunas
                                </button>
                            </form>
                            
                            <div class="text-center text-xs text-gray-500 bg-gray-50 rounded-lg p-2">
                                <div class="font-medium">Detail Pembayaran:</div>
                                <div>DP: Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</div>
                                <div>Sisa: Rp {{ number_format($reservation->remaining_payment, 0, ',', '.') }}</div>
                                <div class="font-bold mt-1">Total: Rp {{ number_format($totalAfterDiscount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endif --}}

                        <!-- Pembayaran Final Section -->
                        @if(in_array($reservation->status, ['confirmed', 'pending']) && $reservation->remaining_payment > 0 && !$reservation->is_fully_paid)
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h4 class="font-bold mb-3 flex items-center text-gray-800">
                                <i class="fas fa-cash-register text-green-500 mr-2"></i>
                                Pembayaran Final
                            </h4>
                            
                            <form action="{{ route('admin.reservations.record-full-payment', $reservation->id) }}" 
                                method="POST" 
                                onsubmit="return confirm('Konfirmasi: Reservasi #{{ $reservation->id }} sudah bayar lunas?')">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-green-100 text-green-700 py-3 rounded-lg font-medium hover:bg-green-200 transition-colors flex items-center justify-center mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Tandai Sudah Bayar Lunas
                                </button>
                            </form>
                        </div>
                        @endif

                        @if($reservation->is_fully_paid)
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                                <p class="font-bold text-green-800 text-lg">LUNAS</p>
                                <p class="text-sm text-green-600 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Dibayar pada: {{ $reservation->fully_paid_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        {{-- @if($reservation->is_fully_paid)
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                                <p class="font-bold text-green-800 text-lg">LUNAS</p>
                                <p class="text-sm text-green-600 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Dibayar pada: {{ $reservation->fully_paid_at->format('d M Y H:i') }}
                                </p>
                                <p class="text-xs text-green-500 mt-2">
                                    <i class="fas fa-table mr-1"></i>
                                    Meja {{ $reservation->table->number }} sudah dikosongkan
                                </p>
                            </div>
                        </div>
                        @endif --}}

                        <!-- Status Lunas/Belum - VERSI SIMPLE -->
                        @if($reservation->is_fully_paid)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2 text-center">
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <strong>LUNAS</strong>
                                    @if($reservation->fully_paid_at)
                                        - Dibayar pada: {{ $reservation->fully_paid_at->format('d M Y H:i') }}
                                    @endif
                                </p>
                            </div>
                        @else
                            <!-- Tampilkan sisa pembayaran hanya jika belum lunas -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2 text-center">
                                <p class="text-sm text-yellow-700">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Belum lunas. Sisa bayar: <strong>Rp {{ number_format($reservation->remaining_payment, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        @endif
                        
                        <!-- Hapus Reservasi -->
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

        <!-- Debug Info (Optional) -->
        {{-- @if(env('APP_DEBUG'))
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mt-6">
            <h4 class="font-bold mb-2 text-gray-700">Debug Info:</h4>
            <div class="text-xs font-mono space-y-1">
                <div>Subtotal (before discount): Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                <div>Total from DB (after discount): Rp {{ number_format($totalAfterDiscount, 0, ',', '.') }}</div>
                <div>Actual Discount: Rp {{ number_format($actualDiscount, 0, ',', '.') }}</div>
                <div>DP Paid: Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</div>
                <div>Remaining: Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                @if($reservation->promo)
                <div>Promo: {{ $reservation->promo->name }} ({{ $reservation->promo->discount }}%)</div>
                @endif
            </div>
        </div>
        @endif --}}
    </div>

    <!-- Modal untuk gambar bukti pembayaran -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-50 p-4">
        <div class="max-w-4xl max-h-full">
            <div class="relative">
                <button type="button" 
                        onclick="closeImageModal()"
                        class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
                <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-screen object-contain rounded-lg">
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .btn-primary {
        @apply bg-primary text-white py-2 px-4 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center;
    }
    
    .btn-secondary {
        @apply bg-gray-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-gray-700 transition-colors flex items-center;
    }
</style>
@endsection

@section('scripts')
<script>
    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Modal Tambah Pesanan
    function openAddMenuModal() {
        document.getElementById('addMenuModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updatePricePreview();
    }

    function closeAddMenuModal() {
        document.getElementById('addMenuModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Update price preview
    function updatePricePreview() {
        const menuSelect = document.querySelector('select[name="menu_id"]');
        const quantityInput = document.querySelector('input[name="quantity"]');
        const pricePreview = document.getElementById('pricePreview');
        const subtotalPreview = document.getElementById('subtotalPreview');
        
        const selectedOption = menuSelect.options[menuSelect.selectedIndex];
        const price = selectedOption ? selectedOption.getAttribute('data-price') : 0;
        const quantity = quantityInput ? quantityInput.value : 1;
        
        pricePreview.textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
        subtotalPreview.textContent = 'Rp ' + (price * quantity).toLocaleString('id-ID');
    }

    // Auto submit quantity form on Enter
    function setupQuantityForms() {
        document.querySelectorAll('input[name="quantity"]').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.closest('form').submit();
                }
            });
            
            // Auto select text when focused
            input.addEventListener('focus', function() {
                this.select();
            });
        });
    }

    // Event listeners untuk update preview
    document.addEventListener('DOMContentLoaded', function() {
        const menuSelect = document.querySelector('select[name="menu_id"]');
        const quantityInput = document.querySelector('input[name="quantity"]');
        
        if (menuSelect) {
            menuSelect.addEventListener('change', updatePricePreview);
        }
        if (quantityInput) {
            quantityInput.addEventListener('input', updatePricePreview);
        }

        // Setup quantity forms
        setupQuantityForms();

        // Close modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('imageModal').classList.contains('hidden')) {
                    closeImageModal();
                }
                if (!document.getElementById('addMenuModal').classList.contains('hidden')) {
                    closeAddMenuModal();
                }
            }
        });

        // Close modal ketika klik di luar
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        document.getElementById('addMenuModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddMenuModal();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Show success message if exists
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    });

    function showToast(message, type = 'success') {
        // Simple toast implementation
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg text-white font-medium z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endsection