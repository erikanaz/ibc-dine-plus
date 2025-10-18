<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Reservasi #{{ $reservation->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 20px;
                background: white !important;
            }
            .print-break {
                page-break-inside: avoid;
            }
        }
        
        .text-primary { color: #3b82f6; }
        .bg-primary { background-color: #3b82f6; }
        .border-primary { border-color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8 print:shadow-none">
        <!-- Header Invoice -->
        <div class="flex justify-between items-start mb-8 print-break">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                <p class="text-gray-600">Restaurant IBC Dine+</p>
                <p class="text-sm text-gray-500">Jl. Contoh No. 123, Jakarta</p>
                <p class="text-sm text-gray-500">Telp: (021) 1234-5678</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-primary">#{{ $reservation->id }}</h2>
                <p class="text-gray-600">{{ $reservation->created_at->format('d M Y H:i') }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    Status: 
                    <span class="font-medium 
                        @if($reservation->status === 'confirmed') text-green-600
                        @elseif($reservation->status === 'completed') text-blue-600
                        @elseif($reservation->status === 'cancelled') text-red-600
                        @else text-yellow-600 @endif">
                        {{ $reservation->status_label }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Informasi Customer & Reservasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 print-break">
            <div>
                <h3 class="font-bold text-gray-700 mb-3 border-b pb-1">Informasi Customer</h3>
                <p class="text-gray-900 font-medium text-lg">{{ $reservation->customer_name }}</p>
                <p class="text-gray-600">{{ $reservation->customer_email }}</p>
                <p class="text-gray-600">{{ $reservation->customer_phone }}</p>
                @if($reservation->user_id)
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-user-check"></i> Customer Terdaftar
                    </p>
                @else
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-user"></i> Guest Customer
                    </p>
                @endif
            </div>
            <div>
                <h3 class="font-bold text-gray-700 mb-3 border-b pb-1">Detail Reservasi</h3>
                <p class="text-gray-900 font-medium">Meja {{ $reservation->table->number }}</p>
                <p class="text-gray-600">{{ $reservation->table->location_label }}</p>
                <p class="text-gray-600">{{ $reservation->reservation_date->format('d M Y') }} - {{ $reservation->formatted_time }}</p>
                <p class="text-gray-600">{{ $reservation->guest_count }} orang</p>
                @if($reservation->promo)
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-tag"></i> Promo: {{ $reservation->promo->promo_code }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Daftar Pesanan -->
        <div class="mb-8 print-break">
            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Detail Pesanan</h3>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left p-3 font-medium text-gray-700 border-b">Menu</th>
                        <th class="text-right p-3 font-medium text-gray-700 border-b">Harga</th>
                        <th class="text-right p-3 font-medium text-gray-700 border-b">Qty</th>
                        <th class="text-right p-3 font-medium text-gray-700 border-b">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if($reservation->order && $reservation->order->orderItems->count() > 0)
                        @foreach($reservation->order->orderItems as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-gray-900">
                                    <div>
                                        <p class="font-medium">{{ $item->menu->name }}</p>
                                        @if($item->menu->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($item->menu->description, 50) }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-right text-gray-600">{{ $item->qty }}</td>
                                <td class="p-3 text-right font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500 bg-gray-50">
                                <i class="fas fa-shopping-cart text-2xl mb-2 block text-gray-300"></i>
                                Tidak ada pesanan
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 print-break border border-gray-200">
            <h3 class="font-bold text-gray-700 mb-4 text-lg">Ringkasan Pembayaran</h3>
            <div class="space-y-3">
                @if($reservation->order)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal Pesanan:</span>
                        <span class="font-medium">Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($reservation->promo)
                    <div class="flex justify-between items-center text-green-600">
                        <span>Diskon Promo ({{ $reservation->promo->promo_code }}):</span>
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
                    
                    <div class="flex justify-between items-center border-t border-gray-300 pt-3 mt-2">
                        <span class="font-bold text-lg">Total Tagihan:</span>
                        <span class="font-bold text-xl text-primary">Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-orange-600 border-b border-gray-300 pb-3">
                        <span class="font-medium">DP Dibayar:</span>
                        <span class="font-medium">-Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                    </div>
                    
                    @php
                        $remaining = $reservation->order->total_price - $reservation->total_DP;
                    @endphp
                    <div class="flex justify-between items-center pt-3">
                        <span class="font-bold text-lg">Sisa Pembayaran:</span>
                        <span class="font-bold text-xl 
                            @if($remaining <= 0) text-green-600 
                            @else text-red-600 @endif">
                            Rp {{ number_format($remaining, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($remaining <= 0)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-3 text-center">
                            <div class="flex items-center justify-center text-green-800">
                                <i class="fas fa-check-circle mr-2 text-lg"></i>
                                <span class="font-medium">LUNAS</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-3 text-center">
                            <div class="flex items-center justify-center text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-medium">BELUM LUNAS</span>
                            </div>
                            <p class="text-sm text-yellow-700 mt-1">Selesaikan pembayaran saat kedatangan</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-receipt text-2xl mb-2 block text-gray-300"></i>
                        Tidak ada data pesanan
                    </div>
                @endif
            </div>
        </div>

        @if($reservation->notes)
        <!-- Catatan Khusus -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8 print-break">
            <h4 class="font-bold text-yellow-800 mb-2 flex items-center">
                <i class="fas fa-sticky-note mr-2"></i> Catatan Khusus
            </h4>
            <p class="text-yellow-700 text-sm">{{ $reservation->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="border-t border-gray-300 pt-6 text-center text-gray-500 print-break">
            <div class="mb-4">
                <p class="font-medium text-gray-700">Terima kasih atas kunjungan Anda di IBC Dine+</p>
                <p class="text-sm mt-1">Invoice ini sah dan dapat digunakan sebagai bukti pembayaran</p>
            </div>
            <div class="text-xs text-gray-400 mt-4">
                <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
                <p>Staff: {{ Auth::user()->name }}</p>
            </div>
        </div>

        <!-- Tombol Print -->
        <div class="no-print mt-8 text-center space-y-3">
            <button onclick="window.print()" 
                    class="bg-primary text-white py-3 px-8 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center justify-center mx-auto shadow-lg">
                <i class="fas fa-print mr-2"></i> Cetak Invoice
            </button>
            <button onclick="window.close()" 
                    class="bg-gray-500 text-white py-2 px-6 rounded-lg font-medium hover:bg-gray-600 transition-colors flex items-center justify-center mx-auto">
                <i class="fas fa-times mr-2"></i> Tutup Window
            </button>
            <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
               class="inline-block bg-green-500 text-white py-2 px-6 rounded-lg font-medium hover:bg-green-600 transition-colors flex items-center justify-center mx-auto">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    <script>
        // Auto print ketika halaman loaded (opsional - bisa diaktifkan jika perlu)
        // window.onload = function() {
        //     setTimeout(() => {
        //         window.print();
        //     }, 500);
        // };

        // Keyboard shortcut untuk print (Ctrl + P)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });

        // Auto close setelah print (jika diinginkan)
        window.onafterprint = function() {
            // setTimeout(() => {
            //     window.close();
            // }, 1000);
        };
    </script>
</body>
</html>