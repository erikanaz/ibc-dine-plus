<h2 class="text-xl font-semibold mb-4">Pilih Tanggal & Waktu</h2>

<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
        <input type="date" x-model="reservasi.tanggal" class="mt-1 block w-full border rounded-md p-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Waktu</label>
        <input type="time" x-model="reservasi.waktu" class="mt-1 block w-full border rounded-md p-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Jumlah Tamu</label>
        <input type="number" x-model="reservasi.jumlah_tamu" min="1" class="mt-1 block w-full border rounded-md p-2">
    </div>
    <div>
        <button @click="cekKetersediaan()" class="bg-yellow-500 text-white px-4 py-2 rounded mt-2">Lanjut</button>
    </div>
</div>
