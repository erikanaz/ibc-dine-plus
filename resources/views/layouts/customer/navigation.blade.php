<!-- Pastikan Alpine.js sudah ada -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ open: false, accountOpen: false }">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="text-2xl font-bold">
                <a href="/homepage" class="font-medium hover:text-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 rounded">
                    <span class="text-yellow-500">IBC</span>Dine+
                </a>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                @auth
                    <a href="/homepage" class="font-medium {{ Request::is('homepage*') ? 'text-yellow-500' : 'hover:text-yellow-500' }} focus:outline-none focus:underline">Beranda</a>
                    <a href="/reservation" class="font-medium {{ Request::is('reservation*') ? 'text-yellow-500' : 'hover:text-yellow-500' }} focus:outline-none focus:underline">Reservasi</a>
                    <a href="/order" class="font-medium {{ Request::is('order*') ? 'text-yellow-500' : 'hover:text-yellow-500' }} focus:outline-none focus:underline">Menu</a>
                    
                    <!-- Account Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            @keydown.escape="open = false"
                            :aria-expanded="open.toString()"
                            class="gold-bg text-white px-4 py-2 rounded-md hover:bg-opacity-90 flex items-center space-x-1 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                        >
                            <span>{{ Auth::user()->name ?? 'Account' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="{ 'transform rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5"
                             x-cloak>
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-yellow-600 transition-colors focus:bg-gray-50 focus:text-yellow-600">Profil</a>
                            <a href="/reservation-history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-yellow-600 transition-colors focus:bg-gray-50 focus:text-yellow-600">Riwayat Reservasi</a>
                            <a href="/order-history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-yellow-600 transition-colors focus:bg-gray-50 focus:text-yellow-600">Riwayat Pesanan</a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors focus:bg-gray-50 focus:text-red-600">
                                    <!-- Icon Logout -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                            {{-- <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors focus:bg-gray-50 focus:text-red-600">
                                    Logout
                                </button>
                            </form> --}}
                        </div>
                    </div>
                @else
                    <a href="/login" class="gold-border border-2 gold-text px-4 py-1 rounded-md hover:gold-bg hover:text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200">Login</a>
                    <a href="/register" class="gold-bg text-white px-4 py-2 rounded-md hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200">Register</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button 
                class="md:hidden text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 rounded p-1" 
                @click="open = !open"
                :aria-expanded="open.toString()"
            >
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

        <!-- Mobile Backdrop -->
        <div x-show="open" 
             class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:leave="transition ease-in duration-200"
             @click="open = false"
             x-cloak>
        </div>

        <!-- Mobile Dropdown Menu with Transition -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden mt-4 space-y-4 bg-white rounded-lg shadow-lg p-4 relative z-50"
             x-cloak>
            @auth
                <a href="/homepage" class="block font-medium {{ Request::is('homepage*') ? 'text-yellow-500' : 'hover:text-yellow-500' }} px-4 py-2 rounded hover:bg-gray-50 focus:bg-gray-50">Beranda</a>
                <a href="/reservation" class="block font-medium {{ Request::is('reservation*') ? 'text-yellow-500' : 'hover:text-yellow-500' }} px-4 py-2 rounded hover:bg-gray-50 focus:bg-gray-50">Reservasi</a>
                <a href="/order" class="block font-medium {{ Request::is('order*') ? 'text-yellow-500' : 'hover:text-yellow-500' }} px-4 py-2 rounded hover:bg-gray-50 focus:bg-gray-50">Menu</a>
                <div class="border-t border-gray-200 pt-2">
                    <p class="text-sm font-semibold text-gray-500 px-4 py-1">Akun Saya</p>
                    <a href="/profile" class="block px-4 py-2 text-gray-700 hover:text-yellow-500 hover:bg-gray-50 rounded focus:bg-gray-50">Profil</a>
                    <a href="/reservation-history" class="block px-4 py-2 text-gray-700 hover:text-yellow-500 hover:bg-gray-50 rounded focus:bg-gray-50">Riwayat Reservasi</a>
                    <a href="/order-history" class="block px-4 py-2 text-gray-700 hover:text-yellow-500 hover:bg-gray-50 rounded focus:bg-gray-50">Riwayat Pesanan</a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="gold-bg text-white w-full px-4 py-2 rounded-md hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200">
                        Logout
                    </button>
                </form>
            @else
                <a href="/login" class="block border-2 gold-border gold-text px-4 py-2 rounded-md hover:gold-bg hover:text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 text-center transition-colors duration-200">Login</a>
                <a href="/register" class="block gold-bg text-white px-4 py-2 rounded-md hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 text-center transition-colors duration-200">Register</a>
            @endauth
        </div>
    </div>
</nav>