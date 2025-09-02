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
                <a href="/reservasi" class="gold-bg text-white px-8 py-3 rounded-md font-medium hover:bg-opacity-90">
                    RESERVASI MEJA
                </a>
                <a href="/pemesanan" class="bg-white text-gray-800 px-8 py-3 rounded-md font-medium hover:bg-gray-100">
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
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Menu 1 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300">
                    <div class="h-60 bg-cover bg-center" 
                            style="background-image: url('/images/gurame_bakar.avif')">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Gurame Bakar</h3>
                        <p class="text-gray-600 mb-4">Gurame bakar berbumbu manis gurih khas Jawa</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold gold-text">Rp 65.000</span>
                            <button class="gold-bg text-white px-4 py-2 rounded-md text-sm hover:bg-opacity-90">
                                + Pesan
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Menu 2 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300">
                    <div class="h-60 bg-cover bg-center" 
                            style="background-image: url('/images/gurame_asam_manis.avif')">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Gurame Asam Manis</h3>
                        <p class="text-gray-600 mb-4">Gurame goreng renyah disiram saus asam manis segar</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold gold-text">Rp 75.000</span>
                            <button class="gold-bg text-white px-4 py-2 rounded-md text-sm hover:bg-opacity-90">
                                + Pesan
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Menu 3 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300 flex flex-col h-full">
                    <div class="h-60 bg-cover bg-center" style="background-image: url('/images/gurame_goreng.avif')"></div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="font-bold text-xl mb-2">Gurame Goreng</h3>
                        <p class="text-gray-600 mb-4">Dengan bumbu khas racikan spesial IBC</p>
                        <div class="flex justify-between items-center mt-auto">
                            <span class="font-bold gold-text">Rp 65.000</span>
                            <button class="gold-bg text-white px-4 py-2 rounded-md text-sm hover:bg-opacity-90">
                                + Pesan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
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
            
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Promo 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <div class="h-48 gold-bg flex items-center justify-center">
                        <span class="text-white text-xl font-bold">DISKON 20%</span>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Setiap Hari Senin</h3>
                        <p class="text-gray-600 mb-4">Untuk semua menu ikan bakar</p>
                        <a href="#" class="gold-text font-medium hover:underline">Syarat & Ketentuan</a>
                    </div>
                </div>
                
                <!-- Promo 2 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <div class="h-48 gold-bg flex items-center justify-center">
                        <span class="text-white text-xl font-bold">PAKET KELUARGA</span>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Untuk 4 Orang</h3>
                        <p class="text-gray-600 mb-4">Free 1 minuman segar</p>
                        <a href="#" class="gold-text font-medium hover:underline">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection