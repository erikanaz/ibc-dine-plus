<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Group menus by category
        $menus = Menu::where('is_available', true)
                    ->orderBy('category')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('category');

        $categories = [
            'signatures' => 'Signature',
            'vegetables' => 'Sayuran', 
            'tempoe-doeloe' => 'Tempo Doeloe',
            'mie-ayam h&w' => 'Mie Ayam H&W',
            'drinks' => 'Minuman'
        ];

        // Ensure all categories exist in the result
        foreach ($categories as $key => $name) {
            if (!isset($menus[$key])) {
                $menus[$key] = collect();
            }
        }

        return view('customer.menu', compact('menus', 'categories'));
    }
}