<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\ModulIqra;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $modules = ModulIqra::withCount('materi')->orderBy('id')->get();
        $view = auth()->user()->isAdmin() ? 'admin.materi.index' : 'guru.materi.index';
        return view($view, compact('modules'));
    }

    public function byModule(ModulIqra $module)
    {
        $materis = $module->materi()
            ->orderByRaw('urutan IS NULL, urutan ASC')
            ->orderBy('created_at', 'asc')
            ->get();

        $modules = ModulIqra::all();
        
        $allKategoriList = [
            'hijaiyah'        => 'Huruf Hijaiyah',
            'fathah'          => 'Fathah (ــَ)',
            'kasrah'          => 'Kasrah (ــِ)',
            'dammah'          => 'Dammah (ــُ)',
            'fathatain'       => 'Fathatain (ً)',
            'kasratain'       => 'Kasratain (ٍ)',
            'dammatain'       => 'Dammatain (ٌ)',
            'sukun'           => 'Sukun (ْ)',
            'tasydid'         => 'Tasydid (ّ)',
            'konsep_sambung'  => 'Konsep Huruf Sambung',
            'latihan_2_huruf' => 'Latihan 2 Huruf',
            'latihan_3_huruf' => 'Latihan 3 Huruf',
            'latihan_4_huruf' => 'Latihan 4 Huruf',
            'mad_2_harakat'   => 'Mad 2 Harakat',
            'mad_4_5_harakat' => 'Mad 4-5 Harakat',
            'mad_6_harakat'   => 'Mad 6 Harakat',
            'muqattaah'       => "Huruf Muqatta'ah",
            'tanda_sifir'     => 'Tanda Sifir',
            'tanda_waqaf'     => 'Tanda Waqaf',
        ];

        $moduleMapping = [
            1 => ['hijaiyah'],
            2 => ['fathah', 'kasrah', 'dammah'],
            3 => ['fathatain', 'kasratain', 'dammatain', 'sukun', 'tasydid'],
            4 => ['konsep_sambung', 'latihan_2_huruf', 'latihan_3_huruf', 'latihan_4_huruf'],
            5 => ['mad_2_harakat', 'mad_4_5_harakat', 'mad_6_harakat'],
            6 => ['muqattaah', 'tanda_sifir', 'tanda_waqaf'],
        ];

        $kategoriList = [];
        if (isset($moduleMapping[$module->id])) {
            foreach ($moduleMapping[$module->id] as $key) {
                if (isset($allKategoriList[$key])) {
                    $kategoriList[$key] = $allKategoriList[$key];
                }
            }
        } else {
            $kategoriList = $allKategoriList;
        }

        $view = auth()->user()->isAdmin() ? 'admin.materi.show' : 'guru.materi.show';
        return view($view, compact('module', 'materis', 'modules', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_iqra_id'   => 'required|exists:modul_iqra,id',
            'kategori_materi' => 'nullable|string|max:255',
            'judul_materi'    => 'required|string|max:255',
            'huruf_hijaiyah'  => 'nullable|string|max:10',
            'file_video'      => 'nullable|string',
            'path_file'       => 'nullable|image|max:5120',
            'deskripsi'       => 'nullable|string',
            'urutan'          => 'nullable|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();

        $module     = ModulIqra::find($validated['modul_iqra_id']);
        $folderPath = $this->getModuleFolderPath($module);

        if ($request->hasFile('path_file')) {
            $validated['path_file'] = $request->file('path_file')->store($folderPath, 'public');
        }

        $materi = Materi::create($validated);

        $this->logActivity('created', 'Material', $materi->id, 'Menambahkan materi "' . $materi->judul_materi . '" ke ' . $module->nama_modul);

        $route = auth()->user()->isAdmin() ? 'admin.materi.by-module' : 'guru.materi.by-module';

        return redirect()->route($route, $validated['modul_iqra_id'])->with('success', 'Materi berhasil ditambahkan');
    }

    public function update(Request $request, Materi $materi)
    {
        $validated = $request->validate([
            'modul_iqra_id'   => 'required|exists:modul_iqra,id',
            'kategori_materi' => 'nullable|string|max:255',
            'judul_materi'    => 'required|string|max:255',
            'huruf_hijaiyah'  => 'nullable|string|max:10',
            'file_video'      => 'nullable|string',
            'path_file'       => 'nullable|image|max:5120',
            'deskripsi'       => 'nullable|string',
            'urutan'          => 'nullable|integer|min:1',
        ]);

        if (!$request->filled('file_video')) {
            unset($validated['file_video']);
        }

        $module     = ModulIqra::find($validated['modul_iqra_id']);
        $folderPath = $this->getModuleFolderPath($module);

        if ($request->hasFile('path_file')) {
            if ($materi->path_file) Storage::disk('public')->delete($materi->path_file);
            $validated['path_file'] = $request->file('path_file')->store($folderPath, 'public');
        } else {
            unset($validated['path_file']);
        }

        $materi->update($validated);

        $this->logActivity('updated', 'Material', $materi->id, 'Mengupdate materi "' . $materi->judul_materi . '"');

        $route = auth()->user()->isAdmin() ? 'admin.materi.by-module' : 'guru.materi.by-module';

        return redirect()->route($route, $materi->modul_iqra_id)->with('success', 'Materi berhasil diupdate');
    }

    public function destroy(Materi $materi)
    {
        $materiName = $materi->judul_materi;
        
        if ($materi->path_file) {
            Storage::disk('public')->delete($materi->path_file);
        }

        $materi->delete();
        
        $this->logActivity('deleted', 'Material', $materi->id, "Menghapus materi \"" . $materiName . "\"");
        
        return back()->with('success', 'Materi berhasil dihapus');
    }

    private function getModuleFolderPath(ModulIqra $module): string
    {
        $folderName = strtolower(str_replace(' ', '', $module->nama_modul));
        return "materi/{$folderName}";
    }
}
