<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use App\Models\Material;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\LearningProgress;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    // ─────────────────────────────────────────────────────────
    //  DASHBOARD
    // ─────────────────────────────────────────────────────────

    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_materi' => Material::where('user_id', $user->id)->count(),
            'total_kuis'   => Quiz::where('user_id', $user->id)->count(),
            'total_tugas'  => Assignment::where('user_id', $user->id)->count(),
            'total_siswa'  => User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count(),
        ];

        $recentActivities = ActivityLog::with('user')
            ->where('user_id', $user->id)
            ->recent(10)
            ->get();

        return view('guru.dashboard', compact('stats', 'recentActivities'));
    }

    // ─────────────────────────────────────────────────────────
    //  TUGAS (CRUD)
    // ─────────────────────────────────────────────────────────

    public function tugas()
    {
        $modules = \App\Models\Module::all();
        $tugasList = Assignment::where('user_id', auth()->id())
            ->with('module')
            ->withCount('submissions as pengumpulan_count')
            ->orderBy('deadline', 'desc')
            ->paginate(15);

        return view('guru.tugas.index', compact('tugasList', 'modules'));
    }

    public function createTugas()
    {
        return view('guru.tugas.create');
    }

    public function storeTugas(Request $request)
    {
        $validated = $request->validate([
            'module_id'   => 'required|exists:modules,id',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi_tugas'   => 'required|string', // Based on Assignment model
            'deadline'    => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        Assignment::create($validated);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dibuat');
    }

    public function editTugas(Assignment $tugas)
    {
        return view('guru.tugas.edit', compact('tugas'));
    }

    public function updateTugas(Request $request, Assignment $tugas)
    {
        $validated = $request->validate([
            'module_id'   => 'required|exists:modules,id',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi_tugas'   => 'required|string', // Changed from deskripsi to match model
            'deadline'    => 'required|date',
        ]);

        $tugas->update($validated);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diupdate');
    }

    public function destroyTugas(Assignment $tugas)
    {
        $tugas->delete();
        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dihapus');
    }

    // ─────────────────────────────────────────────────────────
    //  SUBMISSIONS & GRADING
    // ─────────────────────────────────────────────────────────

    public function submissions(Assignment $tugas)
    {
        if ($tugas->user_id != auth()->id()) {
            abort(403);
        }

        $pengumpulan = Submission::with('user')
            ->where('assignment_id', $tugas->id)
            ->get();

        $stats = [
            'total'    => $pengumpulan->count(),
            'graded'   => $pengumpulan->whereNotNull('nilai')->count(),
            'ungraded' => $pengumpulan->whereNull('nilai')->count(),
            'avg'      => $pengumpulan->count() > 0
                ? round($pengumpulan->whereNotNull('nilai')->avg('nilai'), 1)
                : '-',
        ];

        return view('guru.tugas.submissions', compact('tugas', 'pengumpulan', 'stats'));
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        $submission->loadMissing('assignment');

        if ($submission->assignment->user_id != auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nilai'        => 'required|numeric|min:0|max:100',
            'catatan_guru' => 'nullable|string|max:1000',
        ]);

        $submission->update($validated);

        return back()->with('success', 'Nilai berhasil disimpan');
    }

    // ─────────────────────────────────────────────────────────
    //  PROGRESS MONITORING
    // ─────────────────────────────────────────────────────────

    public function progress()
    {
        $siswaList = User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))
            ->with(['progress'])
            ->get();

        $totalMateri = Material::count();

        $siswaList = $siswaList->map(function ($siswa) use ($totalMateri) {
            $completed = $siswa->progress->where('status', 'selesai')->count();
            $siswa->completed_materi = $completed;
            $siswa->progress_pct = $totalMateri > 0
                ? round(($completed / $totalMateri) * 100, 1)
                : 0;
            return $siswa;
        });

        return view('guru.progress.index', compact('siswaList', 'totalMateri'));
    }

    public function studentProgress(User $user)
    {
        $progressList = LearningProgress::where('user_id', $user->id)
            ->with('material.module')
            ->latest('updated_at')
            ->get();

        $modules = Module::withCount('materials')->get();
        $totalMateri = Material::count();
        $completedMateri = $progressList->where('status', 'selesai')->count();
        $overallProgress = $totalMateri > 0
            ? round(($completedMateri / $totalMateri) * 100, 1)
            : 0;

        return view('guru.progress.show', compact(
            'user', 'progressList', 'modules',
            'totalMateri', 'completedMateri', 'overallProgress'
        ));
    }
}
