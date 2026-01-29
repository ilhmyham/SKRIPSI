<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\ModulIqra;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Display materi index (module cards)
     */
    public function index()
    {
        $modules = ModulIqra::withCount('materi')->orderBy('modul_id')->get();
        
        // Determine which view to use based on user role
        $view = auth()->user()->isAdmin() ? 'admin.materi.index' : 'guru.materi.index';
        
        return view($view, compact('modules'));
    }

    /**
     * Display materi by module
     */
    public function byModule(ModulIqra $module)
    {
        $materis = $module->materi()->orderBy('created_at', 'desc')->get();
        $modules = ModulIqra::all(); // For dropdown in modal
        
        // Determine which view to use based on user role
        $view = auth()->user()->isAdmin() ? 'admin.materi.show' : 'guru.materi.show';
        
        return view($view, compact('module', 'materis', 'modules'));
    }

    /**
     * Store new materi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
            'judul_materi' => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'file_video' => 'nullable|string', // Google Drive video ID
            'file_path' => 'nullable|image|max:5120', // Support both field names
            'image' => 'nullable|image|max:5120', // Guru uses 'image'
            'deskripsi' => 'nullable|string',
        ]);

        // Add the user who created this materi
        $validated['users_user_id'] = auth()->id();

        // Handle image upload - support both 'file_path' and 'image' fields
        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('materi', 'public');
        } elseif ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $validated['file_path'] = $image->storeAs('materi', $filename, 'public');
        }

        // Remove 'image' field if it exists (we only store in file_path)
        unset($validated['image']);

        $materi = Materi::create($validated);
        $module = ModulIqra::find($validated['modul_iqra_modul_id']);

        // Log activity only for admin
        if (auth()->user()->isAdmin()) {
            $this->logActivity('created', 'Materi', $materi->materi_id, "Menambahkan materi \"" . $materi->judul_materi . "\" ke " . $module->nama_modul);
        }

        // Redirect based on user role
        $message = 'Materi berhasil ditambahkan';

        if (auth()->user()->isAdmin()) {
            return back()->with('success', $message);
        } else {
            // Redirect to the module's material management table
            $route = 'guru.materi.by-module';
            return redirect()->route($route, ['module' => $validated['modul_iqra_modul_id']])->with('success', $message);
        }
    }

    /**
     * Update materi
     */
    public function update(Request $request, Materi $materi)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
            'judul_materi' => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'file_video' => 'nullable|string',
            'video_url' => 'nullable|string', // Admin uses 'video_url'
            'gambar_isyarat' => 'nullable|image|max:5120', // Admin uses 'gambar_isyarat'
            'image' => 'nullable|image|max:5120', // Guru uses 'image'
            'deskripsi' => 'nullable|string',
        ]);

        // Handle video URL field (admin uses video_url, we store in file_video)
        if (isset($validated['video_url'])) {
            $validated['file_video'] = $validated['video_url'];
            unset($validated['video_url']);
        }

        // Handle image upload - support both 'gambar_isyarat' and 'image' fields
        if ($request->hasFile('gambar_isyarat')) {
            // Delete old image
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $validated['file_path'] = $request->file('gambar_isyarat')->store('materi', 'public');
        } elseif ($request->hasFile('image')) {
            // Delete old image
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $validated['file_path'] = $image->storeAs('materi', $filename, 'public');
        }

        // Remove temporary fields
        unset($validated['gambar_isyarat'], $validated['image']);

        $materi->update($validated);

        // Log activity only for admin
        if (auth()->user()->isAdmin()) {
            $this->logActivity('updated', 'Materi', $materi->materi_id, "Mengupdate materi \"" . $materi->judul_materi . "\"");
        }

        // Redirect based on user role
        $message = 'Materi berhasil diupdate';

        if (auth()->user()->isAdmin()) {
            return back()->with('success', $message);
        } else {
            // Redirect to the module's material management table
            $route = 'guru.materi.by-module';
            return redirect()->route($route, ['module' => $materi->modul_iqra_modul_id])->with('success', $message);
        }
    }

    /**
     * Delete materi
     */
    public function destroy(Materi $materi)
    {
        $materiName = $materi->judul_materi;
        
        // Delete image if exists
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();
        
        // Log activity only for admin
        if (auth()->user()->isAdmin()) {
            $this->logActivity('deleted', 'Materi', $materi->materi_id, "Menghapus materi \"" . $materiName . "\"");
        }
        
        return back()->with('success', 'Materi berhasil dihapus');
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
