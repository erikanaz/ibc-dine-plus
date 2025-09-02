<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - IBC Dine+</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .register-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/pizza-bg.jpg');
            background-size: cover;
            background-position: center;
        }
        .btn-register {
            background-color: #D4AF37;
            color: white;
        }
        .btn-register:hover {
            background-color: #ad9651;
        }
    </style>
</head>
    <body class="font-sans antialiased register-bg h-screen flex items-start justify-center py-10 overflow-y-auto">

        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8 w-full max-w-md">
            <div class="flex flex-col items-center mb-6 sticky top-0 bg-white pt-2 z-10">
                <img src="/images/logoibc.png" alt="Logo IBC Dine+" class="h-12 mb-2">
                <h2 class="text-center text-xl md:text-2xl font-bold text-gray-800">Daftar Akun Baru</h2>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="space-y-3">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">+62</span>
                            <input id="phone" type="tel" name="whatsapp" value="{{ old('phone') }}" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-orange-400"
                                placeholder="81234567890">
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full py-2 rounded-md btn-register font-semibold">Daftar</button>
                </div>

                <div class="text-center text-sm mt-4">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login di sini</a>
                </div>
            </form>
        </div>
    </body>
</html>