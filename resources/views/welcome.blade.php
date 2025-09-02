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
                <p class="mt-4 text-gray-600">Login terlebih dahulu untuk melakukan pemesanan</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Menu 1 -->
                <div class="login-required bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300">
                     <div class="h-60 bg-cover bg-center" 
                        style="background-image: url('/images/gurame_bakar.avif')">
                     </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Gurame Bakar</h3>
                        <p class="text-gray-600 mb-4">Gurame bakar berbumbu manis gurih khas Jawa</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold gold-text">Rp 65.000</span>
                            <button class="gold-bg text-white px-4 py-2 rounded-md text-sm hover:bg-opacity-90 cursor-not-allowed opacity-50">
                                + Pesan
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Menu 2 -->
                <div class="login-required bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300">
                    <div class="h-60 bg-cover bg-center" 
                        style="background-image: url('/images/gurame_asam_manis.avif')">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Gurame Asam Manis</h3>
                        <p class="text-gray-600 mb-4">Gurame goreng renyah disiram saus asam manis segar</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold gold-text">Rp 75.000</span>
                            <button class="gold-bg text-white px-4 py-2 rounded-md text-sm hover:bg-opacity-90 cursor-not-allowed opacity-50">
                                + Pesan
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Menu 3 -->
                <div class="login-required bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300 flex flex-col h-full">
                    <div class="h-60 bg-cover bg-center" 
                        style="background-image: url('/images/gurame_goreng.avif')">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="font-bold text-xl mb-2">Gurame Goreng</h3>
                        <p class="text-gray-600 mb-4">Dengan bumbu khas racikan spesial IBC</p>
                        <div class="flex justify-between items-center mt-auto">
                            <span class="font-bold gold-text">Rp 55.000</span>
                            <button class="gold-bg text-white px-4 py-2 rounded-md text-sm hover:bg-opacity-90 cursor-not-allowed opacity-50">
                                + Pesan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reservation Info -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <h2 class="text-3xl font-bold mb-4">INGIN MELAKUKAN RESERVASI?</h2>
                <div class="w-20 h-1 gold-bg mx-auto mb-6"></div>
                <p class="text-gray-600 mb-8">
                    Silakan login atau daftar terlebih dahulu untuk melakukan reservasi meja di restoran kami
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/login" class="gold-bg text-white px-8 py-3 rounded-md font-medium hover:bg-opacity-90">
                        LOGIN SEKARANG
                    </a>
                    <a href="/register" class="gold-border border-2 gold-text px-8 py-3 rounded-md font-medium hover:gold-bg hover:text-white">
                        BUAT AKUN BARU
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection