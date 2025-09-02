<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IBC Dine+ | Ikan Bakar Cianjur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('/images/ibc-bg.png') no-repeat center center;
            background-size: cover;
        }
        .gold-text {
            color: #D4AF37;
        }
        .gold-bg {
            background-color: #272521;
        }
        .gold-border {
            border-color: #D4AF37;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .gold-bg:hover {
            background-color: #e6c04d;
        }
        [x-cloak] { display: none !important; }
        .login-required {
            position: relative;
        }
        .login-required::after {
            content: "Login untuk mengakses";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .login-required:hover::after {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
    
</body>
</html>