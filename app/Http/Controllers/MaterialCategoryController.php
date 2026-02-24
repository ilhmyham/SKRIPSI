<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    /**
     * Display a listing of the categories for a specific module.
     */
    public function index(Module $module)
    {
        // Load categories for this module, sorted by their set display order
        $categories = MaterialCategory::where('module_id', $module->id)
            ->withCount('materials')
            ->orderByRaw('urutan IS NULL, urutan ASC')
            ->get();

        return view('admin.categories.index', compact('module', 'categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:1',
        ]);

        // Automatically format string to slug-like if desired, 
        // or just keep what they typed intact. 
        // Here we'll convert strictly machine slug format to remain consistent with seeders:
        $validated['nama'] = \Illuminate\Support\Str::slug($validated['nama'], '_');
        
        $validated['module_id'] = $module->id;

        MaterialCategory::create($validated);

        return back()->with('success', 'Kategori materi berhasil ditambahkan.');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, MaterialCategory $category)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:1',
        ]);

        // Keep snake_case slug convention for data consistency
        $validated['nama'] = \Illuminate\Support\Str::slug($validated['nama'], '_');

        $category->update($validated);

        return back()->with('success', 'Kategori materi berhasil diperbarui.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(MaterialCategory $category)
    {
        // You may want to prevent deletion if there are materials assigned, 
        // but since materials' category_id is nullable, we can let foreign key cascade 
        // SET NULL handle it (if configured in schema), or manually check:
        
        if ($category->materials()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa materi. Hapus atau pindahkan materi terlebih dahulu.');
        }

        $category->delete();

        return back()->with('success', 'Kategori materi berhasil dihapus.');
    }
}
