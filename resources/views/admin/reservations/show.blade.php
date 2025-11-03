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
                    {{-- TOMBOL EDIT DIHAPUS UNTUK ADMIN --}}
                    
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
                        {{-- TOMBOL TAMBAH MENU DIHAPUS UNTUK ADMIN --}}
                    </div>
                    
                    @php
                        $order = $reservation->orders->first();
                    @endphp
                    
                    @if($order && $order->orderItems->count() > 0)
                        <div class="space-y-3">
                            @foreach($order->orderItems as $item)
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
                                                Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        
                                        {{-- TOMBOL EDIT/HAPUS MENU DIHAPUS UNTUK ADMIN --}}
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
                            @if($order)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Pesanan:</span>
                                    <span class="font-medium">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                @if($reservation->promo)
                                <div class="flex justify-between text-green-600">
                                    <span>Diskon Promo:</span>
                                    <span class="font-medium">
                                        -Rp 
                                        @if($reservation->promo->type == 'percent')
                                            {{ number_format(($order->total_price * $reservation->promo->discount / 100), 0, ',', '.') }}
                                        @else
                                            {{ number_format($reservation->promo->discount, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <span class="font-medium">Total Setelah Diskon:</span>
                                    <span class="font-medium">
                                        @php
                                            $discountedTotal = $reservation->promo->type == 'percent' 
                                                ? $order->total_price * (1 - $reservation->promo->discount / 100)
                                                : max(0, $order->total_price - $reservation->promo->discount);
                                        @endphp
                                        Rp {{ number_format($discountedTotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between text-orange-600 border-t border-gray-200 pt-2">
                                    <span>DP Dibayar:</span>
                                    <span class="font-medium">-Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between border-t border-gray-200 pt-2 font-bold">
                                    @php
                                        $finalTotal = $discountedTotal ?? $order->total_price;
                                        $remaining = $finalTotal - $reservation->total_DP;
                                    @endphp
                                    <span>Sisa Pembayaran:</span>
                                    <span class="text-lg 
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

                                    <!-- Catatan -->
                                    @if($payment->notes)
                                    <div class="p-2 bg-blue-50 rounded border border-blue-200">
                                        <p class="text-xs text-blue-700">{{ $payment->notes }}</p>
                                    </div>
                                    @endif

                                    <!-- Admin Actions untuk Payment -->
                                    @if($payment->status === 'pending')
                                    <div class="flex space-x-2 mt-3 pt-3 border-t border-gray-200">
                                        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full bg-green-100 text-green-700 py-2 px-3 rounded text-sm font-medium hover:bg-green-200 transition-colors flex items-center justify-center">
                                                <i class="fas fa-check mr-2"></i> Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Yakin ingin menolak pembayaran ini?')"
                                                    class="w-full bg-red-100 text-red-700 py-2 px-3 rounded text-sm font-medium hover:bg-red-200 transition-colors flex items-center justify-center">
                                                <i class="fas fa-times mr-2"></i> Tolak
                                            </button>
                                        </form>
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

                <!-- Informasi Sistem -->
                {{-- <div class="bg-white rounded-xl shadow p-6">
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
                </div> --}}

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

    // Close modal dengan ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // Close modal ketika klik di luar gambar
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
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