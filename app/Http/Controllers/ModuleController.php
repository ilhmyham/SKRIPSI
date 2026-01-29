<?php

namespace App\Http\Controllers;

use App\Models\ModulIqra;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display modules
     */
    public function index()
    {
        $modules = ModulIqra::withCount('materi')->orderBy('modul_id')->get();
        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Show create module form
     */
    public function create()
    {
        return view('admin.modules.create');
    }

    /**
     * Store new module
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_modul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $module = ModulIqra::create($validated);

        $this->logActivity('created', 'ModulIqra', $module->modul_id, "Menambahkan modul \"" . $module->nama_modul . "\"");

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil ditambahkan');
    }

    /**
     * Show edit module form
     */
    public function edit(ModulIqra $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    /**
     * Update module
     */
    public function update(Request $request, ModulIqra $module)
    {
        $validated = $request->validate([
            'nama_modul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $module->update($validated);

        $this->logActivity('updated', 'ModulIqra', $module->modul_id, "Mengupdate modul \"" . $module->nama_modul . "\"");

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil diupdate');
    }

    /**
     * Delete module
     */
    public function destroy(ModulIqra $module)
    {
        $moduleName = $module->nama_modul;
        $module->delete();
        
        $this->logActivity('deleted', 'ModulIqra', $module->modul_id, "Menghapus modul \"" . $moduleName . "\"");
        
        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil dihapus');
    }

    /**
     * Helper method to log activities
     */
    private function logActivity(string $type, string $subjectType, $subjectId, string $description, array $properties = [])
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity_type' => $type,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
