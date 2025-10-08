<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Promo;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        // Ambil menu andalan (3 menu pertama dari kategori signatures)
        $featuredMenus = Menu::where('category', 'signatures')
                            ->where('is_available', true)
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();

        // Ambil promo aktif
        $activePromos = Promo::active()
                            ->orderBy('created_at', 'desc')
                            ->take(2)
                            ->get();

        return view('customer.homepage', compact('featuredMenus', 'activePromos')); // buat file resources/views/customer/homepage.blade.php
    }
}
