<header class="bg-white border-b shadow-md">
  <div class="flex items-center px-6 py-4">
    <div class="flex items-center flex-1">
      <!-- Tombol Toggle -->
      <button @click="sidebarOpen = !sidebarOpen" class="mr-6 text-gray-500 hover:text-primary focus:outline-none">
        <i class="fas fa-bars text-xl"></i>
      </button>

      <!-- Search Bar -->
      <div class="relative w-full max-w-md">
        <input 
          type="text" 
          placeholder="Cari..." 
          class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        >
        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
      </div>
    </div>

    <!-- Menu Kanan -->
    <div class="flex items-center space-x-6 ml-auto">
      <!-- Notifikasi -->
      <!-- <button class="relative text-gray-500 hover:text-primary">
        <i class="fas fa-bell text-xl"></i>
        <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
      </button> -->

      <!-- Pesan -->
      <!-- <button class="relative text-gray-500 hover:text-primary">
        <i class="fas fa-envelope text-xl"></i>
        <span class="absolute -top-1 -right-1 bg-secondary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">7</span>
      </button> -->

      <!-- Dropdown Akun -->
      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
          <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>
          <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             @click.away="open = false"
             x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100">
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-user mr-2"></i> Profil
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>