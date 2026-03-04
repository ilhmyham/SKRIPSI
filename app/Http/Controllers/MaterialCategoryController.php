<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    public function index(Module $module)
    {
        $categories = MaterialCategory::where('modul_iqra_id', $module->id)
            ->withCount('materi')
            ->orderByRaw('urutan IS NULL, urutan ASC')
            ->get();

        return view('admin.categories.index', compact('module', 'categories'));
    }

    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:1',
        ]);

        $validated['nama'] = \Illuminate\Support\Str::slug($validated['nama'], '_');
        
        $validated['modul_iqra_id'] = $module->id;

        MaterialCategory::create($validated);

        return back()->with('success', 'Kategori materi berhasil ditambahkan.');
    }

    public function update(Request $request, MaterialCategory $category)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:1',
        ]);

        $validated['nama'] = \Illuminate\Support\Str::slug($validated['nama'], '_');

        $category->update($validated);

        return back()->with('success', 'Kategori materi berhasil diperbarui.');
    }

    public function destroy(MaterialCategory $category)
    {
        if ($category->materi()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa materi. Hapus atau pindahkan materi terlebih dahulu.');
        }

        $category->delete();

        return back()->with('success', 'Kategori materi berhasil dihapus.');
    }
}
