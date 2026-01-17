<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'is_available',
        'location',
        'image'
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }
        
        return $this->getDefaultImage();
    }

    /**
     * Get default image based on icon type
     */
    protected function getDefaultImage()
    {
        $defaultImages = [
            'wifi' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            'socket' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            'baby-chair' => 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            'ac' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            'toilet' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            'smoking-area' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            'parking' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        ];

        return $defaultImages[$this->icon] ?? 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_available ? 'Tersedia' : 'Tidak Tersedia';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_available 
            ? 'bg-green-100 text-green-800 border border-green-200' 
            : 'bg-red-100 text-red-800 border border-red-200';
    }

    /**
     * Get icon class for FontAwesome
     */
    public function getIconClassAttribute()
    {
        $icons = [
            'wifi' => 'fas fa-wifi',
            'socket' => 'fas fa-plug',
            'baby-chair' => 'fas fa-baby',
            'ac' => 'fas fa-snowflake',
            'toilet' => 'fas fa-restroom',
            'smoking-area' => 'fas fa-smoking',
            'parking' => 'fas fa-parking',
        ];

        return $icons[$this->icon] ?? 'fas fa-star';
    }

    /**
     * Get icon color for UI
     */
    public function getIconColorClassAttribute()
    {
        $colors = [
            'wifi' => 'bg-blue-100 text-blue-600',
            'socket' => 'bg-yellow-100 text-yellow-600',
            'baby-chair' => 'bg-pink-100 text-pink-600',
            'ac' => 'bg-cyan-100 text-cyan-600',
            'toilet' => 'bg-purple-100 text-purple-600',
            'smoking-area' => 'bg-orange-100 text-orange-600',
            'parking' => 'bg-gray-100 text-gray-600',
        ];

        return $colors[$this->icon] ?? 'bg-gray-100 text-gray-600';
    }

    /**
     * Get type name
     */
    public function getTypeNameAttribute()
    {
        $types = [
            'wifi' => 'Wi-Fi',
            'socket' => 'Stop Kontak',
            'baby-chair' => 'Kursi Bayi',
            'ac' => 'AC',
            'toilet' => 'Toilet',
            'smoking-area' => 'Area Merokok',
            'parking' => 'Parkir',
        ];

        return $types[$this->icon] ?? 'Fasilitas';
    }

    /**
     * Scope for available facilities
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('icon', $type);
    }

    // Tambahkan ini di class Facility (setelah method getTypeNameAttribute())

/**
 * Get icon color for CSS (untuk digunakan di dashboard customer)
 */
public function getIconCssAttribute()
{
    $colors = [
        'wifi' => [
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
            'border' => 'border-blue-200'
        ],
        'socket' => [
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-600',
            'border' => 'border-yellow-200'
        ],
        'baby-chair' => [
            'bg' => 'bg-pink-100',
            'text' => 'text-pink-600',
            'border' => 'border-pink-200'
        ],
        'ac' => [
            'bg' => 'bg-cyan-100',
            'text' => 'text-cyan-600',
            'border' => 'border-cyan-200'
        ],
        'toilet' => [
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'border' => 'border-purple-200'
        ],
        'smoking-area' => [
            'bg' => 'bg-orange-100',
            'text' => 'text-orange-600',
            'border' => 'border-orange-200'
        ],
        'parking' => [
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-600',
            'border' => 'border-gray-200'
        ],
    ];

    return $colors[$this->icon] ?? [
        'bg' => 'bg-gray-100',
        'text' => 'text-gray-600',
        'border' => 'border-gray-200'
    ];
}

/**
 * Get icon color class for background
 */
public function getIconBgClassAttribute()
{
    $colors = [
        'wifi' => 'bg-blue-100',
        'socket' => 'bg-yellow-100',
        'baby-chair' => 'bg-pink-100',
        'ac' => 'bg-cyan-100',
        'toilet' => 'bg-purple-100',
        'smoking-area' => 'bg-orange-100',
        'parking' => 'bg-gray-100',
    ];

    return $colors[$this->icon] ?? 'bg-gray-100';
}

/**
 * Get icon color class for text
 */
public function getIconTextClassAttribute()
{
    $colors = [
        'wifi' => 'text-blue-600',
        'socket' => 'text-yellow-600',
        'baby-chair' => 'text-pink-600',
        'ac' => 'text-cyan-600',
        'toilet' => 'text-purple-600',
        'smoking-area' => 'text-orange-600',
        'parking' => 'text-gray-600',
    ];

    return $colors[$this->icon] ?? 'text-gray-600';
}
}

