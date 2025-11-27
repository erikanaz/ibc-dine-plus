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
                    <input type="date" x-model="reservasi.tanggal" required min="{{ date('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->addDays(90)->format('Y-m-d') }}"
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
                    <input type="number" x-model.number="reservasi.jumlah_tamu" min="1" max="20" required
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
        
        <!-- Filter Sederhana -->
        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-sm font-semibold mb-3 text-gray-700">Filter Meja</h3>
            
            <div class="flex flex-wrap gap-3">
                <button @click="filterMeja = 'all'" 
                        class="px-4 py-2 rounded-md text-sm font-medium transition"
                        :class="filterMeja === 'all' 
                            ? 'bg-blue-500 text-white' 
                            : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-100'">
                    Semua Meja
                </button>
                
                <button @click="filterMeja = 'available'" 
                        class="px-4 py-2 rounded-md text-sm font-medium transition"
                        :class="filterMeja === 'available' 
                            ? 'bg-green-500 text-white' 
                            : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-100'">
                    Hanya Tersedia
                </button>
            </div>
            
            <div class="mt-3 text-xs text-gray-600">
                <template x-if="filterMeja === 'all'">
                    <p>Menampilkan <strong x-text="mejaTersedia.length"></strong> meja</p>
                </template>
                <template x-if="filterMeja === 'available'">
                    <p>Menampilkan <strong x-text="getAvailableTablesCount()"></strong> meja tersedia</p>
                </template>
            </div>
        </div>

        <!-- Info Pencarian -->
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
            <div class="flex items-start">
                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs text-blue-700">
                    Menampilkan ketersediaan meja untuk <strong x-text="reservasi.jumlah_tamu"></strong> tamu 
                    pada <strong x-text="formatTanggal(reservasi.tanggal)"></strong> 
                    pukul <strong x-text="reservasi.waktu"></strong>
                </p>
            </div>
        </div>
        
        <!-- Daftar Meja -->
        <div class="mb-4">
            <h3 class="text-sm font-semibold mb-3 text-gray-600">
                Daftar Meja
            </h3>
            
            <template x-if="getFilteredTables().length === 0">
                <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm" x-text="filterMeja === 'available' 
                        ? 'Tidak ada meja yang tersedia' 
                        : 'Tidak ada meja'"></p>
                </div>
            </template>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3" x-show="getFilteredTables().length > 0">
                <template x-for="meja in getFilteredTables()" :key="meja.id">
                    <button type="button" 
                            @click="meja.is_available ? pilihMeja(meja) : null"
                            class="border-2 p-4 rounded-lg text-center transition-all relative"
                            :disabled="!meja.is_available"
                            :class="getTableClasses(meja)">
                        
                        <!-- Check icon untuk meja dipilih -->
                        <div x-show="reservasi.meja_id === meja.id" 
                             class="absolute top-1 right-1 bg-yellow-500 rounded-full p-1 shadow-sm">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <!-- Nomor Meja -->
                        <div class="mb-2">
                            <span x-text="'Meja ' + meja.number" 
                                  class="font-bold text-lg"
                                  :class="getTableTextClass(meja)"></span>
                        </div>

                        <!-- Kapasitas -->
                        <div class="text-xs mb-1"
                             :class="getTableTextClass(meja)">
                            <span x-text="meja.capacity + ' orang'"></span>
                        </div>

                        <!-- Status Label -->
                        <div class="mt-2 pt-2 border-t"
                             :class="getTableBorderClass(meja)">
                            <span class="text-xs font-medium"
                                  :class="getTableTextClass(meja)"
                                  x-text="getTableStatusLabel(meja)"></span>
                        </div>
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
                <!-- Informasi User -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-blue-700">
                            Data dari profil Anda tersedia dan dapat digunakan dengan mengklik field.
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" x-model="reservasi.nama" required
                        placeholder="{{ Auth::user()->name }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 bg-white text-gray-800"
                        @focus="if(!reservasi.nama) reservasi.nama = '{{ Auth::user()->name }}'">
                    <p class="text-xs text-gray-500 mt-1" x-show="!reservasi.nama">
                        Klik field untuk menggunakan: <span class="font-medium">{{ Auth::user()->name }}</span>
                    </p>
                    <p class="text-xs text-green-500 mt-1" x-show="reservasi.nama && reservasi.nama === '{{ Auth::user()->name }}'">
                        ✓ Menggunakan data dari profil Anda
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-2">Email</label>
                        <input type="email" x-model="reservasi.email" required
                            placeholder="{{ Auth::user()->email }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 bg-white text-gray-800"
                            @focus="if(!reservasi.email) reservasi.email = '{{ Auth::user()->email }}'">
                        <p class="text-xs text-gray-500 mt-1" x-show="!reservasi.email">
                            Klik field untuk menggunakan: <span class="font-medium">{{ Auth::user()->email }}</span>
                        </p>
                        <p class="text-xs text-green-500 mt-1" x-show="reservasi.email && reservasi.email === '{{ Auth::user()->email }}'">
                            ✓ Menggunakan data dari profil Anda
                    </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-2">Nomor Telepon</label>
                        <input type="tel" x-model="reservasi.telepon" required
                            placeholder="{{ Auth::user()->phone ? Auth::user()->phone : 'Masukkan nomor telepon' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 bg-white text-gray-800"
                            @focus="if(!reservasi.telepon && '{{ Auth::user()->phone }}') reservasi.telepon = '{{ Auth::user()->phone }}'">
                        @if(Auth::user()->phone)
                            <p class="text-xs text-gray-500 mt-1" x-show="!reservasi.telepon">
                                Klik field untuk menggunakan: <span class="font-medium">{{ Auth::user()->phone }}</span>
                            </p>
                            <p class="text-xs text-green-500 mt-1" x-show="reservasi.telepon && reservasi.telepon === '{{ Auth::user()->phone }}'">
                                ✓ Menggunakan data dari profil Anda
                            </p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">Nomor telepon belum tersedia di profil Anda</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-2">Catatan Khusus (opsional)</label>
                    <textarea x-model="reservasi.catatan" rows="2" placeholder="Contoh: Meja dekat jendela, ada tamu ulang tahun, dll."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 bg-white text-gray-800"></textarea>
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
                            :style="'background-image: url(/images/menus/' + (menu.image || 'menu-placeholder.jpg') + ')'">
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
    
    <!-- Step 5: Konfirmasi Reservasi -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100" x-show="step === 5" x-transition>
        <h2 class="text-xl font-bold mb-4 text-gray-800">5. Konfirmasi Reservasi</h2>
        
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
            
            <!-- Promo Code Section -->
            <div class="bg-white p-4 rounded-md border border-gray-200">
                <h3 class="text-sm font-semibold mb-3 text-gray-700">Gunakan Kode Promo</h3>
                
                <!-- Form input promo awal -->
                <div class="flex space-x-2 mb-3" x-show="!reservasi.promo_terpakai">
                    <input type="text" x-model="reservasi.kode_promo" placeholder="Masukkan kode promo"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                    <button type="button" @click="applyPromo()" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition font-medium disabled:opacity-50"
                            :disabled="!reservasi.kode_promo.trim()">
                        Terapkan
                    </button>
                </div>
                
                <!-- Tampilkan promo yang sudah diterapkan -->
                <template x-if="reservasi.promo_terpakai">
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-green-800 font-medium" x-text="reservasi.promo_terpakai.nama"></span>
                                <p class="text-sm text-green-600" x-text="reservasi.promo_terpakai.deskripsi"></p>
                            </div>
                            <button type="button" @click="hapusPromo()" 
                                    class="text-red-500 hover:text-red-700 transition p-1 rounded-full hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-green-600 mt-1" x-text="'Diskon: ' + reservasi.promo_terpakai.diskon_text"></p>
                        {{-- <p class="text-xs text-green-600" x-text="'Status: ' + reservasi.promo_terpakai.status_label"></p> --}}
                    </div>
                </template>
                
                <!-- Form input promo baru untuk mengganti -->
                <div class="flex space-x-2 mt-3" x-show="reservasi.promo_terpakai">
                    <input type="text" x-model="reservasi.kode_promo_baru" placeholder="Masukkan kode promo baru"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                    <button type="button" @click="gantiPromo()" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition font-medium disabled:opacity-50"
                            :disabled="!reservasi.kode_promo_baru?.trim()">
                        Ganti Promo
                    </button>
                </div>
                
                <template x-if="promoError">
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                        <p class="text-red-800 text-sm" x-text="promoError"></p>
                    </div>
                </template>
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
                        
                        <!-- Subtotal Pesanan -->
                        <div class="flex justify-between pt-2">
                            <span class="text-sm text-gray-600">Subtotal:</span>
                            <span x-text="'Rp ' + hitungSubtotalPesanan().toLocaleString('id-ID')" 
                                  class="text-sm font-medium text-gray-700"></span>
                        </div>
                        
                        <!-- Diskon Promo -->
                        <template x-if="reservasi.promo_terpakai && reservasi.pesan_menu">
                            <div class="flex justify-between text-green-600">
                                <span class="text-sm" x-text="'Diskon (' + reservasi.promo_terpakai.nama + '):'"></span>
                                <span x-text="'-Rp ' + hitungDiskonPromo().toLocaleString('id-ID')" 
                                      class="text-sm font-medium"></span>
                            </div>
                        </template>
                        
                        <!-- Total Pesanan -->
                        <div class="flex justify-between pt-3 mt-1 border-t">
                            <span class="text-sm font-bold">Total Pesanan:</span>
                            <span x-text="'Rp ' + hitungTotalPesanan().toLocaleString('id-ID')" 
                                  class="text-sm font-bold text-yellow-600"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Informasi Pembayaran -->
            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                <h3 class="text-sm font-semibold mb-3 text-gray-700">Informasi Pembayaran</h3>
                
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
                
                <!-- Diskon Promo di DP -->
                <template x-if="reservasi.promo_terpakai && !reservasi.pesan_menu">
                    <div class="flex justify-between text-sm text-green-600">
                        <span x-text="'Diskon (' + reservasi.promo_terpakai.nama + '):'"></span>
                        <span x-text="'-Rp ' + hitungDiskonDP().toLocaleString('id-ID')"></span>
                    </div>
                </template>
                
                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="text-sm font-bold text-gray-800">Total DP:</span>
                    <span x-text="'Rp ' + hitungTotalDP().toLocaleString('id-ID')" 
                          class="text-lg font-bold text-yellow-600"></span>
                </div>
                
                <div class="mt-3 p-3 bg-white rounded border">
                    <p class="text-sm font-medium text-gray-800 mb-2">Transfer ke:</p>
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/bca.png') }}" alt="BCA" class="h-8">
                        <div>
                            <p class="text-sm font-bold">1234567890</p>
                            <p class="text-xs text-gray-600">a.n. IBC Batu Tulis</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        * Silakan lakukan pembayaran DP dalam <strong>24 jam</strong> setelah reservasi dibuat
                    </p>
                </div>
            </div>

            <!-- Checklist Konfirmasi -->
            <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                <h3 class="text-sm font-semibold mb-3 text-gray-700">Konfirmasi</h3>
                <div class="space-y-2">
                    <label class="flex items-start space-x-2">
                        <input type="checkbox" x-model="konfirmasi.syarat" class="mt-0.5">
                        <span class="text-sm text-gray-700">Saya menyetujui syarat & ketentuan reservasi</span>
                    </label>
                    <label class="flex items-start space-x-2">
                        <input type="checkbox" x-model="konfirmasi.pembayaran" class="mt-0.5">
                        <span class="text-sm text-gray-700">Saya akan melakukan pembayaran DP dalam 24 jam</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="flex justify-between mt-6 pt-4 border-t">
            <button @click="step = reservasi.pesan_menu ? 4 : 3" 
                    class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                Kembali
            </button>

            <button @click="submitReservasiFinal()" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md font-medium transition shadow-sm flex items-center disabled:opacity-50"
                    :disabled="!konfirmasi.syarat || !konfirmasi.pembayaran || isLoading">
                <template x-if="isLoading">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="isLoading ? 'Memproses...' : 'Buat Reservasi'"></span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reservasiApp() {
    return {
        userId: {{ Auth::id() }},
        step: Number(localStorage.getItem(`reservasi_step_${ {{ Auth::id() }} }`)) || 1,
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
            kode_promo: '',
            kode_promo_baru: '',
            promo_terpakai: null
        },
        konfirmasi: {
            syarat: false,
            pembayaran: false
        },
        mejaTersedia: JSON.parse(localStorage.getItem(`meja_tersedia_${ {{ Auth::id() }} }`)) || @json($tables),
        daftarMenu: @json($menus),
        pesananMenu: JSON.parse(localStorage.getItem(`pesananMenu_${ {{ Auth::id() }} }`)) || [],
        activeCategory: Object.keys(@json($menus))[0],
        promoError: null,
        isLoading: false,
        calculations: {
            subtotal_pesanan: 0,
            diskon_promo: 0,
            total_pesanan: 0,
            dp: 0,
            diskon_dp: 0,
            total_dp: 0
        },

        // Filter sederhana
        filterMeja: 'all',

        // Helper functions
        getStepName(step) {
            const steps = {
                1: 'Tanggal & Waktu',
                2: 'Pilih Meja',
                3: 'Detail',
                4: 'Menu',
                5: 'Konfirmasi'
            };
            return steps[step] || '';
        },

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

        // Filter tables
        getFilteredTables() {
            if (this.filterMeja === 'available') {
                return this.mejaTersedia.filter(meja => meja.is_available);
            }
            return this.mejaTersedia;
        },

        getAvailableTablesCount() {
            return this.mejaTersedia.filter(meja => meja.is_available).length;
        },

        init() {
            const savedReservasi = localStorage.getItem(`reservasi_data_${this.userId}`);
            if (savedReservasi) {
                const savedData = JSON.parse(savedReservasi);
                this.reservasi = { ...this.reservasi, ...savedData };
            }
            
            const savedPesanan = localStorage.getItem(`pesananMenu_${this.userId}`);
            if (savedPesanan) {
                this.pesananMenu = JSON.parse(savedPesanan);
            }

            const savedMejaTersedia = localStorage.getItem(`meja_tersedia_${this.userId}`);
            if (savedMejaTersedia && this.step === 2) {
                this.mejaTersedia = JSON.parse(savedMejaTersedia);
            }

            if (this.step >= 5) {
                this.calculatePrice();
            }
        },

        goToStep(stepNumber) {
            if (stepNumber < this.step) {
                this.step = stepNumber;
                localStorage.setItem(`reservasi_step_${this.userId}`, String(stepNumber));
            }
        },

        async cekKetersediaan() {
            if (!this.reservasi.tanggal || !this.reservasi.waktu || !this.reservasi.jumlah_tamu) {
                alert('Harap isi semua field');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('{{ route("reservation.check-availability") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tanggal: this.reservasi.tanggal,
                        waktu: this.reservasi.waktu,
                        jumlah_tamu: this.reservasi.jumlah_tamu
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.mejaTersedia = data.all_tables;
                    localStorage.setItem(`meja_tersedia_${this.userId}`, JSON.stringify(data.all_tables));
                    
                    if (this.reservasi.meja_id) {
                        const mejaYangDipilih = this.mejaTersedia.find(m => m.id === this.reservasi.meja_id);
                        if (mejaYangDipilih && !mejaYangDipilih.is_available) {
                            this.reservasi.meja_id = null;
                            localStorage.setItem(`reservasi_data_${this.userId}`, JSON.stringify(this.reservasi));
                        }
                    }
                    
                    localStorage.setItem(`reservasi_data_${this.userId}`, JSON.stringify({
                        tanggal: this.reservasi.tanggal,
                        waktu: this.reservasi.waktu,
                        jumlah_tamu: this.reservasi.jumlah_tamu
                    }));
                    this.step = 2;
                    localStorage.setItem(`reservasi_step_${this.userId}`, '2');
                } else {
                    alert('Gagal memeriksa ketersediaan meja');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memeriksa ketersediaan');
            } finally {
                this.isLoading = false;
            }
        },

        // Helper functions untuk styling meja
        getTableClasses(meja) {
            if (this.reservasi.meja_id === meja.id) {
                return 'border-yellow-500 bg-yellow-50 shadow-md';
            }
            
            if (meja.is_available) {
                return 'border-green-500 bg-green-50 hover:border-green-600 hover:shadow-md cursor-pointer';
            }
            
            return 'border-gray-400 bg-gray-100 cursor-not-allowed opacity-50';
        },

        getTableTextClass(meja) {
            if (this.reservasi.meja_id === meja.id) {
                return 'text-yellow-700';
            }
            
            if (meja.is_available) {
                return 'text-green-700';
            }
            
            return 'text-gray-600';
        },

        getTableBorderClass(meja) {
            if (this.reservasi.meja_id === meja.id) {
                return 'border-yellow-300';
            }
            
            if (meja.is_available) {
                return 'border-green-300';
            }
            
            return 'border-gray-300';
        },

        getTableStatusLabel(meja) {
            if (this.reservasi.meja_id === meja.id) {
                return 'Dipilih';
            }
            
            if (meja.is_available) {
                return 'Tersedia';
            }
            
            if (meja.effective_status === 'reserved_slot') {
                return 'Sudah Dipesan';
            }
            
            if (meja.status === 'occupied') {
                return 'Terisi';
            }
            
            if (meja.status === 'maintenance') {
                return 'Maintenance';
            }
            
            if (meja.is_capacity_insufficient) {
                return 'Kapasitas Kurang';
            }
            
            return 'Tidak Tersedia';
        },

        async applyPromo() {
            this.promoError = null;
            const kode = this.reservasi.kode_promo.trim();
            
            if (!kode) {
                this.promoError = 'Masukkan kode promo';
                return;
            }

            try {
                const response = await fetch('{{ route("reservation.apply-promo") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kode_promo: kode
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.reservasi.promo_terpakai = data.promo;
                    this.reservasi.kode_promo = '';
                    await this.calculatePrice();
                    localStorage.setItem(`reservasi_data_${this.userId}`, JSON.stringify(this.reservasi));
                } else {
                    this.promoError = data.message;
                    this.reservasi.promo_terpakai = null;
                }
            } catch (error) {
                console.error('Error:', error);
                this.promoError = 'Terjadi kesalahan saat menerapkan promo';
                this.reservasi.promo_terpakai = null;
            }
        },

        async gantiPromo() {
            this.promoError = null;
            const kode = this.reservasi.kode_promo_baru.trim();
            
            if (!kode) {
                this.promoError = 'Masukkan kode promo baru';
                return;
            }

            try {
                const response = await fetch('{{ route("reservation.apply-promo") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kode_promo: kode
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.reservasi.promo_terpakai = data.promo;
                    this.reservasi.kode_promo_baru = '';
                    await this.calculatePrice();
                    localStorage.setItem(`reservasi_data_${this.userId}`, JSON.stringify(this.reservasi));
                } else {
                    this.promoError = data.message;
                }
            } catch (error) {
                console.error('Error:', error);
                this.promoError = 'Terjadi kesalahan saat menerapkan promo';
            }
        },

        hapusPromo() {
            this.reservasi.promo_terpakai = null;
            this.reservasi.kode_promo = '';
            this.reservasi.kode_promo_baru = '';
            this.promoError = null;
            localStorage.setItem(`reservasi_data_${this.userId}`, JSON.stringify(this.reservasi));
            this.calculatePrice();
        },

        async calculatePrice() {
            try {
                const response = await fetch('{{ route("reservation.calculate-price") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        pesan_menu: this.reservasi.pesan_menu,
                        menu_items: this.pesananMenu,
                        promo_id: this.reservasi.promo_terpakai?.id || null,
                        promo_discount: this.reservasi.promo_terpakai?.discount || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.calculations = data.calculations;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },

        pilihMeja(meja) {
            this.reservasi.meja_id = meja.id;
            localStorage.setItem(`reservasi_data_${this.userId}`, JSON.stringify(this.reservasi));
        },

        submitReservasi() {
            if (!this.reservasi.nama) {
                this.reservasi.nama = '{{ Auth::user()->name }}';
            }
            if (!this.reservasi.email) {
                this.reservasi.email = '{{ Auth::user()->email }}';
            }
            if (!this.reservasi.telepon && '{{ Auth::user()->phone }}') {
                this.reservasi.telepon = '{{ Auth::user()->phone }}';
            }
            
            if (!this.reservasi.nama || !this.reservasi.email || !this.reservasi.telepon) {
                alert('Harap lengkapi data diri terlebih dahulu');
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.reservasi.email)) {
                alert('Format email tidak valid');
                return;
            }

            const phoneRegex = /^[0-9]{10,15}$/;
            const cleanPhone = this.reservasi.telepon.replace(/\D/g, '');
            if (!phoneRegex.test(cleanPhone)) {
                alert('Format nomor telepon tidak valid. Minimal 10 digit angka');
                return;
            }
            
            this.reservasi.telepon = cleanPhone;

            localStorage.setItem(`reservasi_data_${this.userId}`, JSON.stringify(this.reservasi));
            this.step = this.reservasi.pesan_menu ? 4 : 5;
            localStorage.setItem(`reservasi_step_${this.userId}`, String(this.step));
            
            if (!this.reservasi.pesan_menu) {
                this.pesananMenu = [];
                localStorage.removeItem(`pesananMenu_${this.userId}`);
            }
            
            if (this.step === 5) {
                this.calculatePrice();
            }
        },

        tambahJumlah(menuId) {
            const existing = this.pesananMenu.find(item => item.menu_id == menuId);
            if (existing) {
                existing.jumlah++;
            } else {
                this.pesananMenu.push({ menu_id: menuId, jumlah: 1 });
            }
            localStorage.setItem(`pesananMenu_${this.userId}`, JSON.stringify(this.pesananMenu));
            this.calculatePrice();
        },

        kurangiJumlah(menuId) {
            const index = this.pesananMenu.findIndex(item => item.menu_id == menuId);
            if (index !== -1) {
                if (this.pesananMenu[index].jumlah > 1) {
                    this.pesananMenu[index].jumlah--;
                } else {
                    this.pesananMenu.splice(index, 1);
                }
                localStorage.setItem(`pesananMenu_${this.userId}`, JSON.stringify(this.pesananMenu));
                this.calculatePrice();
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
            localStorage.setItem(`reservasi_step_${this.userId}`, '5');
            this.calculatePrice();
        },

        // Fungsi perhitungan untuk tampilan
        hitungSubtotalPesanan() {
            return this.calculations.subtotal_pesanan || 0;
        },

        hitungDiskonPromo() {
            return this.calculations.diskon_promo || 0;
        },

        hitungTotalPesanan() {
            return this.calculations.total_pesanan || 0;
        },

        hitungDP() {
            return this.calculations.dp || 0;
        },

        hitungDiskonDP() {
            return this.calculations.diskon_dp || 0;
        },

        hitungTotalDP() {
            return this.calculations.total_dp || 0;
        },

        formatTanggal(tanggal) {
            if (!tanggal) return '';
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(tanggal).toLocaleDateString('id-ID', options);
        },

        getNamaMeja(mejaId) {
            const meja = this.mejaTersedia.find(m => m.id === mejaId);
            return meja ? meja.number : '';
        },

        async submitReservasiFinal() {
            if (!this.konfirmasi.syarat || !this.konfirmasi.pembayaran) {
                alert('Harap centang semua konfirmasi terlebih dahulu');
                return;
            }

            this.isLoading = true;

            const formData = new FormData();
            formData.append('name', this.reservasi.nama);
            formData.append('email', this.reservasi.email);
            formData.append('phone', this.reservasi.telepon);
            formData.append('reservation_date', this.reservasi.tanggal);
            formData.append('reservation_time', this.reservasi.waktu);
            formData.append('guest_count', String(this.reservasi.jumlah_tamu));
            formData.append('table_id', String(this.reservasi.meja_id));
            formData.append('notes', this.reservasi.catatan || '');
            formData.append('with_preorder', this.reservasi.pesan_menu ? '1' : '0');
            formData.append('down_payment', String(this.calculations.total_dp));
            formData.append('promo_id', this.reservasi.promo_terpakai ? String(this.reservasi.promo_terpakai.id) : '');
            
            if (this.reservasi.pesan_menu) {
                this.pesananMenu.forEach((item, i) => {
                    formData.append(`menu_items[${i}][menu_id]`, String(item.menu_id));
                    formData.append(`menu_items[${i}][jumlah]`, String(item.jumlah));
                });
            }

            try {
                const response = await fetch('{{ route("reservation.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.removeItem(`reservasi_data_${this.userId}`);
                    localStorage.removeItem(`pesananMenu_${this.userId}`);
                    localStorage.removeItem(`reservasi_step_${this.userId}`);
                    localStorage.removeItem(`meja_tersedia_${this.userId}`);
                    
                    window.location.href = `/reservation/success/${data.reservation_id}`;
                } else {
                    alert('Gagal membuat reservasi: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan/server. Silakan coba lagi.');
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
<style>
    [x-cloak] { display: none !important; }
    .dot { transition: all 0.2s ease-in-out; }
</style>
@endpush
@endsection