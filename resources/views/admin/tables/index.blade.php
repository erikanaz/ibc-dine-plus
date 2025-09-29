@extends('layouts.admin.app')

@section('title', 'Manajemen Meja')
@section('subtitle', 'Kelola meja restoran Anda')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-1">Manajemen Meja</h2>
                <p class="text-gray-600 text-base">Kelola meja restoran Anda</p>
            </div>
            <div class="flex space-x-3">
                <button class="btn-secondary flex items-center">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Layout
                </button>
                <a href="{{ route('admin.tables.create') }}" class="btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Meja
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Tables -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-primary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Meja</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalTables }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="fas fa-chair text-primary text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-success text-sm">
                <i class="fas fa-percentage mr-1"></i>
                <span>100% total meja</span>
            </div>
        </div>
        
        <!-- Meja 4 Orang -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-success transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Meja 4 Orang</p>
                    <p class="text-3xl font-bold mt-2">{{ $tables4Person }}</p>
                </div>
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="fas fa-user-friends text-success text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-gray-500 text-sm">
                <i class="fas fa-users mr-1"></i>
                <span>Kapasitas 4 orang</span>
            </div>
        </div>
        
        <!-- Meja 5 Orang -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-warning transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Meja 5 Orang</p>
                    <p class="text-3xl font-bold mt-2">{{ $tables5Person }}</p>
                </div>
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="fas fa-users text-warning text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-warning text-sm">
                <i class="fas fa-users mr-1"></i>
                <span>Kapasitas 5 orang</span>
            </div>
        </div>
        
        <!-- Meja 6 Orang -->
        <div class="dashboard-card bg-white rounded-xl shadow p-6 border-l-4 border-secondary transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Meja 6 Orang</p>
                    <p class="text-3xl font-bold mt-2">{{ $tables6Person }}</p>
                </div>
                <div class="bg-secondary/10 p-3 rounded-lg">
                    <i class="fas fa-users text-secondary text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-secondary text-sm">
                <i class="fas fa-users mr-1"></i>
                <span>Kapasitas 6 orang</span>
            </div>
        </div>
    </div>

    <!-- Status Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Available Tables -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-success rounded-full mr-3"></div>
                    <span class="text-gray-600">Tersedia</span>
                </div>
                <span class="text-2xl font-bold text-success">{{ $availableTables }}</span>
            </div>
        </div>
        
        <!-- Occupied Tables -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-warning rounded-full mr-3"></div>
                    <span class="text-gray-600">Terisi</span>
                </div>
                <span class="text-2xl font-bold text-warning">{{ $occupiedTables }}</span>
            </div>
        </div>
        
        <!-- Reserved Tables -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-secondary rounded-full mr-3"></div>
                    <span class="text-gray-600">Reservasi</span>
                </div>
                <span class="text-2xl font-bold text-secondary">{{ $reservedTables }}</span>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Table Layout Visualization -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-th-large text-primary mr-2"></i>
                        Daftar Meja
                    </h3>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.tables.index') }}" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg transition-colors">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Restaurant Layout Visualization -->
                    <div class="restaurant-layout bg-gray-50 rounded-lg p-6 mb-6">
                        @foreach($tablesByCapacity as $capacity => $capacityTables)
                            <div class="mb-8">
                                <h4 class="font-bold text-lg mb-4 flex items-center">
                                    <i class="fas fa-users text-blue-500 mr-2"></i>
                                    Meja {{ $capacity }} Orang
                                    <span class="ml-2 text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        {{ $capacityTables->count() }} meja
                                    </span>
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($capacityTables as $table)
                                        <a href="{{ route('admin.tables.index', ['selected_table' => $table->id]) }}" 
                                           class="table-card block 
                                            @if($table->status === 'available') bg-success/20 border-2 border-success
                                            @elseif($table->status === 'occupied') bg-warning/20 border-2 border-warning
                                            @elseif($table->status === 'reserved') bg-secondary/20 border-2 border-secondary
                                            @else bg-gray-200 border-2 border-gray-400 @endif
                                            @if($selectedTable && $selectedTable->id == $table->id) ring-2 ring-primary ring-offset-2 @endif
                                            rounded-lg p-4 text-center transition-all hover:shadow-lg">
                                            <div class="text-2xl font-bold mb-1">{{ $table->number }}</div>
                                            <div class="text-sm font-medium 
                                                @if($table->status === 'available') text-success
                                                @elseif($table->status === 'occupied') text-warning
                                                @elseif($table->status === 'reserved') text-secondary
                                                @else text-gray-600 @endif">
                                                {{ $table->status_label }}
                                            </div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $table->location_label }}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold mb-3">Legenda Status Meja</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-success rounded-full mr-2"></div>
                                <span class="text-sm">Tersedia</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-warning rounded-full mr-2"></div>
                                <span class="text-sm">Terisi</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-secondary rounded-full mr-2"></div>
                                <span class="text-sm">Reservasi</span>
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

        <!-- Right Column - Table Details and Actions -->
        <div class="space-y-6">
            @if($selectedTable)
                <!-- Selected Table Details -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="font-bold text-lg flex items-center">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            Detail Meja
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 rounded-full mb-3">
                                <span class="text-2xl font-bold text-primary">{{ $selectedTable->number }}</span>
                            </div>
                            <h4 class="text-xl font-bold">Meja {{ $selectedTable->number }}</h4>
                            <span class="status-badge 
                                @if($selectedTable->status === 'available') bg-success/10 text-success
                                @elseif($selectedTable->status === 'occupied') bg-warning/10 text-warning
                                @elseif($selectedTable->status === 'reserved') bg-secondary/10 text-secondary
                                @else bg-gray-100 text-gray-600 @endif mt-2">
                                {{ $selectedTable->status_label }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kapasitas:</span>
                                <span class="font-medium">{{ $selectedTable->capacity }} Orang</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lokasi:</span>
                                <span class="font-medium">{{ $selectedTable->location_label }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium 
                                    @if($selectedTable->status === 'available') text-success
                                    @elseif($selectedTable->status === 'occupied') text-warning
                                    @elseif($selectedTable->status === 'reserved') text-secondary
                                    @else text-gray-600 @endif">
                                    {{ $selectedTable->status_label }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Catatan:</span>
                                <span class="font-medium">{{ $selectedTable->notes ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <a href="{{ route('admin.tables.edit', $selectedTable->id) }}" class="w-full bg-primary text-white py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>Edit Meja
                            </a>
                            <button class="w-full bg-warning text-white py-2 rounded-lg font-medium hover:bg-warning/90 transition-colors flex items-center justify-center">
                                <i class="fas fa-calendar-plus mr-2"></i>Buat Reservasi
                            </button>
                            <button class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-history mr-2"></i>Riwayat Penggunaan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="font-bold text-lg flex items-center">
                            <i class="fas fa-bolt text-warning mr-2"></i>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <form action="{{ route('admin.tables.update-status', $selectedTable->id) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="available">
                            <button type="submit" class="w-full flex items-center justify-center bg-success/10 text-success py-3 rounded-lg font-medium hover:bg-success/20 transition-colors 
                                @if($selectedTable->status === 'available') opacity-50 cursor-not-allowed @endif">
                                <i class="fas fa-check-circle mr-2"></i>Tandai sebagai Tersedia
                            </button>
                        </form>

                        <form action="{{ route('admin.tables.update-status', $selectedTable->id) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="occupied">
                            <button type="submit" class="w-full flex items-center justify-center bg-warning/10 text-warning py-3 rounded-lg font-medium hover:bg-warning/20 transition-colors 
                                @if($selectedTable->status === 'occupied') opacity-50 cursor-not-allowed @endif">
                                <i class="fas fa-users mr-2"></i>Tandai sebagai Terisi
                            </button>
                        </form>

                        <form action="{{ route('admin.tables.update-status', $selectedTable->id) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="reserved">
                            <button type="submit" class="w-full flex items-center justify-center bg-secondary/10 text-secondary py-3 rounded-lg font-medium hover:bg-secondary/20 transition-colors 
                                @if($selectedTable->status === 'reserved') opacity-50 cursor-not-allowed @endif">
                                <i class="fas fa-calendar-alt mr-2"></i>Tandai sebagai Reservasi
                            </button>
                        </form>

                        <form action="{{ route('admin.tables.update-status', $selectedTable->id) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="maintenance">
                            <button type="submit" class="w-full flex items-center justify-center bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors 
                                @if($selectedTable->status === 'maintenance') opacity-50 cursor-not-allowed @endif">
                                <i class="fas fa-tools mr-2"></i>Tandai Perbaikan
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="font-bold text-lg flex items-center">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            Detail Meja
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-3">
                            <i class="fas fa-chair text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-400 mb-2">Pilih Meja</h4>
                        <p class="text-gray-500">Klik pada salah satu meja di daftar untuk melihat detail</p>
                    </div>
                </div>

                <!-- Quick Actions Placeholder -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="font-bold text-lg flex items-center">
                            <i class="fas fa-bolt text-warning mr-2"></i>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-gray-500 text-sm">Pilih meja terlebih dahulu untuk mengubah status</p>
                    </div>
                </div>
            @endif

            <!-- Recent Table Activities -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-history text-secondary mr-2"></i>
                        Aktivitas Terkini
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">Meja {{ $activity['table_number'] }}</p>
                                    <p class="text-sm text-gray-600">
                                        @if($activity['customer_name'])
                                            Pelanggan: {{ $activity['customer_name'] }}
                                        @else
                                            Tersedia
                                        @endif
                                    </p>
                                </div>
                                <span class="status-badge 
                                    @if($activity['status'] === 'occupied') bg-warning/10 text-warning
                                    @elseif($activity['status'] === 'reserved') bg-secondary/10 text-secondary
                                    @else bg-success/10 text-success @endif">
                                    @if($activity['status'] === 'occupied') Terisi
                                    @elseif($activity['status'] === 'reserved') Reservasi
                                    @else Tersedia @endif
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $activity['time'] }} 
                                @if($activity['people'])
                                    • {{ $activity['people'] }} orang
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
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
    
    .btn-secondary {
        background-color: #6b7280;
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
    
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
    }
    
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .table-card {
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    
    .table-card:hover {
        transform: scale(1.05);
    }
    
    .activity-item {
        padding: 0.75rem;
        border-radius: 0.5rem;
        background-color: #f9fafb;
        transition: background-color 0.2s;
    }
    
    .activity-item:hover {
        background-color: #f3f4f6;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .ring-2 {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }

    .ring-primary {
        --tw-ring-color: rgba(59, 130, 246, 0.5);
    }

    .ring-offset-2 {
        --tw-ring-offset-width: 2px;
    }

    /* Success colors */
    .bg-success { background-color: #10b981; }
    .text-success { color: #10b981; }
    .bg-success\/10 { background-color: rgba(16, 185, 129, 0.1); }
    .bg-success\/20 { background-color: rgba(16, 185, 129, 0.2); }
    .border-success { border-color: #10b981; }

    /* Warning colors */
    .bg-warning { background-color: #f59e0b; }
    .text-warning { color: #f59e0b; }
    .bg-warning\/10 { background-color: rgba(245, 158, 11, 0.1); }
    .bg-warning\/20 { background-color: rgba(245, 158, 11, 0.2); }
    .border-warning { border-color: #f59e0b; }

    /* Secondary colors */
    .bg-secondary { background-color: #6b7280; }
    .text-secondary { color: #6b7280; }
    .bg-secondary\/10 { background-color: rgba(107, 114, 128, 0.1); }
    .bg-secondary\/20 { background-color: rgba(107, 114, 128, 0.2); }
    .border-secondary { border-color: #6b7280; }

    /* Primary colors */
    .bg-primary { background-color: #3b82f6; }
    .text-primary { color: #3b82f6; }
    .bg-primary\/10 { background-color: rgba(59, 130, 246, 0.1); }

    /* Gray colors */
    .bg-gray-100 { background-color: #f3f4f6; }
    .bg-gray-200 { background-color: #e5e7eb; }
    .text-gray-400 { color: #9ca3af; }
    .text-gray-500 { color: #6b7280; }
    .text-gray-600 { color: #4b5563; }
    .text-gray-700 { color: #374151; }
    .text-gray-800 { color: #1f2937; }
    .border-gray-400 { border-color: #9ca3af; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling to table cards
        const tableCards = document.querySelectorAll('.table-card');
        
        tableCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // The navigation is handled by the link href
                // We can add any additional JavaScript behavior here if needed
            });
        });

        // Add confirmation for status changes
        const statusForms = document.querySelectorAll('form[action*="update-status"]');
        
        statusForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button');
                if (button.classList.contains('cursor-not-allowed')) {
                    e.preventDefault();
                    return false;
                }
                
                const status = this.querySelector('input[name="status"]').value;
                const statusText = getStatusText(status);
                
                if (!confirm(`Apakah Anda yakin ingin mengubah status meja menjadi "${statusText}"?`)) {
                    e.preventDefault();
                }
            });
        });

        function getStatusText(status) {
            const statusMap = {
                'available': 'Tersedia',
                'occupied': 'Terisi',
                'reserved': 'Reservasi',
                'maintenance': 'Perbaikan'
            };
            return statusMap[status] || status;
        }
    });
</script>
@endsection