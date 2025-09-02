@extends('layouts.admin.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, Admin!')

@section('content')
    <!-- Dashboard Cards -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-1">Dashboard</h2>
        <p class="text-gray-600 text-base">Selamat datang kembali, Admin!</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Reservations Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-primary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Reservasi</p>
                    <p class="text-3xl font-bold mt-2">142</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="fas fa-calendar-check text-primary text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-success text-sm">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>12% dari bulan lalu</span>
            </div>
        </div>
        
        <!-- Today's Reservations Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-secondary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Reservasi Hari Ini</p>
                    <p class="text-3xl font-bold mt-2">18</p>
                </div>
                <div class="bg-secondary/10 p-3 rounded-lg">
                    <i class="fas fa-calendar-day text-secondary text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-danger text-sm">
                <i class="fas fa-arrow-down mr-1"></i>
                <span>3% dari kemarin</span>
            </div>
        </div>
        
        <!-- Monthly Revenue Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-success transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Pendapatan Bulan Ini</p>
                    <p class="text-3xl font-bold mt-2">Rp 42.8jt</p>
                </div>
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="fas fa-wallet text-success text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-success text-sm">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>18% dari bulan lalu</span>
            </div>
        </div>
        
        <!-- Available Tables Card -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-warning transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Meja Tersedia</p>
                    <p class="text-3xl font-bold mt-2">12/24</p>
                </div>
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="fas fa-chair text-warning text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-gray-500 text-sm">
                <i class="fas fa-info-circle mr-1"></i>
                <span>Kapasitas 50%</span>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3 width) -->
        <div class="lg:col-span-2">
            <!-- Today's Reservations Table -->
            <div class="bg-white rounded-xl shadow mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-calendar-day text-secondary mr-2"></i>
                        Reservasi Hari Ini
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Reservation 1 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/women/32.jpg" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Diana Putri</div>
                                            <div class="text-sm text-gray-500">4 Orang</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">18:00 - 20:00</div>
                                    <div class="text-sm text-gray-500">11 Jul 2023</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Meja 12
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-success/10 text-success">Konfirmasi</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-primary hover:text-primary/80 mr-3"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="text-warning hover:text-warning/80"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            
                            <!-- Reservation 2 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/men/41.jpg" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                                            <div class="text-sm text-gray-500">6 Orang</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">19:30 - 21:30</div>
                                    <div class="text-sm text-gray-500">11 Jul 2023</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Meja 5
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-warning/10 text-warning">Menunggu</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-primary hover:text-primary/80 mr-3"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="text-warning hover:text-warning/80"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            <!-- Reservation 3 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/women/68.jpg" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Siti Rahayu</div>
                                            <div class="text-sm text-gray-500">2 Orang</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">20:00 - 22:00</div>
                                    <div class="text-sm text-gray-500">11 Jul 2023</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Meja 8
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-danger/10 text-danger">Batal</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-primary hover:text-primary/80 mr-3"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="text-warning hover:text-warning/80"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            
                            <!-- Add more reservation rows as needed -->
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t text-center">
                    <a href="#" class="text-primary hover:underline">Lihat semua reservasi</a>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-chart-line text-primary mr-2"></i>
                        Pendapatan 7 Hari Terakhir
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-end h-64">
                        <!-- Chart bars would go here -->
                        <!-- Replace with actual chart library like Chart.js in production -->
                        <div class="flex-1 flex flex-col items-center justify-end">
                            <div class="bg-primary w-10 rounded-t-lg" style="height: 70%;"></div>
                            <p class="mt-2 text-sm text-gray-600">Sen</p>
                        </div>
                        <!-- Add more days as needed -->
                    </div>
                    <div class="flex justify-center mt-8">
                        <div class="flex items-center mr-6">
                            <div class="w-4 h-4 bg-primary rounded mr-2"></div>
                            <span class="text-sm text-gray-600">Pendapatan (juta)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (1/3 width) -->
        <div>
            <!-- Upcoming Reservations -->
            <div class="bg-white rounded-xl shadow mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-calendar-alt text-warning mr-2"></i>
                        Reservasi Mendatang
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    <!-- Reservation Card 1 -->
                    <div class="reservation-card p-4 border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold">Dewi Anggraini</p>
                                <p class="text-sm text-gray-600">12 Jul 2023 • 19:00</p>
                            </div>
                            <span class="status-badge bg-warning/10 text-warning">Menunggu</span>
                        </div>
                        <div class="mt-3 flex items-center text-sm">
                            <i class="fas fa-users text-gray-500 mr-2"></i>
                            <span>4 Orang • Meja 7</span>
                        </div>
                        <div class="mt-3 flex">
                            <button class="text-sm bg-primary text-white px-3 py-1 rounded mr-2">Konfirmasi</button>
                            <button class="text-sm bg-gray-200 text-gray-700 px-3 py-1 rounded">Detail</button>
                        </div>
                    </div>
                    
                    <!-- Reservation Card 2 -->
                    <div class="reservation-card p-4 border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold">Rudi Hartono</p>
                                <p class="text-sm text-gray-600">13 Jul 2023 • 20:30</p>
                            </div>
                            <span class="status-badge bg-success/10 text-success">Konfirmasi</span>
                        </div>
                        <div class="mt-3 flex items-center text-sm">
                            <i class="fas fa-users text-gray-500 mr-2"></i>
                            <span>6 Orang • Meja 10</span>
                        </div>
                        <div class="mt-3 flex">
                            <button class="text-sm bg-gray-200 text-gray-700 px-3 py-1 rounded">Detail</button>
                        </div>
                    </div>
                    <!-- Add more reservation cards as needed -->
                </div>
            </div>

            <!-- Table Status -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-chair text-secondary mr-2"></i>
                        Status Meja
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-4 gap-3">
                        <div class="bg-success/10 border border-success rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold">1-6</div>
                            <div class="text-sm text-success">Tersedia</div>
                        </div>
                        <div class="bg-warning/10 border border-warning rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold">7-8</div>
                            <div class="text-sm text-warning">Reservasi</div>
                        </div>
                        <div class="bg-primary/10 border border-primary rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold">9-12</div>
                            <div class="text-sm text-primary">Terisi</div>
                        </div>
                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold">13-14</div>
                            <div class="text-sm text-gray-500">Perbaikan</div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="font-medium mb-3">Legenda Status</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-success rounded-full mr-2"></div>
                                <span class="text-sm">Tersedia</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-warning rounded-full mr-2"></div>
                                <span class="text-sm">Reservasi</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-primary rounded-full mr-2"></div>
                                <span class="text-sm">Terisi</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-400 rounded-full mr-2"></div>
                                <span class="text-sm">Tidak Tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection