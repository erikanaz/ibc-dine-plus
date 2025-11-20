@extends('layouts.admin.app')

@section('title', 'Profil Admin')

@section('content')
<div class="max-w-3xl mx-auto mt-8 mb-12 space-y-8">

    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Profil Admin</h1>

    <!-- Form Profil -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form method="post" action="{{ route('admin.profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <input type="text" value="{{ $role }}" class="w-full px-4 py-3 border border-gray-300 bg-gray-50 text-gray-500 rounded-lg" disabled>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bergabung Sejak</label>
                    <input type="text" value="{{ $user->created_at->format('d M Y') }}" class="w-full px-4 py-3 border border-gray-300 bg-gray-50 text-gray-500 rounded-lg" disabled>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Form Ubah Password -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form method="post" action="{{ route('admin.profile.change-password') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input type="password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('new_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    <!-- Hapus Akun -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-red-700 mb-3">Hapus Akun</h3>
        <p class="text-sm text-red-600 mb-3">Menghapus akun ini bersifat permanen dan tidak dapat dikembalikan.</p>
        <form id="delete-account-form" method="post" action="{{ route('admin.profile.destroy') }}">
            @csrf
            @method('delete')
            <button type="button" id="delete-account-btn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                Hapus Akun Saya
            </button>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('delete-account-btn').addEventListener('click', function(){
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Tindakan ini akan menghapus akun secara permanen!",
        icon: 'warning',
        input: 'password',
        inputPlaceholder: 'Masukkan password untuk konfirmasi',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if(result.isConfirmed){
            const form = document.getElementById('delete-account-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'password';
            input.value = result.value;
            form.appendChild(input);
            form.submit();
        }
    });
});
</script>
@endsection