<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $reservation->id }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
        .watermark {
            position: absolute;
            opacity: 0.1;
            font-size: 120px;
            transform: rotate(-45deg);
            top: 40%;
            left: 10%;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Print Controls -->
    <div class="no-print fixed top-4 right-4 z-50">
        <div class="flex space-x-2">
            <button onclick="window.print()" 
                    class="bg-primary text-white px-4 py-2 rounded-lg shadow hover:bg-primary/90 transition-colors flex items-center">
                <i class="fas fa-print mr-2"></i> Cetak
            </button>
            <button onclick="window.close()" 
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 transition-colors flex items-center">
                <i class="fas fa-times mr-2"></i> Tutup
            </button>
        </div>
    </div>

    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Invoice Container -->
            <div class="bg-white rounded-xl shadow-lg invoice-container relative overflow-hidden">
                <!-- Watermark -->
                @if($reservation->status === 'paid')
                <div class="watermark text-green-500 font-bold">
                    LUNAS
                </div>
                @elseif($reservation->status === 'cancelled')
                <div class="watermark text-red-500 font-bold">
                    DIBATALKAN
                </div>
                @endif

                <!-- Header -->
                <div class="border-b border-gray-200 p-8">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                            {{-- <p class="text-gray-600 mt-2">{{ config('app.name') }}</p> --}}
                            <p class="text-gray-600 mt-2">IBC Dine+</p>
                            <p class="text-sm text-gray-500">Jl. Contoh No. 123, Jakarta</p>
                            <p class="text-sm text-gray-500">Telp: (021) 1234-5678</p>
                            {{-- <p class="text-gray-500 text-sm">Restoran & Cafe</p> --}}
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-primary">#{{ $reservation->id }}</div>
                            <p class="text-gray-600 mt-2">{{ now()->format('d M Y H:i') }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-2
                                @if($reservation->status === 'completed') bg-green-100 text-green-800
                                @elseif($reservation->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif(in_array($reservation->status, ['cancelled', 'expired'])) bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $reservation->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer & Reservation Info -->
                <div class="p-8 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Customer</h3>
                            <div class="space-y-2">
                                <p><strong>Nama:</strong> {{ $reservation->customer_name }}</p>
                                <p><strong>Email:</strong> {{ $reservation->customer_email }}</p>
                                @if($reservation->customer_phone)
                                <p><strong>Telepon:</strong> {{ $reservation->customer_phone }}</p>
                                @endif
                                <p><strong>Tipe:</strong> 
                                    @if($reservation->user_id)
                                        <span class="text-green-600">Member</span>
                                    @else
                                        <span class="text-gray-600">Guest</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Reservasi</h3>
                            <div class="space-y-2">
                                <p><strong>Tanggal:</strong> {{ $reservation->reservation_date->format('d M Y') }}</p>
                                <p><strong>Waktu:</strong> {{ $reservation->formatted_time }}</p>
                                <p><strong>Meja:</strong> Meja {{ $reservation->table->number }} ({{ $reservation->table->capacity }} orang)</p>
                                <p><strong>Jumlah Tamu:</strong> {{ $reservation->guest_count }} orang</p>
                                @if($reservation->promo)
                                <p><strong>Promo:</strong> {{ $reservation->promo->promo_code }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                @php
                    $order = $reservation->orders->first();
                @endphp
                
                @if($order && $order->orderItems->count() > 0)
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Pesanan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 font-semibold text-gray-600">Menu</th>
                                    <th class="text-right py-3 font-semibold text-gray-600">Harga</th>
                                    <th class="text-right py-3 font-semibold text-gray-600">Qty</th>
                                    <th class="text-right py-3 font-semibold text-gray-600">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr class="border-b border-gray-100">
                                    <td class="py-3">
                                        <div class="font-medium text-gray-900">{{ $item->menu->name }}</div>
                                        @if($item->menu->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($item->menu->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right text-gray-900">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-right text-gray-900">
                                        {{ $item->qty }}
                                    </td>
                                    <td class="py-3 text-right font-semibold text-gray-900">
                                        Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Payment Summary -->
                <div class="p-8">
                    <div class="max-w-md ml-auto">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h3>
                        <div class="space-y-3">
                            @if($order)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            @if($reservation->promo)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon ({{ $reservation->promo->promo_code }}):</span>
                                <span class="font-medium">
                                    -Rp 
                                    @if($reservation->promo->type == 'percent')
                                        {{ number_format(($order->total_price * $reservation->promo->discount / 100), 0, ',', '.') }}
                                    @else
                                        {{ number_format($reservation->promo->discount, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>

                            @php
                                $discountedTotal = $reservation->promo->type == 'percent' 
                                    ? $order->total_price * (1 - $reservation->promo->discount / 100)
                                    : max(0, $order->total_price - $reservation->promo->discount);
                            @endphp

                            <div class="flex justify-between border-t border-gray-200 pt-2">
                                <span class="font-medium">Total Setelah Diskon:</span>
                                <span class="font-medium">Rp {{ number_format($discountedTotal, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between text-orange-600 border-t border-gray-200 pt-2">
                                <span>DP Dibayar:</span>
                                <span class="font-medium">-Rp {{ number_format($reservation->total_DP, 0, ',', '.') }}</span>
                            </div>

                            @php
                                $finalTotal = $discountedTotal ?? $order->total_price;
                                $remaining = $finalTotal - $reservation->total_DP;
                            @endphp

                            <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-lg">
                                <span>Sisa Pembayaran:</span>
                                <span class="@if($remaining <= 0) text-green-600 @else text-red-600 @endif">
                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($remaining <= 0)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-3">
                                <div class="flex items-center text-green-800 justify-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="font-semibold">LUNAS</span>
                                </div>
                            </div>
                            @endif
                            @else
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-shopping-cart text-2xl mb-2"></i>
                                <p>Belum ada pesanan</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 p-8 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Catatan:</h4>
                            <p class="text-sm text-gray-600">
                                @if($reservation->notes)
                                    {{ $reservation->notes }}
                                @else
                                    Terima kasih atas reservasi Anda. Silakan datang tepat waktu.
                                @endif
                            </p>
                        </div>
                        {{-- <div class="text-right">
                            <h4 class="font-semibold text-gray-800 mb-2">{{ config('app.name') }}</h4>
                            <p class="text-sm text-gray-600">
                                Jl. Contoh No. 123, Kota<br>
                                Phone: (021) 1234-5678<br>
                                Email: info@restaurant.com
                            </p>
                        </div> --}}
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                        <p class="text-xs text-gray-500">
                            Invoice ini dibuat otomatis pada {{ $reservation->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($remaining > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Instruksi Pembayaran
                </h3>
                <div class="space-y-2 text-yellow-700">
                    <p>• Sisa pembayaran sebesar <strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong> dapat dibayar saat datang</p>
                    <p>• Silakan tunjukkan invoice ini kepada staff</p>
                    <p>• Pembayaran dapat dilakukan via cash atau transfer</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Auto print jika diinginkan
        @if(request('auto_print'))
        window.onload = function() {
            window.print();
        }
        @endif

        // Close window setelah print
        window.onafterprint = function() {
            // Optional: auto close setelah print
            // window.close();
        };
    </script>
</body>
</html>