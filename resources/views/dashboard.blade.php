@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg hidden md:block">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">Admin Panel</h2>
        </div>
        <nav class="p-4 space-y-2">
            <a href="/admin/dashboard" class="block px-4 py-2 rounded-md bg-gray-200 text-gray-800 font-medium">Dashboard</a>
            <a href="/admin/reservasi" class="block px-4 py-2 rounded-md hover:bg-gray-100">Reservasi</a>
            <a href="/admin/pemesanan" class="block px-4 py-2 rounded-md hover:bg-gray-100">Pemesanan</a>
            <a href="/admin/menu" class="block px-4 py-2 rounded-md hover:bg-gray-100">Kelola Menu</a>
            <a href="/admin/promo" class="block px-4 py-2 rounded-md hover:bg-gray-100">Promo</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <h2 class="text-3xl font-bold mb-6">Dashboard Admin</h2>

        <!-- Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Total Reservasi</h3>
                <p class="text-2xl font-bold text-green-600">120</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Total Pemesanan</h3>
                <p class="text-2xl font-bold text-blue-600">245</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Menu Aktif</h3>
                <p class="text-2xl font-bold text-yellow-600">18</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Promo Aktif</h3>
                <p class="text-2xl font-bold text-red-600">2</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4">Reservasi Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-left">
                            <th class="py-2 px-4 border-b">Nama</th>
                            <th class="py-2 px-4 border-b">Tanggal</th>
                            <th class="py-2 px-4 border-b">Waktu</th>
                            <th class="py-2 px-4 border-b">Jumlah Orang</th>
                            <th class="py-2 px-4 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">Budi</td>
                            <td class="py-2 px-4 border-b">2025-06-28</td>
                            <td class="py-2 px-4 border-b">19:00</td>
                            <td class="py-2 px-4 border-b">4</td>
                            <td class="py-2 px-4 border-b text-green-600 font-semibold">Dikonfirmasi</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b">Sari</td>
                            <td class="py-2 px-4 border-b">2025-06-28</td>
                            <td class="py-2 px-4 border-b">20:00</td>
                            <td class="py-2 px-4 border-b">2</td>
                            <td class="py-2 px-4 border-b text-yellow-600 font-semibold">Menunggu</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
