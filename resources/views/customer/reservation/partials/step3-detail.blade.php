<h2 class="text-xl font-semibold mb-4">Detail Reservasi</h2>

<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" x-model="reservasi.nama" class="mt-1 block w-full border rounded-md p-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" x-model="reservasi.email" class="mt-1 block w-full border rounded-md p-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
        <input type="text" x-model="reservasi.telepon" class="mt-1 block w-full border rounded-md p-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Catatan</label>
        <textarea x-model="reservasi.catatan" rows="3" class="mt-1 block w-full border rounded-md p-2"></textarea>
    </div>
    <div class="flex items-center mt-2">
        <input type="checkbox" x-model="reservasi.pesan_menu" id="pesanMenu" class="mr-2">
        <label for="pesanMenu" class="text-gray-700">Ingin memesan menu</label>
    </div>
</div>

<div class="mt-4">
    <button @click="step--" class="px-4 py-2 rounded bg-gray-200 mr-2">Kembali</button>
    <button @click="step++" class="px-4 py-2 rounded bg-yellow-500 text-white">Lanjut</button>
</div>
