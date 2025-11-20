<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ChangePasswordRequest; // IMPORT REQUEST BARU
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $totalReservations = Reservation::where('user_id', $user->id)->count();
        $completedReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return view('customer.profile.edit', compact('user', 'totalReservations', 'completedReservations'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('customer.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Change the user's password.
     */
    public function changePassword(ChangePasswordRequest $request): RedirectResponse // GUNAKAN REQUEST BARU
    {
        $user = $request->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return Redirect::route('customer.profile.edit')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
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

        return Redirect::to('/');
    }
}