@extends('layouts.customer.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto mt-8 mb-12">

    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi profil dan preferensi akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Form Profil -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Informasi Profil -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Informasi Profil</h2>
                        <p class="text-gray-600 text-sm">Perbarui informasi dasar akun Anda</p>
                    </div>
                </div>

                <form method="post" action="{{ route('customer.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user text-yellow-500 mr-1"></i>Nama Lengkap
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope text-yellow-500 mr-1"></i>Alamat Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                   disabled>
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>Email tidak dapat diubah
                            </p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone text-yellow-500 mr-1"></i>Nomor Telepon
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                                   placeholder="Contoh: 081234567890">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Member Since -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-yellow-500 mr-1"></i>Bergabung Sejak
                            </label>
                            <input type="text" value="{{ $user->created_at->format('d M Y') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                   disabled>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-sm">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Ubah Password -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-lock text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Keamanan Akun</h2>
                        <p class="text-gray-600 text-sm">Perbarui password akun Anda</p>
                    </div>
                </div>

                <form method="post" action="{{ route('customer.profile.change-password') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key text-blue-500 mr-1"></i>Password Saat Ini
                            </label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                                   placeholder="Masukkan password saat ini">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock text-green-500 mr-1"></i>Password Baru
                            </label>
                            <input type="password" name="new_password" id="new_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                                   placeholder="Masukkan password baru">
                            @error('new_password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>Konfirmasi Password Baru
                            </label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                                   placeholder="Konfirmasi password baru">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-sm">
                            <i class="fas fa-lock mr-2"></i>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Card Ringkasan Profil -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-chart-bar text-yellow-500 mr-2"></i>
                    Ringkasan Profil
                </h3>
                
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-2xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <h4 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h4>
                    <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i>Member
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            {{ $totalReservations ?? 0 }}
                        </div>
                        <div class="text-xs text-blue-600 font-medium">Total Reservasi</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                        <div class="text-2xl font-bold text-green-600 mb-1">
                            {{ $completedReservations ?? 0 }}
                        </div>
                        <div class="text-xs text-green-600 font-medium">Selesai</div>
                    </div>
                </div>
            </div>

            <!-- Card Aksi Cepat -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Aksi Cepat
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('reservation.index') }}" 
                       class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg font-medium transition flex items-center justify-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Reservasi
                    </a>
                    
                    <a href="{{ route('reservation.history') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium transition flex items-center justify-center">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Saya
                    </a>
                </div>
            </div>

            <!-- Card Hapus Akun -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-bold text-red-800 mb-2">Hapus Akun</h4>
                        <p class="text-sm text-red-700 mb-3">
                            Hapus akun Anda secara permanen. Tindakan ini tidak dapat dibatalkan.
                        </p>
                        <form id="delete-account-form" method="post" action="{{ route('customer.profile.destroy') }}">
                            @csrf
                            @method('delete')
                            <button type="button" id="delete-account-btn"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus Akun Saya
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert -->
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Flash message profil/password
    @if (session('status') === 'profile-updated')
        Swal.fire({
            icon: 'success',
            title: '<span class="text-gray-800 font-bold">Berhasil!</span>',
            html: '<p class="text-gray-600">Profil berhasil diperbarui.</p>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#FBBF24',
            customClass: {
                popup: 'rounded-xl shadow-lg p-6 text-gray-800',
                title: 'text-xl font-bold',
                content: 'text-gray-600',
                confirmButton: 'bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-medium transition shadow-sm'
            }
        });
    @elseif (session('status') === 'password-updated')
        Swal.fire({
            icon: 'success',
            title: '<span class="text-gray-800 font-bold">Berhasil!</span>',
            html: '<p class="text-gray-600">Password berhasil diubah.</p>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#FBBF24',
            customClass: {
                popup: 'rounded-xl shadow-lg p-6 text-gray-800',
                title: 'text-xl font-bold',
                content: 'text-gray-600',
                confirmButton: 'bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-medium transition shadow-sm'
            }
        });
    @endif

    // Konfirmasi hapus akun
    document.getElementById('delete-account-btn').addEventListener('click', function(){
        Swal.fire({
            title: '<span class="text-gray-800 font-bold">Apakah Anda yakin?</span>',
            html: '<p class="text-gray-600">Semua data akun akan hilang permanen!</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-xl shadow-lg p-6 text-gray-800',
                title: 'text-xl font-bold',
                content: 'text-gray-600',
                confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition shadow-sm',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition shadow-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // submit form hapus akun
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('customer.profile.destroy') }}";

                // csrf
                let csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = "{{ csrf_token() }}";
                form.appendChild(csrf);

                // method delete
                let method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'delete';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>

@endsection
