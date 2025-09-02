@extends('layouts.customer.app')

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Checkout Pesanan</h1>
            <div class="w-20 h-1 bg-yellow-500 mx-auto"></div>
        </div>

        <div x-data="checkoutPage()" x-init="init()" class="bg-white p-8 rounded-xl shadow-lg space-y-8">
            <!-- List Pesanan -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                    </svg>
                    Rincian Pesanan
                </h2>
                
                <template x-if="orderList.length === 0">
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-gray-500 mt-2">Tidak ada item dalam pesanan</p>
                        <a href="{{ route('order.index') }}" class="inline-block mt-4 bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">
                            Kembali ke Menu
                        </a>
                    </div>
                </template>
                
                <ul class="divide-y mb-3" x-show="orderList.length > 0">
                    <template x-for="(item, index) in orderList" :key="index">
                        <li class="py-4 flex justify-between items-start">
                            <div class="flex items-start gap-3">
                                <div class="bg-gray-100 rounded-md p-2 w-9 h-9 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-gray-700" x-text="index + 1"></span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800" x-text="item.name"></p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-sm text-gray-500 mr-2">x<span x-text="item.qty" class="ml-1"></span></span>
                                        <span class="text-sm text-yellow-600 font-medium"> Rp<span x-text="item.price.toLocaleString('id-ID')"></span></span>
                                    </div>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800" x-text="`Rp ${(item.qty * item.price).toLocaleString('id-ID')}`"></p>
                        </li>
                    </template>
                </ul>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea x-model="orderNote" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                        placeholder="Contoh: Tolong tanpa sambal, atau saya ambil jam 6 sore."></textarea>
                </div>
            </div>

            <!-- Form Pemesanan -->
            <div class="space-y-6" x-show="orderList.length > 0">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opsi Pesanan</label>
                        <select x-model="orderType" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                            <option value="dine-in">Makan di Tempat</option>
                            <option value="takeaway">Bungkus / Takeaway</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <div class="space-y-3">
                            <label class="flex items-center space-x-3 p-3 border rounded-md cursor-pointer hover:border-yellow-400 transition"
                                   :class="{'border-yellow-500 bg-yellow-50': paymentMethod === 'qris'}">
                                <input type="radio" x-model="paymentMethod" value="qris" class="text-yellow-500 focus:ring-yellow-500">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">QRIS</p>
                                    <p class="text-xs text-gray-500">Bayar dengan QRIS melalui berbagai aplikasi e-wallet</p>
                                </div>
                                <img src="{{ asset('images/qris.png') }}" alt="QRIS" class="h-8">
                            </label>

                            <label class="flex items-center space-x-3 p-3 border rounded-md cursor-pointer hover:border-yellow-400 transition"
                                   :class="{'border-yellow-500 bg-yellow-50': paymentMethod === 'bank_transfer'}">
                                <input type="radio" x-model="paymentMethod" value="bank_transfer" class="text-yellow-500 focus:ring-yellow-500">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">Transfer Bank</p>
                                    <p class="text-xs text-gray-500">Transfer ke rekening BCA 1234567890 a.n. IBC Batu Tulis</p>
                                </div>
                                <img src="{{ asset('images/bca.png') }}" alt="Bank Transfer" class="h-8">
                            </label>

                            <label class="flex items-center space-x-3 p-3 border rounded-md cursor-pointer hover:border-yellow-400 transition"
                                   :class="{'border-yellow-500 bg-yellow-50': paymentMethod === 'cash'}">
                                <input type="radio" x-model="paymentMethod" value="cash" class="text-yellow-500 focus:ring-yellow-500">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">Bayar di Tempat</p>
                                    <p class="text-xs text-gray-500">Bayar langsung saat pesanan diantar/dimakan di tempat</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Total Pembayaran -->
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" x-text="`Rp ${total().toLocaleString('id-ID')}`"></span>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold text-gray-800">
                        <span>Total Pembayaran:</span>
                        <span class="text-yellow-600" x-text="`Rp ${total().toLocaleString('id-ID')}`"></span>
                    </div>
                </div>

                <!-- Tombol Konfirmasi -->
                <button @click="submitOrder" 
                        :disabled="isSubmitting"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg font-bold text-base transition flex items-center justify-center gap-2">
                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Memproses...' : 'Konfirmasi & Bayar'"></span>
                </button>
            </div>

            <!-- Modal QRIS -->
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
                           x-text="'Rp ' + total().toLocaleString('id-ID')"></p>
                        <div class="flex space-x-2 justify-center">
                            <button @click="showQrisModal = false" 
                                    class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                                Tutup
                            </button>
                            <button @click="processPayment()" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-md font-medium transition">
                                Saya Sudah Bayar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Transfer Bank -->
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
                        <p class="text-sm text-gray-600 mb-2">Silakan transfer sesuai jumlah berikut:</p>
                        <p class="text-sm font-medium text-gray-800 mb-4" 
                           x-text="'Rp ' + total().toLocaleString('id-ID')"></p>
                        <div class="flex space-x-2 justify-center">
                            <button @click="showBankTransferModal = false" 
                                    class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-md hover:bg-gray-50 transition">
                                Tutup
                            </button>
                            <button @click="processPayment()" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-md font-medium transition">
                                Saya Sudah Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function checkoutPage() {
    return {
        orderList: [],
        orderType: 'dine-in',
        paymentMethod: 'cash',
        orderNote: '',
        isSubmitting: false,
        showQrisModal: false,
        showBankTransferModal: false,
        csrfToken: '',

        init() {
            this.loadOrderList();
            this.getCsrfToken();
        },

        getCsrfToken() {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            this.csrfToken = csrfMeta ? csrfMeta.content : '';
        },

        loadOrderList() {
            try {
                const stored = localStorage.getItem('orderList');
                this.orderList = stored ? JSON.parse(stored) : [];
            } catch (error) {
                console.error('Error loading order:', error);
                this.orderList = [];
            }
        },

        total() {
            return this.orderList.reduce((sum, i) => sum + (i.qty * i.price), 0);
        },

        async submitOrder() {
            if (this.orderList.length === 0) {
                alert("Pesanan kosong.");
                return;
            }

            // if (this.paymentMethod === 'cash') {
            //     await this.processPayment(); // Langsung proses tanpa modal
            // }

            this.isSubmitting = true;

            try {
                if (this.paymentMethod === 'qris') {
                    this.showQrisModal = true;
                    this.isSubmitting = false;
                } 
                else if (this.paymentMethod === 'bank_transfer') {
                    this.showBankTransferModal = true;
                    this.isSubmitting = false;
                }
                else {
                    await this.processPayment();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
                this.isSubmitting = false;
            }
        },

        async processPayment() {
            try {
                const orderData = {
                    items: this.orderList.map(item => ({
                        menu_id: item.menu_id || item.id, // Pastikan ini ada dan sesuai dengan ID menu
                        price: item.price,
                        qty: item.quantity || item.qty, // Sesuaikan dengan struktur data Anda
                        note: item.note || '' // Optional
                    })),
                    order_type: this.orderType,
                    payment_method: this.paymentMethod,
                    total: this.total(),
                    order_note: this.orderNote
                };

                console.log('Data yang dikirim:', orderData); // Untuk debugging
                
                const response = await fetch("{{ route('order.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify(orderData)
                });

                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || "Gagal memproses pesanan");
                }

                localStorage.removeItem('orderList');
                window.location.href = `/order/${result.order_id}/success`;

            } catch (error) {
                console.error('Payment processing error:', error);
                alert(`Terjadi kesalahan: ${error.message}`);
            } finally {
                this.isSubmitting = false;
                this.showQrisModal = false;
                this.showBankTransferModal = false;
            }
        }
    }
}
</script>
@endpush