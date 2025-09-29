<h2 class="text-xl font-semibold mb-4">Pilih Meja</h2>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <template x-for="meja in mejaTersedia" :key="meja.id">
        <div @click="reservasi.meja_id = meja.id"
             class="border rounded p-4 cursor-pointer transition"
             :class="{
                 'bg-yellow-100 border-yellow-500': reservasi.meja_id === meja.id,
                 'bg-white hover:bg-gray-100': reservasi.meja_id !== meja.id
             }">
            <h3 class="font-medium" x-text="meja.name"></h3>
            <p class="text-sm text-gray-500" x-text="`Kapasitas: ${meja.capacity} orang`"></p>
        </div>
    </template>
</div>

<div class="mt-4">
    <button @click="step--" class="px-4 py-2 rounded bg-gray-200 mr-2">Kembali</button>
    <button @click="step++" class="px-4 py-2 rounded bg-yellow-500 text-white">Lanjut</button>
</div>
