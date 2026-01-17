<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    // Facility types data
    private $facilityTypes = [
        'wifi' => 'Wi-Fi',
        'socket' => 'Stop Kontak',
        'baby-chair' => 'Kursi Bayi',
        'ac' => 'AC',
        'toilet' => 'Toilet',
        'smoking-area' => 'Area Merokok',
        'parking' => 'Parkir',
    ];

    /**
     * Display a listing of facilities
     */
    public function index(Request $request)
    {
        $query = Facility::query();

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'available') {
                $query->where('is_available', true);
            } elseif ($request->status == 'unavailable') {
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
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $facilities = $query->paginate(10)->appends($request->except('page'));

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new facility
     */
    public function create()
    {
        return view('admin.facilities.create', [
            'facilityTypes' => $this->facilityTypes
        ]);
    }

    /**
     * Store a newly created facility
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|in:wifi,socket,baby-chair,ac,toilet,smoking-area,parking',
            'is_available' => 'required|boolean',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/facilities', 'public');
                $validated['image'] = $imagePath;
            }

            // Create facility
            Facility::create($validated);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified facility
     */
    public function show(string $id)
    {
        $facility = Facility::findOrFail($id);
        return view('admin.facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified facility
     */
    public function edit(string $id)
    {
        $facility = Facility::findOrFail($id);
        
        return view('admin.facilities.edit', [
            'facility' => $facility,
            'facilityTypes' => $this->facilityTypes
        ]);
    }

    /**
     * Update the specified facility
     */
    public function update(Request $request, string $id)
    {
        $facility = Facility::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|in:wifi,socket,baby-chair,ac,toilet,smoking-area,parking',
            'is_available' => 'required|boolean',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($facility->image && Storage::disk('public')->exists($facility->image)) {
                    Storage::disk('public')->delete($facility->image);
                }
                
                // Upload new image
                $imagePath = $request->file('image')->store('images/facilities', 'public');
                $validated['image'] = $imagePath;
            } else {
                // Keep old image
                $validated['image'] = $facility->image;
            }

            // Update facility
            $facility->update($validated);

            // Redirect based on origin
            if ($request->has('from_show_page') || url()->previous() === route('admin.facilities.show', $facility->id)) {
                return redirect()->route('admin.facilities.show', $facility->id)
                    ->with('success', 'Fasilitas berhasil diperbarui.');
            }

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified facility
     */
    public function destroy(string $id)
    {
        $facility = Facility::findOrFail($id);
        
        try {
            // Delete image if exists
            if ($facility->image && Storage::disk('public')->exists($facility->image)) {
                Storage::disk('public')->delete($facility->image);
            }
            
            $facility->delete();

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status availability
     */
    public function updateStatus(Request $request, string $id)
    {
        $facility = Facility::findOrFail($id);
        
        $request->validate([
            'is_available' => 'required|boolean'
        ]);

        $facility->update([
            'is_available' => $request->is_available
        ]);

        return redirect()->route('admin.facilities.show', $facility->id)
            ->with('success', 'Status fasilitas berhasil diperbarui.');
    }

    /**
     * Seed default facilities
     */
    public function seed()
    {
        try {
            $defaults = [
                [
                    'name' => 'Wi-Fi Gratis',
                    'description' => 'Kecepatan tinggi, dengan password',
                    'icon' => 'wifi',
                    'is_available' => true,
                    'location' => 'Seluruh area restoran'
                ],
                [
                    'name' => 'Stop Kontak / Colokan',
                    'description' => 'Di hampir semua meja',
                    'icon' => 'socket',
                    'is_available' => true,
                    'location' => 'Meja makan'
                ],
                [
                    'name' => 'Kursi Bayi (High Chair)',
                    'description' => 'Untuk pelanggan dengan anak kecil',
                    'icon' => 'baby-chair',
                    'is_available' => true,
                    'location' => 'Area keluarga'
                ],
                [
                    'name' => 'AC / Pendingin Ruangan',
                    'description' => 'Menyala pada jam operasional',
                    'icon' => 'ac',
                    'is_available' => true,
                    'location' => 'Seluruh ruangan'
                ],
                [
                    'name' => 'Toilet',
                    'description' => 'Toilet pria dan wanita terpisah',
                    'icon' => 'toilet',
                    'is_available' => true,
                    'location' => 'Belakang restoran'
                ],
                [
                    'name' => 'Smoking Area',
                    'description' => 'Terpisah dari area utama',
                    'icon' => 'smoking-area',
                    'is_available' => true,
                    'location' => 'Teras samping'
                ],
                [
                    'name' => 'Area Parkir',
                    'description' => 'Cukup untuk mobil dan motor',
                    'icon' => 'parking',
                    'is_available' => true,
                    'location' => 'Depan restoran'
                ],
            ];

            foreach ($defaults as $facilityData) {
                Facility::firstOrCreate(
                    ['name' => $facilityData['name']],
                    $facilityData
                );
            }

            return redirect()->route('admin.facilities.index')
                ->with('success', '7 fasilitas default berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->route('admin.facilities.index')
                ->with('error', 'Gagal menambahkan fasilitas default: ' . $e->getMessage());
        }
    }
}