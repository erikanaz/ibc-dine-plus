<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Ikan Bakar Cianjur</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <style>
        .gold-bg {
            background-color: #D4AF37;
        }
        .gold-text {
            color: #D4AF37;
        }
        .gold-border {
            border-color: #D4AF37;
        }
        body {
            background-color: #f0f2f5;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Top Navigation -->
    <nav class="gold-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/cashier" class="text-xl font-bold">IBC KASIR</a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-white">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                        </button>
                    </div>
                    <div class="relative">
                        <button @click="toggleUserMenu" class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center">
                                <i class="fas fa-user gold-text"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div v-show="userMenuOpen" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                userMenuOpen: false
            },
            methods: {
                toggleUserMenu() {
                    this.userMenuOpen = !this.userMenuOpen;
                }
            }
        });
    </script>
</body>
</html>