<section>
    <div class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <i class="fas fa-user text-yellow-500 mr-2"></i>Nama Lengkap
            </label>
            <input id="name" name="name" type="text" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <i class="fas fa-envelope text-yellow-500 mr-2"></i>Alamat Email
            </label>
            <input id="email" name="email" type="email" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition bg-gray-50"
                   value="{{ old('email', $user->email) }}" required autocomplete="username" disabled>
            <p class="mt-2 text-xs text-gray-500 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>Email tidak dapat diubah
            </p>
            @error('email')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-sm">
                <i class="fas fa-save mr-2"></i>
                Simpan Perubahan
            </button>
        </div>
    </div>
</section>