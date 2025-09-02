@extends('layouts.customer.app')

@section('content')
<div class="container mx-auto px-4 max-w-4xl mt-8 mb-12" x-data="reservasiApp()" x-cloak>
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Reservasi Meja</h1>
    
    <!-- Progress Steps -->
    <div class="flex justify-center mb-8">
        <div class="w-full">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 -z-10 transform -translate-y-1/2"></div>
                <div class="absolute top-1/2 left-0 h-1 bg-yellow-500 -z-10 transform -translate-y-1/2 transition-all duration-300" 
                     :style="`width: ${(step-1)*25}%`"></div>
                
                <template x-for="(stepNum, index) in 5" :key="index">
                    <button type="button" @click="goToStep(index+1)" 
                            class="flex flex-col items-center focus:outline-none"
                            :disabled="step <= index+1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mb-2 transition-all"
                            :class="{
                                'bg-yellow-500 text-white shadow-md': step > index+1,
                                'bg-white border-2 border-yellow-500 text-yellow-500': step === index+1,
                                'bg-white border-2 border-gray-300 text-gray-400': step < index+1
                            }">
                            <span x-text="index + 1" class="font-medium"></span>
                        </div>
                        <span class="text-xs font-medium" 
                              :class="{
                                  'text-yellow-600': step >= index+1,
                                  'text-gray-400': step < index+1
                              }" 
                              x-text="getStepName(index + 1)"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Step 1: Pilih Tanggal & Waktu -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100" x-show="step === 1" x-transition>
        <h2 class="text-xl font-bold mb-4 text-gray-800">1. Pilih Tanggal & Waktu</h2>
        
        <form @submit.prevent="cekKetersediaan()" class="space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Reservasi</label>
                    <input type="date" x-model="reservasi.tanggal" required min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Reservasi</label>
                    <select x-model="reservasi.waktu" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="">Pilih Waktu</option>
                        <option value="10:00">10:00 - 12:00</option>
                        <option value="12:00">12:00 - 14:00</option>
                        <option value="14:00">14:00 - 16:00</option>
                        <option value="18:00">18:00 - 20:00</option>
                        <option value="20:00">20:00 - 22:00</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tamu</label>
                    <input type="number" x-model.number="reservasi.jumlah_tamu" min="1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
            </div>
            
            <div class="pt-2">
                <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md font-medium transition">
                    Cek Ketersediaan Meja
                </button>
            </div>
        </form>
    </div>
    
    <!-- Step 2: Pilih Meja -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100" x-show="step === 2" x-transition>
        <h2 class="text-xl font-bold mb-4 text-gray-800">2. Pilih Meja</h2>
        
        <div class="mb-4">
            <h3 class="text-sm font-semibold mb-3 text-gray-600">Meja Tersedia</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <template x-for="meja in mejaTersedia.filter(m => m.capacity >= reservasi.jumlah_tamu)" :key="meja.id">
                    <button type="button" @click="pilihMeja(meja)"
                            class="border p-3 rounded-md text-center transition"
                            :class="{ 
                                'border-yellow-500 bg-yellow-50': reservasi.meja_id === meja.id,
                                'border-gray-200 hover:border-yellow-300': reservasi.meja_id !== meja.id
                            }">
                        <span x-text="'Meja ' + meja.number" class="font-medium text-gray-800"></span>
                        <span x-text="'(' + meja.capacity + ' orang)'" class="block text-xs text-gray-500"></span>
                    </button>
                </template>
            </div>
        </div>
        
        <div class="flex justify-between mt-6 pt-4 border-t">
            <button @click="step = 1" 
                    class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                Kembali
            </button>
            <button @click="step = 3" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-md font-medium transition disabled:opacity-50"
                    :disabled="!reservasi.meja_id">
                Lanjut
            </button>
        </div>
    </div>
    
    <!-- Step 3: Detail Reservasi -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100" x-show="step === 3" x-transition>
        <h2 class="text-xl font-bold mb-4 text-gray-800">3. Detail Reservasi</h2>
        
        <form @submit.prevent="submitReservasi()" class="space-y-4">
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" x-model="reservasi.nama" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" x-model="reservasi.email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" x-model="reservasi.telepon" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Khusus (opsional)</label>
                    <textarea x-model="reservasi.catatan" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" x-model="reservasi.pesan_menu" class="sr-only">
                        <div class="block bg-gray-300 w-10 h-5 rounded-full transition"
                             :class="{'bg-yellow-500': reservasi.pesan_menu}"></div>
                        <div class="dot absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition"
                             :class="{'transform translate-x-5': reservasi.pesan_menu}"></div>
                    </div>
                    <span class="ml-2 text-sm text-gray-700">Pesan menu sekarang</span>
                </label>
            </div>
            
            <div class="flex justify-between mt-6 pt-4 border-t">
                <button type="button" @click="step = 2" 
                        class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                    Kembali
                </button>
                <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-md font-medium transition">
                    <span x-text="reservasi.pesan_menu ? 'Lanjut ke Menu' : 'Lanjut ke Konfirmasi'"></span>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Step 4: Pemesanan Menu -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100" x-show="step === 4" x-transition>
        <h2 class="text-xl font-bold mb-4 text-gray-800">4. Pemesanan Menu</h2>

        <!-- Tabs Kategori -->
        <div class="flex flex-wrap gap-2 mb-4">
            <template x-for="(menus, category) in daftarMenu" :key="category">
                <button type="button"
                    class="px-3 py-1 rounded-md border text-sm font-medium transition"
                    :class="activeCategory === category 
                        ? 'bg-yellow-500 text-white border-yellow-500' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'"
                    @click="activeCategory = category">
                    <span x-text="getFormattedCategoryName(category)"></span>
                </button>
            </template>
        </div>

        <!-- Daftar Menu -->
        <div x-show="daftarMenu[activeCategory]" class="space-y-3">
            <template x-for="menu in daftarMenu[activeCategory]" :key="menu.id">
                <div class="flex items-center justify-between p-3 border rounded-md hover:shadow-xs transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-sm overflow-hidden bg-gray-100 bg-cover bg-center"
                            :style="'background-image: url(/images/' + (menu.image || 'menu-placeholder.jpg') + ')'">
                        </div>
                        <div>
                            <h4 x-text="menu.name" class="text-sm font-medium text-gray-800"></h4>
                            <p x-text="'Rp ' + menu.price.toLocaleString('id-ID')" class="text-xs text-yellow-600"></p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <button type="button" @click="kurangiJumlah(menu.id)" 
                                class="bg-gray-200 px-2 py-1 rounded-l-md hover:bg-gray-300">-</button>
                        <span x-text="getJumlahPesanan(menu.id)" class="px-2 py-1 bg-gray-100 text-sm w-8 text-center"></span>
                        <button type="button" @click="tambahJumlah(menu.id)" 
                                class="bg-gray-200 px-2 py-1 rounded-r-md hover:bg-gray-300">+</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Navigasi -->
        <div class="flex justify-between mt-6 pt-4 border-t">
            <button type="button" @click="step = 3" 
                    class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                Kembali
            </button>
            <button type="button" @click="simpanMenu()" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-md font-medium transition disabled:opacity-50"
                    :disabled="pesananMenu.length === 0">
                Simpan Menu
            </button>
        </div>
    </div>
    
    <!-- Step 5: Konfirmasi & Pembayaran -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100" x-show="step === 5" x-transition>
        <h2 class="text-xl font-bold mb-4 text-gray-800">5. Konfirmasi & Pembayaran</h2>
        
        <div class="mb-6 space-y-4">
            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <h3 class="text-sm font-semibold mb-3 text-gray-700 border-b pb-2">Detail Reservasi</h3>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-gray-500">Tanggal</p>
                        <p x-text="formatTanggal(reservasi.tanggal)" class="text-sm font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Waktu</p>
                        <p x-text="reservasi.waktu" class="text-sm font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Meja & Tamu</p>
                        <p class="text-sm font-medium text-gray-800" 
                            x-text="'Meja ' + getNamaMeja(reservasi.meja_id) + ', ' + reservasi.jumlah_tamu + ' Tamu'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Atas Nama</p>
                        <p x-text="reservasi.nama" class="text-sm font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p x-text="reservasi.email" class="text-sm font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Telepon</p>
                        <p x-text="reservasi.telepon" class="text-sm font-medium text-gray-800"></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500">Catatan</p>
                        <p x-text="reservasi.catatan || '-'" class="text-sm font-medium text-gray-800"></p>
                    </div>
                </div>
            </div>
            
            <template x-if="reservasi.pesan_menu && pesananMenu.length > 0">
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                    <h3 class="text-sm font-semibold mb-3 text-gray-700 border-b pb-2">Pesanan Menu</h3>
                    <div class="space-y-2">
                        <template x-for="item in pesananMenu" :key="item.menu_id">
                            <div class="flex justify-between items-center py-2 border-b">
                                <div class="flex items-center">
                                    <span x-text="getNamaMenu(item.menu_id)" class="text-sm font-medium text-gray-800"></span>
                                    <span x-text="' × ' + item.jumlah" class="text-xs text-gray-500 ml-1"></span>
                                </div>
                                <span x-text="'Rp ' + (item.jumlah * getHargaMenu(item.menu_id)).toLocaleString('id-ID')" 
                                      class="text-sm font-medium text-gray-700"></span>
                            </div>
                        </template>
                        <div class="flex justify-between pt-3 mt-1 border-t">
                            <span class="text-sm font-bold">Total Pesanan:</span>
                            <span x-text="'Rp ' + hitungTotalPesanan().toLocaleString('id-ID')" 
                                  class="text-sm font-bold text-yellow-600"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Metode Pembayaran -->
            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <h3 class="text-sm font-semibold mb-3 text-gray-700 border-b pb-2">Metode Pembayaran</h3>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 p-3 border rounded-md cursor-pointer hover:border-yellow-400 transition"
                           :class="{'border-yellow-500 bg-yellow-50': reservasi.payment_method === 'qris'}">
                        <input type="radio" x-model="reservasi.payment_method" value="qris" class="text-yellow-500 focus:ring-yellow-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">QRIS</p>
                            <p class="text-xs text-gray-500">Bayar dengan QRIS melalui berbagai aplikasi e-wallet</p>
                        </div>
                        <img src="{{ asset('images/qris.png') }}" alt="QRIS" class="h-8">
                    </label>

                    <label class="flex items-center space-x-3 p-3 border rounded-md cursor-pointer hover:border-yellow-400 transition"
                           :class="{'border-yellow-500 bg-yellow-50': reservasi.payment_method === 'bank_transfer'}">
                        <input type="radio" x-model="reservasi.payment_method" value="bank_transfer" class="text-yellow-500 focus:ring-yellow-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Transfer Bank</p>
                            <p class="text-xs text-gray-500">Transfer ke rekening BCA 1234567890 a.n. IBC Batu Tulis</p>
                        </div>
                        <img src="{{ asset('images/bca.png') }}" alt="Bank Transfer" class="h-8">
                    </label>
                </div>
            </div>

            <!-- Total Pembayaran -->
            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                <template x-if="reservasi.pesan_menu && pesananMenu.length > 0">
                    <div class="mb-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Pesanan:</span>
                            <span x-text="'Rp ' + hitungTotalPesanan().toLocaleString('id-ID')" 
                                  class="text-gray-800"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">DP (30%):</span>
                            <span x-text="'Rp ' + hitungDP().toLocaleString('id-ID')" 
                                  class="text-gray-800"></span>
                        </div>
                    </div>
                </template>
                <template x-if="!reservasi.pesan_menu">
                    <div class="mb-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">DP Reservasi:</span>
                            <span class="text-gray-800">Rp 300.000</span>
                        </div>
                    </div>
                </template>
                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="text-sm font-bold text-gray-800">Total Pembayaran:</span>
                    <span x-text="'Rp ' + hitungDP().toLocaleString('id-ID')" 
                          class="text-lg font-bold text-yellow-600"></span>
                </div>
                <template x-if="reservasi.payment_method === 'bank_transfer'">
                    <p class="text-xs text-gray-500 mt-1">
                        * Silakan transfer ke BCA 1234567890 a.n. Nama Restoran
                    </p>
                </template>
            </div>
        </div>
        
        <div class="flex justify-between mt-6 pt-4 border-t">
            <button @click="step = reservasi.pesan_menu ? 4 : 3" 
                    class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                Kembali
            </button>

            <button @click="prosesPembayaran()" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md font-medium transition shadow-sm flex items-center">
                <span x-text="reservasi.payment_method === 'cash' ? 'Selesaikan Reservasi' : 'Bayar Sekarang'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor" x-show="reservasi.payment_method !== 'cash'">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- QRIS Modal -->
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
             x-show="showQrisModal" x-transition>
            <div class="bg-white rounded-lg p-6 max-w-sm w-full" @click.away="showQrisModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Pembayaran QRIS</h3>
                    <button @click="showQrisModal = false" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <div class="bg-white p-4 rounded-md border border-gray-200 inline-block mb-4">
                        <img src="{{ asset('images/qris.png') }}" alt="QR Code" class="w-48 h-48 mx-auto">
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Scan QR code di atas menggunakan aplikasi e-wallet Anda</p>
                    <p class="text-sm font-medium text-gray-800 mb-4" 
                       x-text="'Rp ' + hitungDP().toLocaleString('id-ID')"></p>
                    <div class="flex space-x-2 justify-center">
                        <button @click="showQrisModal = false" 
                                class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                            Tutup
                        </button>
                        <button @click="konfirmasiPembayaran()" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-md font-medium transition">
                            Saya Sudah Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Transfer Modal -->
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
             x-show="showBankTransferModal" x-transition>
            <div class="bg-white rounded-lg p-6 max-w-sm w-full" @click.away="showBankTransferModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Pembayaran Transfer Bank</h3>
                    <button @click="showBankTransferModal = false" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <div class="bg-white p-4 rounded-md border border-gray-200 mb-4">
                        <img src="{{ asset('images/bca.png') }}" alt="BCA" class="h-12 mx-auto mb-2">
                        <p class="text-sm font-medium">1234567890</p>
                        <p class="text-sm">a.n. IBC Batu Tulis</p>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Silakan transfer sesuai jumlah DP berikut:</p>
                    <p class="text-sm font-medium text-gray-800 mb-4" 
                       x-text="'Rp ' + hitungDP().toLocaleString('id-ID')"></p>
                    <div class="flex space-x-2 justify-center">
                        <button @click="showBankTransferModal = false" 
                                class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                            Tutup
                        </button>
                        <button @click="konfirmasiPembayaran()" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-md font-medium transition">
                            Saya Sudah Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function reservasiApp() {
        return {
            step: Number(localStorage.getItem('reservasi_step')) || 1,
            reservasi: {
                tanggal: '',
                waktu: '',
                jumlah_tamu: 1,
                meja_id: null,
                nama: '',
                email: '',
                telepon: '',
                catatan: '',
                pesan_menu: false,
                payment_method: 'qris'
            },
            mejaTersedia: @json($tables),
            daftarMenu: @json($menus),
            pesananMenu: JSON.parse(localStorage.getItem('pesananMenu')) || [],
            activeCategory: Object.keys(@json($menus))[0],
            showQrisModal: false,
            showBankTransferModal: false,
            isLoading: false,

            getFormattedCategoryName(category) {
                const names = {
                    'tempoe-doeloe': 'Tempoe Doeloe',
                    'mie-ayam-hw': 'Mie Ayam H&W',
                    'makanan': 'Makanan',
                    'minuman': 'Minuman',
                    'snack': 'Snack'
                };
                return names[category] || category.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            },

            init() {
                const savedReservasi = localStorage.getItem('reservasi_data');
                if (savedReservasi) this.reservasi = JSON.parse(savedReservasi);
                
                const savedPesanan = localStorage.getItem('pesananMenu');
                if (savedPesanan) this.pesananMenu = JSON.parse(savedPesanan);
            },

            getStepName(stepNum) {
                return ['Tanggal & Waktu', 'Pilih Meja', 'Detail', 'Pesan Menu', 'Konfirmasi'][stepNum-1] || '';
            },
            
            goToStep(stepNum) {
                if (stepNum < this.step) this.step = stepNum;
            },
            
            cekKetersediaan() {
                if (!this.reservasi.jumlah_tamu || this.reservasi.jumlah_tamu < 1) {
                    alert('Jumlah tamu harus lebih dari 0');
                    return;
                }

                localStorage.setItem('reservasi_data', JSON.stringify({
                    tanggal: this.reservasi.tanggal,
                    waktu: this.reservasi.waktu,
                    jumlah_tamu: this.reservasi.jumlah_tamu
                }));

                this.step = 2;
            },
            
            pilihMeja(meja) {
                this.reservasi.meja_id = meja.id;
                localStorage.setItem('reservasi_data', JSON.stringify(this.reservasi));
            },
            
            getNamaMeja(mejaId) {
                const meja = this.mejaTersedia.find(m => m.id === mejaId);
                return meja ? meja.number : '';
            },
            
            submitReservasi() {
                localStorage.setItem('reservasi_data', JSON.stringify(this.reservasi));
                this.step = this.reservasi.pesan_menu ? 4 : 5;
                if (!this.reservasi.pesan_menu) {
                    this.pesananMenu = [];
                    localStorage.removeItem('pesananMenu');
                }
            },
            
            tambahJumlah(menuId) {
                const existing = this.pesananMenu.find(item => item.menu_id == menuId);
                if (existing) existing.jumlah++;
                else this.pesananMenu.push({ menu_id: menuId, jumlah: 1 });
                localStorage.setItem('pesananMenu', JSON.stringify(this.pesananMenu));
            },
            
            kurangiJumlah(menuId) {
                const index = this.pesananMenu.findIndex(item => item.menu_id == menuId);
                if (index !== -1) {
                    if (this.pesananMenu[index].jumlah > 1) this.pesananMenu[index].jumlah--;
                    else this.pesananMenu.splice(index, 1);
                    localStorage.setItem('pesananMenu', JSON.stringify(this.pesananMenu));
                }
            },
            
            getJumlahPesanan(menuId) {
                const item = this.pesananMenu.find(item => item.menu_id == menuId);
                return item ? item.jumlah : 0;
            },
            
            getNamaMenu(menuId) {
                for (const category in this.daftarMenu) {
                    const menu = this.daftarMenu[category].find(m => m.id == menuId);
                    if (menu) return menu.name;
                }
                return '';
            },
            
            getHargaMenu(menuId) {
                for (const category in this.daftarMenu) {
                    const menu = this.daftarMenu[category].find(m => m.id == menuId);
                    if (menu) return menu.price;
                }
                return 0;
            },
            
            simpanMenu() {
                this.step = 5;
            },
            
            formatTanggal(tanggal) {
                if (!tanggal) return '';
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(tanggal).toLocaleDateString('id-ID', options);
            },
            
            hitungTotalPesanan() {
                return this.pesananMenu.reduce((total, item) => {
                    return total + (item.jumlah * this.getHargaMenu(item.menu_id));
                }, 0);
            },

            hitungDP() {
                if (!this.reservasi.pesan_menu) {
                    return 300000; // DP fixed amount when not ordering food
                } else if (this.pesananMenu.length > 0) {
                    // 30% of total food order
                    return Math.round(this.hitungTotalPesanan() * 0.3);
                }
                return 0;
            },
            
            prosesPembayaran() {
                // If payment method is QRIS or Bank Transfer, show payment instructions
                if (this.reservasi.payment_method === 'qris') {
                    this.showQrisModal = true;
                } else if (this.reservasi.payment_method === 'bank_transfer') {
                    this.showBankTransferModal = true;
                } else {
                    // For cash payment, proceed directly
                    this.konfirmasiPembayaran();
                }
            },

            konfirmasiPembayaran() {
                this.isLoading = true;
                
                // Format data sesuai dengan yang diharapkan backend
                const formData = {
                    name: this.reservasi.nama,
                    email: this.reservasi.email,
                    phone: this.reservasi.telepon,
                    reservation_date: this.reservasi.tanggal,
                    reservation_time: this.reservasi.waktu,
                    guest_count: this.reservasi.jumlah_tamu,
                    table_id: this.reservasi.meja_id,
                    notes: this.reservasi.catatan || '',
                    payment_method: this.reservasi.payment_method,
                    with_preorder: this.reservasi.pesan_menu,
                    down_payment: this.hitungDP(),
                    menu_items: this.pesananMenu.map(item => ({
                        menu_id: item.menu_id,
                        jumlah: item.jumlah
                    }))
                };

                fetch('{{ route("reservation.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Bersihkan localStorage
                        localStorage.removeItem('reservasi_data');
                        localStorage.removeItem('pesananMenu');
                        localStorage.removeItem('reservasi_step');
                        
                        // Redirect ke halaman sukses
                        window.location.href = '{{ route("reservation.success", "") }}/?id=' + data.reservation_id;
                    } else {
                        alert('Gagal membuat reservasi: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    console.error('Error detail:', error);
                    alert('Terjadi kesalahan: ' + 
                          (error.message || 
                           (error.errors ? JSON.stringify(error.errors) : 'Silakan coba lagi')));
                })
                .finally(() => {
                    this.isLoading = false;
                    this.showQrisModal = false;
                    this.showBankTransferModal = false;
                });
            }
        }
    }
</script>
<style>
    [x-cloak] { display: none !important; }
    .dot {
        transition: all 0.2s ease-in-out;
    }
</style>
@endpush
@endsection