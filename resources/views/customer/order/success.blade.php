@extends('layouts.customer.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md text-center">
        <svg class="h-16 w-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <h2 class="text-2xl font-bold mb-2">Pesanan Berhasil!</h2>
        {{-- <p class="mb-4">Nomor Pesanan: {{ $order->order_code }}</p> --}}
        
        @if($order->payment_method === 'cash')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left">
            <p class="font-medium">Silakan lakukan pembayaran saat:</p>
            <ul class="list-disc pl-5 mt-2">
                <li>Pesanan diantar (untuk takeaway)</li>
                <li>Selesai makan (untuk dine-in)</li>
            </ul>
        </div>
        @endif

        <div class="border-t pt-4">
            <p class="font-semibold">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-2">Metode Pembayaran: 
                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
            </p>
        </div>

        <a href="{{ route('order.index') }}" 
           class="mt-6 inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg transition">
            Kembali ke Menu
        </a>
    </div>
</div>
@endsection