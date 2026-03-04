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
        $modules = Module::withCount('materi')->orderBy('id')->get();
        $view = auth()->user()->isAdmin() ? 'admin.materi.index' : 'guru.materi.index';
        return view($view, compact('modules'));
    }

    public function byModule(Module $module)
    {
        $materis = $module->materi()
            ->with('kategoriMateri')
            ->orderByRaw('urutan IS NULL, urutan ASC')
            ->orderBy('created_at', 'asc')
            ->get();


        $modules = Module::all();
        $categories = MaterialCategory::where('modul_iqra_id', $module->id)->orderBy('urutan')->get();
        
        $view = auth()->user()->isAdmin() ? 'admin.materi.show' : 'guru.materi.show';
        return view($view, compact('module', 'materis', 'modules', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_iqra_id'      => 'required|exists:modul_iqra,id',
            'kategori_materi_id'    => 'nullable|exists:kategori_materi,id',
            'judul_materi'   => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'file_video'     => 'nullable|string',
            'path_file'      => 'nullable|image|max:5120',
            'deskripsi'      => 'nullable|string',
            'urutan'         => 'nullable|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();

        $module     = Module::find($validated['modul_iqra_id']);
        $folderPath = $this->getModuleFolderPath($module);

        if ($request->hasFile('path_file')) {
            $validated['path_file'] = $request->file('path_file')->store($folderPath, 'public');
        }

        $materi = Material::create($validated);

        if (auth()->user()->isAdmin()) {
            $this->logActivity('created', 'Material', $materi->id, 'Menambahkan materi "' . $materi->judul_materi . '" ke ' . $module->nama_modul);
        }

        $route = auth()->user()->isAdmin() ? 'admin.materi.by-module' : 'guru.materi.by-module';

        return redirect()->route($route, $validated['modul_iqra_id'])->with('success', 'Materi berhasil ditambahkan');
    }

    public function update(Request $request, Material $materi)
    {
        $validated = $request->validate([
            'modul_iqra_id'      => 'required|exists:modul_iqra,id',
            'kategori_materi_id'    => 'nullable|exists:kategori_materi,id',
            'judul_materi'   => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'file_video'     => 'nullable|string',
            'path_file'      => 'nullable|image|max:5120',
            'deskripsi'      => 'nullable|string',
            'urutan'         => 'nullable|integer|min:1',
        ]);

        if (!$request->filled('file_video')) {
            unset($validated['file_video']);
        }

        $module     = Module::find($validated['modul_iqra_id']);
        $folderPath = $this->getModuleFolderPath($module);

        if ($request->hasFile('path_file')) {
            if ($materi->path_file) Storage::disk('public')->delete($materi->path_file);
            $validated['path_file'] = $request->file('path_file')->store($folderPath, 'public');
        } else {
            unset($validated['path_file']);
        }

        $materi->update($validated);

        if (auth()->user()->isAdmin()) {
            $this->logActivity('updated', 'Material', $materi->id, 'Mengupdate materi "' . $materi->judul_materi . '"');
        }

        $route = auth()->user()->isAdmin() ? 'admin.materi.by-module' : 'guru.materi.by-module';

        return redirect()->route($route, $materi->modul_iqra_id)->with('success', 'Materi berhasil diupdate');
    }

    public function destroy(Material $materi)
    {
        $materiName = $materi->judul_materi;
        
        if ($materi->path_file) {
            Storage::disk('public')->delete($materi->path_file);
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
