<!-- Sidebar -->
<div class="sidebar w-64 bg-dark text-white fixed h-screen">
    <div class="p-5 border-b border-slate-700">
        <div class="text-2xl font-bold">
            <a href="/dashboard" class="font-medium hover:text-yellow-500">
                <span class="text-yellow-500">IBC</span>Dine+
            </a>
        </div>
        {{-- <p class="text-slate-400 text-sm mt-1">Admin Panel</p> --}}
    </div>
    
    <div class="py-4">
        <a href="{{ route('admin.dashboard') }}" 
            class="sidebar-item flex items-center px-6 py-3 
                    {{ request()->routeIs('admin.dashboard') 
                        ? 'text-white bg-slate-800 border-l-4 border-primary' 
                        : 'text-slate-300 hover:text-white' }}">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.tables.index') }}" 
            class="sidebar-item flex items-center px-6 py-3 
                    {{ request()->routeIs('admin.tables.index') 
                        ? 'text-white bg-slate-800 border-l-4 border-primary' 
                        : 'text-slate-300 hover:text-white' }}">
            <i class="fas fa-chair mr-3"></i>
            Meja
        </a>

        <a href="{{ route('admin.menus.index') }}" 
            class="sidebar-item flex items-center px-6 py-3 
                    {{ request()->routeIs('admin.menus.index') 
                        ? 'text-white bg-slate-800 border-l-4 border-primary' 
                        : 'text-slate-300 hover:text-white' }}">
            <i class="fas fa-book-open mr-3"></i>
            Menu
        </a>
        
        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-slate-300 hover:text-white">
            <i class="fas fa-calendar-check mr-3"></i>
            Reservasi
        </a>
        
        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-slate-300 hover:text-white">
            <i class="fas fa-receipt mr-3"></i>
            Pesanan
        </a>
        
        <a href="{{ route('admin.promos.index') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-300 hover:text-white">
            <i class="fas fa-tags mr-3"></i>
            Promo
        </a>
        
        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-slate-300 hover:text-white">
            <i class="fas fa-chart-bar mr-3"></i>
            Laporan
        </a>

        
        {{-- <div class="absolute bottom-0 w-full p-4 border-t border-slate-700">
            <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Admin" class="w-10 h-10 rounded-full">
                <div class="ml-3">
                    <p class="font-medium">Admin User</p>
                    <p class="text-slate-400 text-sm">Super Admin</p>
                </div>
            </div>
        </div> --}}
    </div>
</div>