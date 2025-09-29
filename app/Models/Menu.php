<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    /**
     * Get formatted price attribute
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get status badge class based on availability
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_available ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_available ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        return $this->is_available ? 'Tersedia' : 'Habis';
    }

    /**
     * Get category badge class
     */
    public function getCategoryBadgeClassAttribute()
    {
        $classes = [
            'signatures' => 'bg-primary/10 text-primary',
            'vegetables' => 'bg-green-100 text-green-800',
            'tempoe-doeloe' => 'bg-orange-100 text-orange-800',
            'mie-ayam h&w' => 'bg-purple-100 text-purple-800',
            'drinks' => 'bg-blue-100 text-blue-800'
        ];

        return $classes[$this->category] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get formatted category name
     */
    public function getFormattedCategoryAttribute()
    {
        $categories = [
            'signatures' => 'Signature',
            'vegetables' => 'Vegetables',
            'tempoe-doeloe' => 'Tempo Doeloe',
            'mie-ayam h&w' => 'Mie Ayam H&W',
            'drinks' => 'Drinks'
        ];

        return $categories[$this->category] ?? $this->category;
    }

    /**
     * Get image URL attribute
     */
    public function getImageUrlAttribute()
    {
        // Jika gambar dari upload (ada di storage)
        if ($this->image) {
            // Cek apakah ini path storage (mengandung 'images/menus/')
            if (str_contains($this->image, 'images/menus/')) {
                if (Storage::disk('public')->exists($this->image)) {
                    return asset('storage/' . $this->image);
                }
            } 
            // Jika ini hanya nama file (dari seeder)
            else {
                // Cek di public images
                if (file_exists(public_path('images/menus/' . $this->image))) {
                    return asset('images/menus/' . $this->image);
                }
                // Cek di storage (fallback)
                elseif (Storage::disk('public')->exists('images/menus/' . $this->image)) {
                    return asset('storage/images/menus/' . $this->image);
                }
            }
        }
        
        // Fallback image berdasarkan kategori
        return $this->getFallbackImage();
    }

    /**
     * Get fallback image based on category
     */
    protected function getFallbackImage()
    {
        $fallbackImages = [
            'signatures' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'vegetables' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'tempoe-doeloe' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'mie-ayam h&w' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'drinks' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
        ];

        return $fallbackImages[$this->category] ?? 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60';
    }
}