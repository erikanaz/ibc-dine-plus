<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IBC Dine+</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .login-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/pizza-bg.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }
        .btn-login {
            background-color: #D4AF37;
            color: white;
        }
        .btn-login:hover {
            background-color: #ad9651;
        }
    </style>
</head>
<body class="font-sans antialiased login-bg flex items-center justify-center">

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <div class="flex items-center justify-center mb-6">
            <img src="/images/logoibc.png" alt="Logo IBC Dine+" class="h-12">
        </div>
        <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Masuk ke IBC Dine+</h2>

        @if(session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            {{-- <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Lupa Password?</a>
                @endif
            </div> --}}

            <div class="mb-4">
                <button type="submit" class="w-full py-2 rounded-md btn-login font-semibold">Login</button>
            </div>

            <div class="text-center text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar Sekarang</a>
            </div>
        </form>
    </div>
</body>
</html>
