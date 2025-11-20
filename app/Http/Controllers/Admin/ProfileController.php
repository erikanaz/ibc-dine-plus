<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Pastikan model User di-import
use App\Models\Reservation; // Jika menggunakan model Reservation

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $role = $user->getRoleNames()->first() ?? 'N/A';

        // Hitung statistik yang diperlukan untuk view
        $totalUsers = User::count();
        $totalReservations = Reservation::count(); // Sesuaikan dengan model Anda
        $pendingReservations = Reservation::where('status', 'pending')->count(); // Sesuaikan

        return view('admin.profile.edit', compact('user', 'role', 'totalUsers', 'totalReservations', 'pendingReservations'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.profile.edit')->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}