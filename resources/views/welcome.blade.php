@extends('layouts.app')

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
                <a href="/login" class="gold-bg text-white px-8 py-3 rounded-md font-medium hover:bg-opacity-90">
                    LOGIN UNTUK MEMESAN
                </a>
                <a href="/register" class="bg-white text-gray-800 px-8 py-3 rounded-md font-medium hover:bg-gray-100">
                    BUAT AKUN BARU
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
                {{-- <p class="mt-4 text-gray-600">Login terlebih dahulu untuk melakukan pemesanan</p> --}}
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Menu 1 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300">
                    <div class="h-72 bg-cover bg-center" 
                            style="background-image: url('/images/menus/gurame_bakar.avif')">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Gurame Bakar</h3>
                        <p class="text-gray-600 mb-4">Gurame bakar berbumbu manis gurih khas Jawa</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold gold-text">Rp 65.000</span>
                        </div>
                    </div>
                </div>
                
                <!-- Menu 2 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300">
                    <div class="h-72 bg-cover bg-center" 
                            style="background-image: url('/images/menus/gurame_asam_manis.avif')">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Gurame Asam Manis</h3>
                        <p class="text-gray-600 mb-4">Gurame goreng renyah disiram saus asam manis segar</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold gold-text">Rp 75.000</span>
                        </div>
                    </div>
                </div>
                
                <!-- Menu 3 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300 flex flex-col h-full">
                    <div class="h-72 bg-cover bg-center" style="background-image: url('/images/menus/gurame_goreng.avif')"></div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="font-bold text-xl mb-2">Gurame Goreng</h3>
                        <p class="text-gray-600 mb-4">Dengan bumbu khas racikan spesial IBC</p>
                        <div class="flex justify-between items-center mt-auto">
                            <span class="font-bold gold-text">Rp 65.000</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="/login" class="inline-block gold-border border-2 gold-text px-8 py-3 rounded-md font-medium hover:gold-bg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    LOGIN UNTUK MELIHAT MENU LENGKAP
                </a>
            </div>
        </div>
    </section>

    <!-- Promo Section -->
    <!-- Promo Section -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">PROMO SPESIAL MEMBER</h2>
            <div class="w-20 h-1 gold-bg mx-auto"></div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($activePromos as $promo)
            <div class="bg-white rounded-lg overflow-hidden shadow-lg promo-card transition duration-300 transform hover:scale-105 cursor-pointer">
                <div class="h-60 gold-bg flex items-center justify-center relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="w-full h-full" style="background-image: url('data:image/svg+xml,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><g fill=\"%23ffffff\" fill-opacity=\"1\"><circle cx=\"30\" cy=\"30\" r=\"4\"/></g></svg>');"></div>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="text-center z-10 promo-content">
                        <div class="text-white text-3xl font-bold mb-2">{{ $promo->promo_code }}</div>
                        <div class="text-white text-lg font-semibold bg-black bg-opacity-30 px-4 py-1 rounded-full">
                            {{ $promo->discount }}{{ $promo->type == 'percentage' ? '%' : 'K' }} OFF
                        </div>
                    </div>
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 flex items-center justify-center transition duration-300 opacity-0 hover:bg-opacity-40 hover:opacity-100 z-20">
                        <span class="text-white text-lg font-semibold bg-gold bg-opacity-90 px-4 py-2 rounded transform scale-95 transition duration-300 hover:scale-100 z-30">
                            LOGIN UNTUK CLAIM
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-xl mb-2">{{ $promo->description }}</h3>
                    <p class="text-gray-600 mb-2">
                        Diskon {{ $promo->discount }}{{ $promo->type == 'percentage' ? '%' : 'K' }} 
                        untuk member
                    </p>
                    <p class="text-sm text-gray-500">
                        Berlaku hingga {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="/register" class="inline-block gold-border border-2 gold-text px-8 py-3 rounded-md font-medium hover:gold-bg  transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                REGISTER UNTUK MENJADI MEMBER
            </a>
        </div>
    </div>
</section>

<style>
    .promo-card {
        transition: all 0.3s ease;
    }
    
    .promo-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .promo-card .absolute {
        transition: all 0.3s ease;
    }
    .promo-card:hover .promo-content {
        opacity: 0.3;
        transition: opacity 0.3s ease;
    }
</style>
@endsection