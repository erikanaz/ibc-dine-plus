@extends('layouts.admin.app')

@section('title', 'Kelola Pesanan')
@section('subtitle', 'Kelola semua pesanan restoran')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-1">Kelola Pesanan</h2>
                <p class="text-gray-600 text-base">Kelola semua pesanan dan transaksi restoran</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.orders.create') }}" class="btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Pesanan
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Orders -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-primary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Pesanan</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="fas fa-shopping-cart text-primary text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-success transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Pendapatan</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-success text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Pending Orders -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-warning transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Menunggu</p>
                    <p class="text-3xl font-bold mt-2">{{ $pendingOrders }}</p>
                </div>
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="fas fa-clock text-warning text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Completed Orders -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-green-500 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Selesai</p>
                    <p class="text-3xl font-bold mt-2">{{ $completedOrders }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg flex items-center">
                <i class="fas fa-list text-primary mr-2"></i>
                Daftar Pesanan
            </h3>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari pesanan..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer & Meja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Items & Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="orderTableBody">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors order-row" data-status="{{ $order->status }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                <div class="text-xs text-gray-500">#{{ $order->id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $order->user->name }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    @if($order->reservation)
                                        Meja {{ $order->reservation->table->number }}
                                    @else
                                        <span class="text-gray-400">Tanpa Reservasi</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $order->user->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $order->total_items }} item
                                </div>
                                <div class="text-sm font-medium text-primary">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $order->orderItems->count() }} jenis menu
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $order->created_at->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $order->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge 
                                    @if($order->status === 'pending') bg-warning/10 text-warning
                                    @elseif($order->status === 'confirmed') bg-primary/10 text-primary
                                    @elseif($order->status === 'completed') bg-success/10 text-success
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-600
                                    @elseif($order->status === 'expired') bg-secondary/10 text-secondary
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $order->status_label }}
                                </span>
                                @if($order->reservation)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $order->reservation->guest_count }} orang
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors"
                                       title="Detail Pesanan">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                       class="text-primary hover:text-primary/80 transition-colors"
                                       title="Edit Pesanan">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.print', $order->id) }}" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-800 transition-colors"
                                       title="Cetak Invoice">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus pesanan {{ $order->order_number }}?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Hapus Pesanan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                                <p>Belum ada pesanan</p>
                                <a href="{{ route('admin.orders.create') }}" class="text-primary hover:underline mt-2 inline-block">
                                    Buat pesanan pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection

@section('styles')
<style>
    .btn-primary {
        background-color: #3b82f6;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Color definitions */
    .bg-primary { background-color: #3b82f6; }
    .text-primary { color: #3b82f6; }
    .bg-primary\/10 { background-color: rgba(59, 130, 246, 0.1); }
    .border-primary { border-color: #3b82f6; }

    .bg-success { background-color: #10b981; }
    .text-success { color: #10b981; }
    .bg-success\/10 { background-color: rgba(16, 185, 129, 0.1); }
    .border-success { border-color: #10b981; }

    .bg-warning { background-color: #f59e0b; }
    .text-warning { color: #f59e0b; }
    .bg-warning\/10 { background-color: rgba(245, 158, 11, 0.1); }
    .border-warning { border-color: #f59e0b; }

    .bg-red-100 { background-color: #fee2e2; }
    .text-red-600 { color: #dc2626; }

    .bg-green-100 { background-color: #dcfce7; }
    .text-green-500 { color: #22c55e; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const orderRows = document.querySelectorAll('.order-row');

        function filterOrders() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            orderRows.forEach(row => {
                const orderNumber = row.querySelector('td:first-child .text-sm').textContent.toLowerCase();
                const customerName = row.querySelector('td:nth-child(2) .text-sm').textContent.toLowerCase();
                const status = row.getAttribute('data-status');

                const matchesSearch = orderNumber.includes(searchTerm) || customerName.includes(searchTerm);
                const matchesStatus = !statusValue || status.includes(statusValue);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterOrders);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', filterOrders);
        }
    });
</script>
@endsection