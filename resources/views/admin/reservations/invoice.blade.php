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
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <!-- Header Invoice -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                <p class="text-gray-600">Restaurant IBC Dine+</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-primary">#{{ $reservation->id }}</h2>
                <p class="text-gray-600">{{ $reservation->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Informasi Customer & Reservasi -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="font-bold text-gray-700 mb-2">Informasi Customer</h3>
                <p class="text-gray-900 font-medium">{{ $reservation->user->name }}</p>
                <p class="text-gray-600">{{ $reservation->user->email }}</p>
            </div>
            <div>
                <h3 class="font-bold text-gray-700 mb-2">Detail Reservasi</h3>
                <p class="text-gray-900">Meja {{ $reservation->table->number }}</p>
                <p class="text-gray-600">{{ $reservation->reservation_date->format('d M Y') }} - {{ $reservation->formatted_time }}</p>
                <p class="text-gray-600">{{ $reservation->guest_count }} orang</p>
            </div>
        </div>

        <!-- Daftar Pesanan -->
        <div class="mb-8">
            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Detail Pesanan</h3>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left p-3 font-medium text-gray-700">Menu</th>
                        <th class="text-right p-3 font-medium text-gray-700">Harga</th>
                        <th class="text-right p-3 font-medium text-gray-700">Qty</th>
                        <th class="text-right p-3 font-medium text-gray-700">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if($reservation->order && $reservation->order->orderItems->count() > 0)
                        @foreach($reservation->order->orderItems as $item)
                            <tr class="border-b">
                                <td class="p-3 text-gray-900">{{ $item->menu->name }}</td>
                                <td class="p-3 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-right text-gray-600">{{ $item->qty }}</td>
                                <td class="p-3 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="p-3 text-center text-gray-500">Tidak ada pesanan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h3 class="font-bold text-gray-700 mb-4">Ringkasan Pembayaran</h3>
            <div class="space-y-2">
                @if($reservation->order)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($reservation->promo)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon ({{ $reservation->promo->promo_code }}):</span>
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
                    
                    <div class="flex justify-between border-t pt-2">
                        <span class="font-bold">Total Tagihan:</span>
                        <span class="font-bold text-lg">Rp {{ number_format($reservation->order->total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-orange-600">
                        <span>DP Dibayar:</span>
                        <span class="font-medium">-Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between border-t pt-2">
                        <span class="font-bold">Sisa Pembayaran:</span>
                        <span class="font-bold text-lg 
                            @if($reservation->order->total_price - $reservation->total_DP <= 0) text-green-600 @else text-red-600 @endif">
                            Rp {{ number_format($reservation->order->total_price - $reservation->total_DP, 0, ',', '.') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t pt-6 text-center text-gray-500">
            <p>Terima kasih atas kunjungan Anda di IBC Dine+</p>
            <p class="text-sm">Invoice ini sah dan dapat digunakan sebagai bukti pembayaran</p>
        </div>

        <!-- Tombol Print -->
        <div class="no-print mt-6 text-center">
            <button onclick="window.print()" 
                    class="bg-primary text-white py-2 px-6 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center justify-center mx-auto">
                <i class="fas fa-print mr-2"></i> Cetak Invoice
            </button>
            <button onclick="window.close()" 
                    class="bg-gray-500 text-white py-2 px-6 rounded-lg font-medium hover:bg-gray-600 transition-colors flex items-center justify-center mx-auto mt-2">
                <i class="fas fa-times mr-2"></i> Tutup
            </button>
        </div>
    </div>

    <script>
        // Auto print ketika halaman loaded (opsional)
        window.onload = function() {
            // setTimeout(() => {
            //     window.print();
            // }, 1000);
        };
    </script>
</body>
</html>