@extends('layouts.customer.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl mt-12 mb-24">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center border border-gray-100">
        <div class="text-4xl text-green-500 mb-4">✅</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Reservasi Berhasil!</h1>
        <p class="text-gray-600 mb-6">Terima kasih <strong>{{ $reservation->name }}</strong>, reservasi kamu telah kami terima.</p>

        <div class="text-left text-sm bg-gray-50 border rounded-md p-4 mb-6">
            <h2 class="font-semibold mb-2 text-gray-700 border-b pb-1">Detail Reservasi</h2>
            <ul class="space-y-1">
                <li><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($reservation->reservation_date)->translatedFormat('l, d F Y') }}</li>
                <li><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</li>
                <li><strong>Jumlah Tamu:</strong> {{ $reservation->guest_count }}</li>
                <li><strong>Meja:</strong> Meja {{ $reservation->table->number ?? '-' }}</li>
                <li><strong>Email:</strong> {{ $reservation->email }}</li>
                <li><strong>Telepon:</strong> {{ $reservation->phone }}</li>
                <li><strong>Catatan:</strong> {{ $reservation->notes ?? '-' }}</li>
            </ul>
        </div>

        {{-- @if($reservation->menus && $reservation->menus->count())
        <div class="text-left text-sm bg-gray-50 border rounded-md p-4 mb-6">
            <h2 class="font-semibold mb-2 text-gray-700 border-b pb-1">Pesanan Menu</h2>
            <ul class="divide-y">
                @foreach($reservation->menus as $menu)
                <li class="py-2 flex justify-between">
                    <span>{{ $menu->name }} × {{ $menu->pivot->quantity }}</span>
                    <span class="text-yellow-600 font-medium">Rp {{ number_format($menu->price * $menu->pivot->quantity, 0, ',', '.') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif --}}

        <a href="{{ route('homepage') }}"
           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md font-medium transition">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
