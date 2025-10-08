<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    // HomeController.php - tambahkan method untuk handle image fallback
    private function getMenuImage($menu)
    {
        // Cek jika image ada di storage
        if ($menu->image && Storage::disk('public')->exists('images/menus/' . $menu->image)) {
            return asset('storage/images/menus/' . $menu->image);
        }
        
        // Fallback ke image default berdasarkan kategori
        return $this->getFallbackImage($menu->category);
    }

    private function getFallbackImage($category)
    {
        $fallbackImages = [
            'signatures' => '/images/menus/default-fish.jpg',
            'vegetables' => '/images/menus/default-vegetable.jpg',
            'tempoe-doeloe' => '/images/menus/default-traditional.jpg',
            'mie-ayam h&w' => '/images/menus/default-noodle.jpg',
            'drinks' => '/images/menus/default-drink.jpg'
        ];

        return $fallbackImages[$category] ?? '/images/menus/default-food.jpg';
    }
}