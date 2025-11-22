<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'whatsapp' => ['required', 'string', 'regex:/^[0-9]{9,13}$/'],
        ], [
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Contoh: 81234567890',
        ]);

         // Format: +62 + nomor (tanpa 0 di depan)
        $phoneNumber = '+62' . $request->whatsapp;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phoneNumber, // Simpan sebagai +628123456789
            // 'phone' => $request->whatsapp,
            // 'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('customer');

        event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('homepage', absolute: false));

        return redirect()->route('verification.notice')->with('status', 'Registrasi berhasil! Silakan verifikasi email Anda.');
    }
}
