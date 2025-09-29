<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $menus = Menu::orderBy('created_at', 'desc')->paginate(10);
    //     return view('admin.menus.index', compact('menus'));
        
    // }

    public function index(Request $request)
{
    $query = Menu::query();

    // Search filter
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    // Category filter
    if ($request->has('category') && $request->category != '') {
        $query->where('category', $request->category);
    }

    // Status filter
    if ($request->has('status') && $request->status != '') {
        if ($request->status == 'active') {
            $query->where('is_available', true);
        } elseif ($request->status == 'inactive') {
            $query->where('is_available', false);
        }
    }

    // Sort filter
    switch ($request->sort) {
        case 'oldest':
            $query->orderBy('created_at', 'asc');
            break;
        case 'name_asc':
            $query->orderBy('name', 'asc');
            break;
        case 'name_desc':
            $query->orderBy('name', 'desc');
            break;
        case 'price_high':
            $query->orderBy('price', 'desc');
            break;
        case 'price_low':
            $query->orderBy('price', 'asc');
            break;
        default: // newest
            $query->orderBy('created_at', 'desc');
            break;
    }

    $menus = $query->paginate(10)->appends($request->except('page'));

    return view('admin.menus.index', compact('menus'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:signatures,vegetables,tempoe-doeloe,mie-ayam h&w,drinks',
            'is_available' => 'required|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048'
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('image')) {
                // Simpan file ke storage
                $imagePath = $request->file('image')->store('images/menus', 'public');
                $validated['image'] = $imagePath;
            }

            // Create menu
            Menu::create($validated);

            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);
        
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:signatures,vegetables,tempoe-doeloe,mie-ayam h&w,drinks',
            'is_available' => 'required|boolean',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048'
        ]);

        try {
            // Handle file upload jika ada gambar baru
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                    Storage::disk('public')->delete($menu->image);
                }
                
                // Simpan file baru
                $imagePath = $request->file('image')->store('images/menus', 'public');
                $validated['image'] = $imagePath;
            } else {
                // Pertahankan gambar lama jika tidak ada upload baru
                $validated['image'] = $menu->image;
            }

            // Update menu
            $menu->update($validated);

            // return redirect()->route('admin.menus.index')
            //     ->with('success', 'Menu berhasil diperbarui.');

            // PERBAIKAN: Redirect berdasarkan asal request
            // Jika datang dari halaman show, kembali ke show
            if ($request->has('from_show_page') || url()->previous() === route('admin.menus.show', $menu->id)) {
                return redirect()->route('admin.menus.show', $menu->id)
                    ->with('success', 'Menu berhasil diperbarui.');
            }

            // Default redirect ke index
            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        
        try {
            // Hapus gambar dari storage jika ada
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $menu->delete();

            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status availability menu
     */
    public function updateStatus(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);
        
        $request->validate([
            'is_available' => 'required|boolean'
        ]);

        $menu->update([
            'is_available' => $request->is_available
        ]);

        return redirect()->route('admin.menus.show', $menu->id)
            ->with('success', 'Status menu berhasil diperbarui.');
    }
}