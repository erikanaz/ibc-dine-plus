<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
// use Auth;
use App\Models\User;    // Import User model if needed
// use App\Models\Role; // Uncomment if you need to check roles directly
// use App\Models\Permission; // Uncomment if you need to check permissions directly
// Uncomment the following lines if you need to use Role and Permission models
// use Spatie\Permission\Models\Role;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        

        if ($user->hasRole('admin') || $user->hasRole('cashier')) {
            return redirect()->intended('/admin/dashboard');
        }

        if ($user->hasRole('member')) {
            return redirect()->intended('/homepage'); // atau route('home')
        }

        // Jika role tidak dikenali
        Auth::logout();
        return redirect()->route('login')->withErrors(['email' => 'Akun Anda tidak memiliki akses.']);
        // return redirect()->intended(route('homepage', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
