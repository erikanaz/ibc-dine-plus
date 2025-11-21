<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - IBC Dine+</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .login-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/pizza-bg.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }
        .btn-primary {
            background-color: #D4AF37;
            color: white;
        }
        .btn-primary:hover {
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
        <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Verifikasi Email</h2>

        <!-- Flash message registrasi sukses -->
        @if (session('status') && session('status') != 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 text-center">
                {{ session('status') }}
            </div>
        @endif

        <!-- Flash message kirim ulang link -->
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 text-center">
                Tautan verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <div class="mb-4 text-sm text-gray-600 text-center">
            Terima kasih telah mendaftar! Sebelum memulai, silakan memverifikasi email Anda dengan mengklik tautan yang telah dikirim. Jika tidak menerima email, kami akan mengirimkan yang baru.
        </div>

        <div class="mt-6 flex flex-col gap-4">
            <!-- Kirim ulang email -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-2 rounded-md btn-primary font-semibold">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-2 rounded-md btn-secondary font-semibold">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
