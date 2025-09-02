@extends('layouts.admin.app')

@section('title', 'Manajemen Meja')
@section('subtitle', 'Kelola ketersediaan dan penempatan meja')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-1">Manajemen Meja</h2>
        <p class="text-gray-600 text-base">Kelola ketersediaan dan penempatan meja restoran</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
            <button class="btn btn-primary flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Meja
            </button>
            <button class="btn btn-secondary flex items-center">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
        </div>
        <div class="relative">
            <input type="text" placeholder="Cari meja..." class="pl-10 pr-4 py-2 border rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
    </div>

    <!-- Table Status Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-primary">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Total Meja</p>
                    <p class="text-2xl font-bold">24</p>
                </div>
                <div class="text-primary text-2xl">
                    <i class="fas fa-chair"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-success">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Tersedia</p>
                    <p class="text-2xl font-bold">12</p>
                </div>
                <div class="text-success text-2xl">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-warning">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Dipesan</p>
                    <p class="text-2xl font-bold">6</p>
                </div>
                <div class="text-warning text-2xl">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-danger">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Tidak Tersedia</p>
                    <p class="text-2xl font-bold">6</p>
                </div>
                <div class="text-danger text-2xl">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Table List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-list-ul text-primary mr-2"></i>
                        Daftar Meja
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Filter:</span>
                        <select class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                            <option>Semua</option>
                            <option>Tersedia</option>
                            <option>Dipesan</option>
                            <option>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Meja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Table 1 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">Meja 01</td>
                                <td class="px-6 py-4 whitespace-nowrap">2 Orang</td>
                                <td class="px-6 py-4 whitespace-nowrap">Area Depan</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-success/10 text-success">Tersedia</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="text-primary hover:text-primary/80">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-danger hover:text-danger/80">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Table 2 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">Meja 02</td>
                                <td class="px-6 py-4 whitespace-nowrap">4 Orang</td>
                                <td class="px-6 py-4 whitespace-nowrap">Area Tengah</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-warning/10 text-warning">Dipesan</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="text-primary hover:text-primary/80">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-danger hover:text-danger/80">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Table 3 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">Meja 03</td>
                                <td class="px-6 py-4 whitespace-nowrap">6 Orang</td>
                                <td class="px-6 py-4 whitespace-nowrap">Area Belakang</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-primary/10 text-primary">Terisi</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="text-primary hover:text-primary/80">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-danger hover:text-danger/80">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Table 4 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">Meja 04</td>
                                <td class="px-6 py-4 whitespace-nowrap">8 Orang</td>
                                <td class="px-6 py-4 whitespace-nowrap">Area VIP</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge bg-danger/10 text-danger">Tidak Tersedia</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="text-primary hover:text-primary/80">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-danger hover:text-danger/80">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Add more tables as needed -->
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Menampilkan 1 hingga 4 dari 24 entri
                    </div>
                    <div class="flex space-x-1">
                        <button class="px-3 py-1 border rounded bg-gray-100 text-gray-600">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 border rounded bg-primary text-white">1</button>
                        <button class="px-3 py-1 border rounded hover:bg-gray-100">2</button>
                        <button class="px-3 py-1 border rounded hover:bg-gray-100">3</button>
                        <button class="px-3 py-1 border rounded hover:bg-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Add/Edit Table Form -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <i class="fas fa-plus-circle text-primary mr-2"></i>
                    Tambah Meja Baru
                </h3>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Nomor Meja</label>
                        <input type="text" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Kapasitas</label>
                        <select class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-primary">
                            <option>2 Orang</option>
                            <option>4 Orang</option>
                            <option>6 Orang</option>
                            <option>8 Orang</option>
                            <option>10 Orang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Lokasi</label>
                        <select class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-primary">
                            <option>Area Depan</option>
                            <option>Area Tengah</option>
                            <option>Area Belakang</option>
                            <option>Area VIP</option>
                            <option>Area Outdoor</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Status</label>
                        <select class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-primary">
                            <option>Tersedia</option>
                            <option>Tidak Tersedia</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="reset" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">Reset</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">Simpan</button>
                    </div>
                </form>
            </div>

            <!-- Table Layout Preview -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <i class="fas fa-project-diagram text-secondary mr-2"></i>
                    Denah Meja
                </h3>
                <div class="bg-gray-100 p-4 rounded-lg">
                    <div class="grid grid-cols-4 gap-3">
                        <!-- Table 1 -->
                        <div class="bg-success/20 border border-success rounded-lg p-2 text-center cursor-pointer hover:shadow-md transition-all">
                            <div class="font-bold">Meja 1</div>
                            <div class="text-xs text-success">2 Orang</div>
                            <div class="text-xs text-gray-600">Tersedia</div>
                        </div>
                        <!-- Table 2 -->
                        <div class="bg-warning/20 border border-warning rounded-lg p-2 text-center cursor-pointer hover:shadow-md transition-all">
                            <div class="font-bold">Meja 2</div>
                            <div class="text-xs text-warning">4 Orang</div>
                            <div class="text-xs text-gray-600">Dipesan</div>
                        </div>
                        <!-- Table 3 -->
                        <div class="bg-primary/20 border border-primary rounded-lg p-2 text-center cursor-pointer hover:shadow-md transition-all">
                            <div class="font-bold">Meja 3</div>
                            <div class="text-xs text-primary">6 Orang</div>
                            <div class="text-xs text-gray-600">Terisi</div>
                        </div>
                        <!-- Table 4 -->
                        <div class="bg-danger/20 border border-danger rounded-lg p-2 text-center cursor-pointer hover:shadow-md transition-all">
                            <div class="font-bold">Meja 4</div>
                            <div class="text-xs text-danger">8 Orang</div>
                            <div class="text-xs text-gray-600">Tidak Tersedia</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:underline text-sm">Lihat denah lengkap</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal (hidden by default) -->
    <div class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-4">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6">Anda yakin ingin menghapus meja ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex justify-end space-x-3">
                    <button class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100 modal-cancel">Batal</button>
                    <button class="px-4 py-2 bg-danger text-white rounded-lg hover:bg-danger/90">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // JavaScript untuk modal
    document.addEventListener('DOMContentLoaded', function() {
        // Modal toggle
        const deleteButtons = document.querySelectorAll('.fa-trash');
        const modal = document.querySelector('.modal');
        const modalCancel = document.querySelector('.modal-cancel');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.classList.remove('hidden');
            });
        });
        
        modalCancel.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endsection