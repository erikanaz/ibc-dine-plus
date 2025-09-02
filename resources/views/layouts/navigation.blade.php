<!-- Pastikan Alpine.js sudah ada -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ open: false }">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="text-2xl font-bold">
                <a href="/dashboard" class="font-medium hover:text-yellow-500">
                    <span class="text-yellow-500">IBC</span>Dine+
                </a>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                @auth
                    <a href="/homepage" class="font-medium {{ Request::is('homepage') ? 'text-yellow-500' : 'hover:text-yellow-500' }}">Beranda</a>
                    <a href="/reservation" class="font-medium {{ Request::is('reservation.index') ? 'text-yellow-500' : 'hover:text-yellow-500' }}">Reservasi</a>
                    <a href="/order" class="font-medium {{ Request::is('order.index') ? 'text-yellow-500' : 'hover:text-yellow-500' }}">Menu</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="gold-bg text-white px-4 py-2 rounded-md hover:bg-opacity-90">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login" class="gold-border border-2 gold-text px-4 py-1 rounded-md hover:gold-bg hover:text-white">Login</a>
                    <a href="/register" class="gold-bg text-white px-4 py-2 rounded-md hover:bg-opacity-90">Register</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-gray-700 focus:outline-none" @click="open = !open">
                <template x-if="!open">
                    <!-- Hamburger icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </template>
                <template x-if="open">
                    <!-- X icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </template>
            </button>
        </div>

        <!-- Mobile Dropdown Menu with Transition -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden mt-4 space-y-4"
             x-cloak>
            @auth
                <a href="/dashboard" class="block font-medium {{ Request::is('dashboard') ? 'text-yellow-500' : 'hover:text-yellow-500' }}">Beranda</a>
                <a href="/reservasi" class="block font-medium {{ Request::is('reservasi') ? 'text-yellow-500' : 'hover:text-yellow-500' }}">Reservasi</a>
                <a href="/pemesanan" class="block font-medium {{ Request::is('pemesanan') ? 'text-yellow-500' : 'hover:text-yellow-500' }}">Menu</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="gold-bg text-white w-full px-4 py-2 rounded-md hover:bg-opacity-90">
                        Logout
                    </button>
                </form>
            @else
                <a href="/login" class="block border-2 gold-border gold-text px-4 py-2 rounded-md hover:gold-bg hover:text-white">Login</a>
                <a href="/register" class="block gold-bg text-white px-4 py-2 rounded-md hover:bg-opacity-90">Register</a>
            @endauth
        </div>
    </div>
</nav>
