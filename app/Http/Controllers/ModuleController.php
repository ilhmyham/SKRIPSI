<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::withCount('materials')->orderBy('id')->get();
        return view('admin.modules.index', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_modul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $module = Module::create($validated);

        $this->logActivity('created', 'Module', $module->id, "Menambahkan modul \"" . $module->nama_modul . "\"");

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil ditambahkan');
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'nama_modul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $module->update($validated);

        $this->logActivity('updated', 'Module', $module->id, "Mengupdate modul \"" . $module->nama_modul . "\"");

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil diupdate');
    }

    public function destroy(Module $module)
    {
        $moduleName = $module->nama_modul;
        $id = $module->id;
        $module->delete();
        
        $this->logActivity('deleted', 'Module', $id, "Menghapus modul \"" . $moduleName . "\"");
        
        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil dihapus');
    }
}
