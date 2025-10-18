<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PromoController extends Controller
{
    public function index(Request $request)
{
    $query = Promo::query()->orderBy('created_at', 'desc');

    // Apply search filter
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('promo_code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Apply status filter jika ada
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Apply type filter jika ada
    if ($request->has('type') && $request->type != '') {
        $query->where('type', $request->type);
    }

    $promos = $query->paginate(10);

    // Hitung statistik dari semua data (bukan hanya yang dipaginasi)
    $allPromos = Promo::all();
    
    $activePromosCount = $allPromos->filter(function($promo) {
        return $promo->status === 'active';
    })->count();
    
    $percentPromosCount = $allPromos->where('type', 'percent')->count();
    $fixedPromosCount = $allPromos->where('type', 'fixed')->count();

    return view('admin.promos.index', compact(
        'promos', 
        'activePromosCount',
        'percentPromosCount', 
        'fixedPromosCount'
    ));
}
    // Method lainnya tetap sama...
    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'promo_code' => 'required|string|max:50|unique:promos,promo_code',
            'description' => 'nullable|string',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percent,fixed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        Promo::create($validated);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil dibuat.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'promo_code' => 'required|string|max:50|unique:promos,promo_code,' . $promo->id,
            'description' => 'nullable|string',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percent,fixed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $promo->update($validated);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        // Cek apakah promo sudah digunakan
        if ($promo->reservations()->count() > 0) {
            return redirect()->route('admin.promos.index')
                ->with('error', 'Tidak dapat menghapus promo yang sudah digunakan dalam reservasi.');
        }

        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }
}