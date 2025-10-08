@extends('layouts.customer.app')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-white py-32">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                IKAN BAKAR CIANJUR
                <br>
                <span class="gold-text"> BATU TULIS</span>
            </h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Nikmati cita rasa autentik ikan bakar khas Cianjur dengan bumbu rahasia turun temurun
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/reservation" class="gold-bg text-white px-8 py-3 rounded-md font-medium hover:bg-opacity-90">
                    RESERVASI MEJA
                </a>
                <a href="/order" class="bg-white text-gray-800 px-8 py-3 rounded-md font-medium hover:bg-gray-100">
                    LIHAT MENU
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Menu -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">MENU ANDALAN KAMI</h2>
                <div class="w-20 h-1 gold-bg mx-auto"></div>
            </div>
            
            @if($featuredMenus->count() > 0)
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($featuredMenus as $menu)
                        <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300 flex flex-col h-full">
                            <div class="h-80 bg-cover bg-center" 
                                 style="background-image: url('{{ $menu->image_url }}')">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="font-bold text-xl mb-2">{{ $menu->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ $menu->description }}</p>
                                <div class="flex justify-between items-center mt-auto">
                                    <span class="font-bold gold-text">{{ $menu->formatted_price }}</span>
                                    @if(!$menu->is_available)
                                        <span class="text-red-500 text-sm font-medium">Stok Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Tidak ada menu yang tersedia saat ini.</p>
                </div>
            @endif
            
            <div class="text-center mt-12">
                <a href="/order" class="inline-block gold-border border-2 gold-text px-8 py-3 rounded-md font-medium hover:gold-bg hover:text-white transition duration-300">
                    LIHAT MENU LENGKAP
                </a>
            </div>
        </div>
    </section>

    <!-- Promo Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">PROMO SPESIAL</h2>
                <div class="w-20 h-1 gold-bg mx-auto"></div>
            </div>
            
            @if($activePromos->count() > 0)
                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    @foreach($activePromos as $promo)
                        <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                            <div class="h-48 gold-bg flex items-center justify-center">
                                <span class="text-white text-xl font-bold text-center px-4">
                                    {{ $promo->type === 'percent' ? $promo->discount . '%' : 'Rp ' . number_format($promo->discount, 0, ',', '.') }}<br>
                                    DISKON
                                </span>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl mb-2">{{ $promo->promo_code }}</h3>
                                <p class="text-gray-600 mb-4">{{ $promo->description }}</p>
                                <div class="text-sm text-gray-500 mb-4">
                                    @if($promo->start_date && $promo->end_date)
                                        <p>Berlaku hingga: {{ $promo->end_date->format('d M Y') }}</p>
                                    @endif
                                    @if($promo->usage_limit)
                                        <p>Kuota: {{ $promo->usage_limit - $promo->used_count }} tersisa</p>
                                    @endif
                                </div>
                                <a href="#" class="gold-text font-medium hover:underline inline-flex items-center">
                                    Syarat & Ketentuan
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Tidak ada promo aktif saat ini.</p>
                </div>
            @endif
        </div>
    </section>
@endsection