@extends('layouts.customer.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div x-data="orderListHandler()" x-init="init()" class="relative">
    <!-- Tombol Pesanan -->
    <button @click="showOrderList = true"
        class="fixed bottom-6 right-6 gold-bg text-white text-2xl text-center p-4 rounded-full shadow-lg z-40">
        🍽️
        <span x-show="orderList.length > 0"
            x-text="orderList.reduce((sum, i) => i.qty + sum, 0)"
            class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-1"
            x-cloak>
        </span>
    </button>

    <!-- Overlay -->
    <div x-show="showOrderList"
         x-transition.opacity
         @click="showOrderList = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-40"
         x-cloak>
    </div>

    <!-- Sidebar Pesanan -->
    <div x-show="showOrderList"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg z-50 p-6 flex flex-col"
        x-cloak>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Pesanan Saya</h2>
            <button @click="showOrderList = false" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
        </div>

        <div class="flex-grow overflow-y-auto">
            <template x-if="orderList.length === 0">
                <p class="text-gray-500">Belum ada pesanan.</p>
            </template>
            <ul class="space-y-3">
                <template x-for="(item, index) in orderList" :key="index">
                    <li class="border-b pb-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold mb-2" x-text="item.name"></p>
                                <div class="flex items-center text-xs">
                                    <button @click="decreaseQty(index)" class="bg-gray-200 px-1.5 py-0.5 rounded-l-md">−</button>
                                    <span x-text="item.qty" class="px-2.5 py-0.5 bg-gray-100"></span>
                                    <button @click="increaseQty(index)" class="bg-gray-200 px-1.5 py-0.5 rounded-r-md">+</button>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium" x-text="`Rp ${item.qty * item.price}`"></p>
                                <button @click="removeItem(index)"
                                    class="text-xs text-red-500 hover:underline mt-1 inline-block">
                                    ❌ Hapus
                                </button>
                            </div>
                        </div>
                    </li>
                </template>

            </ul>
        </div>

        <button
            @click="if(orderList.length > 0 && confirm('Hapus semua pesanan?')) clearOrderList()"
            :disabled="orderList.length === 0"
            :class="orderList.length === 0 ? 'opacity-30 cursor-default' : 'text-red-500 hover:text-red-700'"
            class="text-sm flex items-center gap-1 transition-colors mt-2"
            title="Kosongkan Pesanan">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span class="hidden sm:inline"> Kosongkan</span>
        </button>

        <div class="mt-2 border-t pt-3">
            <div class="flex justify-between font-bold text-lg">
                <span>Total:</span>
                <span x-text="`Rp ${total()}`"></span>
            </div>
            <button @click="submitOrder" class="mt-4 w-full gold-bg text-white py-2 rounded hover:bg-opacity-90">Check Out</button>
        </div>

    </div>

    <!-- Menu Header -->
    <section class="menu-header bg-cover bg-center py-32" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/menu-hero.jpg')">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">MENU KAMI</h1>
            <p class="text-xl max-w-2xl mx-auto">Nikmati berbagai hidangan ikan bakar dan seafood dengan bumbu rahasia kami</p>
        </div>
    </section>

    @php
    $displayNames = [
        'mie-ayam-hw' => 'Mie Ayam H&W',
        'tempoe-doeloe' => 'Tempo Doeloe',
    ];
    @endphp

    <!-- Menu Navigation -->
    <section class="py-3 bg-white sticky top-16 z-10 shadow-md scroll-smooth">
        <div class="container mx-auto px-4">
            <div x-data x-init="$parent.activeTab = 'signatures'" class="flex overflow-x-auto space-x-4">
                @foreach ($menus as $category => $items)
                    <a 
                        href="#{{ $category }}" 
                        @click="activeTab = '{{ $category }}'"
                        :class="activeTab === '{{ $category }}'
                            ? 'gold-text gold-border border-2 scale-103 shadow-md'
                            : 'text-gray-600 hover:gold-text hover:gold-border border-2 border-transparent'"
                        class="whitespace-nowrap font-medium px-4 py-2 rounded-md transition duration-300 ease-in-out transform">
                        {{ $displayNames[$category] ?? ucwords(str_replace('-', ' ', $category)) }}
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    @foreach ($menus as $category => $items)
    <section id="{{ $category }}" class="py-16 bg-gray-50 scroll-mt-32">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">
                    {{ strtoupper($displayNames[$category] ?? str_replace('-', ' ', $category)) }}
                </h2>
                <div class="w-20 h-1 gold-bg mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($items as $item)
                    <div class="relative group bg-gray-50 rounded-lg overflow-hidden shadow-md menu-card transition duration-300 flex flex-col h-full">
                        {{-- Gambar --}}
                        <div class="{{ $category === 'drinks' ? 'h-96' : 'h-60' }} bg-cover bg-center" 
                            style="background-image: url('/images/{{ $item->image }}')">
                        </div>

                        {{-- Overlay jika tidak tersedia --}}
                        @if (!$item->is_available)
                            <div class="absolute inset-0 bg-black bg-opacity-65 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-10">
                                <span class="text-lg font-medium">Menu tidak tersedia</span>
                            </div>
                        @endif

                        {{-- Konten --}}
                        <div class="p-6 flex flex-col flex-grow z-0">
                            <h3 class="text-xl font-bold mb-2">{{ $item->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $item->description }}</p>
                            <div class="flex justify-between items-center mt-auto">
                                <span class="font-bold gold-text">Rp {{ number_format($item->price, 0, ',', '.') }}</span>

                                @if($item->is_available)
                                    <button 
                                        @click="addToOrderList({ id: {{ $item->id }}, name: '{{ $item->name }}', price: {{ $item->price }} })" 
                                        class="flex items-center gap-2 gold-bg hover:bg-opacity-100 text-white px-4 py-2 rounded-md text-sm transition duration-300 shadow-md hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Pesan
                                    </button>
                                @else
                                    <button 
                                        disabled 
                                        class="flex items-center gap-2 gold-bg hover:bg-opacity-100 text-white px-4 py-2 rounded-md text-sm transition duration-300 shadow-md cursor-not-allowed opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Pesan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endforeach

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs" defer></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
function orderListHandler() {
    return {
        showOrderList: false,
        confirmClear: false,
        orderList: [],
        activeTab: 'signatures',
        // orderType: 'dine-in',
        // paymentMethod: 'cash',

        init() {
            // Memuat data pesanan dari localStorage saat komponen diinisialisasi
            const savedOrderList = localStorage.getItem('orderList');
            if (savedOrderList) {
                this.orderList = JSON.parse(savedOrderList);
            }
        },

        addToOrderList(item) {
            const found = this.orderList.find(i => i.menu_id === item.id); // Cari berdasarkan ID bukan name
            if (found) {
                found.qty++;
            } else {
                this.orderList.push({ 
                    menu_id: item.id,        // Wajib - ID dari database
                    name: item.name, 
                    price: item.price,
                    qty: 1,
                    note: ''           // Opsional
                });
            }
            this.saveToLocalStorage();
        },

        increaseQty(index) {
            this.orderList[index].qty++;
            this.saveToLocalStorage();
        },

        decreaseQty(index) {
            if (this.orderList[index].qty > 1) {
                this.orderList[index].qty--;
            } else {
                this.orderList.splice(index, 1);
            }
            this.saveToLocalStorage();
        },

        removeItem(index) {
            this.orderList.splice(index, 1);
            this.saveToLocalStorage();
        },

        clearOrderList() {
            this.orderList = [];
            this.confirmClear = false;
            this.saveToLocalStorage();
        },

        total() {
            return this.orderList.reduce((sum, i) => sum + (i.qty * i.price), 0);
        },

        // Fungsi untuk menyimpan data ke localStorage
        saveToLocalStorage() {
            localStorage.setItem('orderList', JSON.stringify(this.orderList));
        },

        submitOrder() {
            if (this.orderList.length === 0) {
                alert("Belum ada item yang dipesan.");
                return;
            }

            // Simpan data lengkap ke localStorage
            const orderData = {
                items: this.orderList,
                // orderType: this.orderType,
                // paymentMethod: this.paymentMethod,
                total: this.total()
            };
            
            localStorage.setItem('orderData', JSON.stringify(orderData));
            window.location.href = '{{ route("order.checkout") }}';
        }

    }
}
</script>
@endpush