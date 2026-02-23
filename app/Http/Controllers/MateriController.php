<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Module;
use App\Models\MaterialCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $modules = Module::withCount('materials')->orderBy('id')->get();
        $view = auth()->user()->isAdmin() ? 'admin.materi.index' : 'guru.materi.index';
        return view($view, compact('modules'));
    }

    public function byModule(Module $module)
    {
        $materis = $module->materials()
            ->orderByRaw('urutan IS NULL, urutan ASC')
            ->orderBy('created_at', 'asc')
            ->get();
        $modules = Module::all();
        $categories = MaterialCategory::where('module_id', $module->id)->orderBy('urutan')->get();
        
        $view = auth()->user()->isAdmin() ? 'admin.materi.show' : 'guru.materi.show';
        return view($view, compact('module', 'materis', 'modules', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'category_id' => 'nullable|exists:material_categories,id',
            'judul_materi' => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'file_video' => 'nullable|string',
            'file_path' => 'nullable|image|max:5120',
            'image' => 'nullable|image|max:5120',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();

        $module = Module::find($validated['module_id']);
        $folderPath = $this->getModuleFolderPath($module);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store($folderPath, 'public');
        } elseif ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $validated['file_path'] = $image->storeAs($folderPath, $filename, 'public');
        }

        unset($validated['image']);

        $materi = Material::create($validated);
        $module = Module::find($validated['module_id']);

        if (auth()->user()->isAdmin()) {
            $this->logActivity('created', 'Material', $materi->id, "Menambahkan materi \"" . $materi->judul_materi . "\" ke " . $module->nama_modul);
        }

        $message = 'Materi berhasil ditambahkan';

        if (auth()->user()->isAdmin()) {
            return back()->with('success', $message);
        } else {
            return redirect()->route('guru.materi.by-module', ['module' => $validated['module_id']])->with('success', $message);
        }
    }

    public function update(Request $request, Material $materi)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'category_id' => 'nullable|exists:material_categories,id',
            'judul_materi' => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'file_video' => 'nullable|string',
            'video_url' => 'nullable|string',
            'file_path' => 'nullable|image|max:5120',
            'gambar_isyarat' => 'nullable|image|max:5120',
            'image' => 'nullable|image|max:5120',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:1',
        ]);

        if (isset($validated['video_url'])) {
            $validated['file_video'] = $validated['video_url'];
            unset($validated['video_url']);
        } elseif (!$request->filled('file_video')) {
            unset($validated['file_video']);
        }

        $module = Module::find($validated['module_id']);
        $folderPath = $this->getModuleFolderPath($module);

        if ($request->hasFile('file_path')) {
            if ($materi->file_path) Storage::disk('public')->delete($materi->file_path);
            $validated['file_path'] = $request->file('file_path')->store($folderPath, 'public');
        } elseif ($request->hasFile('gambar_isyarat')) {
            if ($materi->file_path) Storage::disk('public')->delete($materi->file_path);
            $validated['file_path'] = $request->file('gambar_isyarat')->store($folderPath, 'public');
        } elseif ($request->hasFile('image')) {
            if ($materi->file_path) Storage::disk('public')->delete($materi->file_path);
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $validated['file_path'] = $image->storeAs($folderPath, $filename, 'public');
        }

        unset($validated['gambar_isyarat'], $validated['image']);

        $materi->update($validated);

        if (auth()->user()->isAdmin()) {
            $this->logActivity('updated', 'Material', $materi->id, "Mengupdate materi \"" . $materi->judul_materi . "\"");
        }

        $message = 'Materi berhasil diupdate';

        if (auth()->user()->isAdmin()) {
            return back()->with('success', $message);
        } else {
            return redirect()->route('guru.materi.by-module', ['module' => $materi->module_id])->with('success', $message);
        }
    }

    public function destroy(Material $materi)
    {
        $materiName = $materi->judul_materi;
        
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();
        
        if (auth()->user()->isAdmin()) {
            $this->logActivity('deleted', 'Material', $materi->id, "Menghapus materi \"" . $materiName . "\"");
        }
        
        return back()->with('success', 'Materi berhasil dihapus');
    }

    private function getModuleFolderPath(Module $module): string
    {
        $folderName = strtolower(str_replace(' ', '', $module->nama_modul));
        return "materi/{$folderName}";
    }
}
