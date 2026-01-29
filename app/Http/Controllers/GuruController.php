<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\User;
use App\Models\Materi;
use App\Models\Kuis;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * Display guru dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'total_materi' => Materi::where('users_user_id', $user->id)->count(),
            'total_kuis' => Kuis::where('users_user_id', $user->id)->count(),
            'total_tugas' => Tugas::where('users_user_id', $user->id)->count(),
            'total_siswa' => User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count(),
        ];

        // Get recent activity logs for this guru
        $recentActivities = ActivityLog::with('user')
            ->where('user_id', $user->id)
            ->recent(10)
            ->get();

        return view('guru.dashboard', compact('stats', 'recentActivities'));
    }

    /**
     * Display assignments
     */
    public function tugas()
    {
        $tugasList = Tugas::where('users_user_id', auth()->id())
            ->withCount('pengumpulan')
            ->orderBy('deadline', 'desc')
            ->paginate(15);

        return view('guru.tugas.index', compact('tugasList'));
    }

    /**
     * Show create assignment form
     */
    public function createTugas()
    {
        return view('guru.tugas.create');
    }

    /**
     * Store new assignment
     */
    public function storeTugas(Request $request)
    {
        $validated = $request->validate([
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
        ]);

        $validated['users_user_id'] = auth()->id();
        Tugas::create($validated);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dibuat');
    }

    /**
     * Edit assignment
     */
    public function editTugas(Tugas $tugas)
    {
        return view('guru.tugas.edit', compact('tugas'));
    }

    /**
     * Update assignment
     */
    public function updateTugas(Request $request, Tugas $tugas)
    {
        $validated = $request->validate([
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
        ]);

        $tugas->update($validated);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diupdate');
    }

    /**
     * Delete assignment
     */
    public function destroyTugas(Tugas $tugas)
    {
        $tugas->delete();
        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dihapus');
    }

    /**
     * View assignment submissions
     */
    public function submissions(Tugas $tugas)
    {
        if ($tugas->users_user_id != auth()->id()) {
            abort(403);
        }

        $pengumpulan = PengumpulanTugas::with('user')
            ->where('tugas_id', $tugas->tugas_id)
            ->get();

        return view('guru.tugas.submissions', compact('tugas', 'pengumpulan'));
    }

    /**
     * Grade submission
     */
    public function gradeSubmission(Request $request, PengumpulanTugas $submission)
    {
        if ($submission->tugas->users_user_id != auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $submission->update($validated);

        return back()->with('success', 'Nilai berhasil disimpan');
    }
}
