<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - IBC Dine+</title>
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
        .btn-secondary {
            background-color: #1F2937; /* gray-800 */
            color: white;
        }
        .btn-secondary:hover {
            background-color: #374151; /* gray-700 */
        }
    </style>
</head>
<body class="font-sans antialiased login-bg flex items-center justify-center">

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <div class="flex items-center justify-center mb-6">
            <img src="/images/logoibc.png" alt="Logo IBC Dine+" class="h-12">
        </div>
        <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Lupa Password</h2>

        <div class="mb-4 text-sl text-gray-600 text-center  ">
            Lupa password Anda? Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan tautan reset password yang memungkinkan Anda memilih yang baru.
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <button type="submit" class="w-full py-2 rounded-md btn-login font-semibold">Kirim Tautan Reset Password</button>
            </div>

            <div class="mb-0">
                <a href="{{ route('login') }}" class="w-full px-32 py-2 rounded-md btn-secondary font-semibold">Kembali ke Login</a>
            </div>
        </form>
    </div>
</body>
</html>