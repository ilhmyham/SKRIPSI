<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\QuizAnswer;
use App\Models\Submission;
use App\Models\User;
use App\Models\Material;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
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
            ->with('modulIqra')
            ->withCount('pengumpulanTugas as pengumpulan_count')
            ->orderBy('tenggat_waktu', 'desc')
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
            'modul_iqra_id' => 'required|exists:modul_iqra,id',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi_tugas'   => 'required|string', // Based on Assignment model
            'tenggat_waktu'    => 'required|date',
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
            'modul_iqra_id' => 'required|exists:modul_iqra,id',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi_tugas'   => 'required|string', // Changed from deskripsi to match model
            'tenggat_waktu'    => 'required|date',
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
            ->where('tugas_id', $tugas->id)
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
        $submission->loadMissing('tugas');

        if ($submission->tugas->user_id != auth()->id()) {
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
            ->with(['progressBelajar'])
            ->get();

        $totalMateri = Material::count();

        $siswaList = $siswaList->map(function ($siswa) use ($totalMateri) {
            $completed = $siswa->progressBelajar->where('status', 'selesai')->count();
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
            ->with('materi.modulIqra')
            ->latest('updated_at')
            ->get();

        $modules = Module::withCount('materi')->get();
        $totalMateri = Material::count();
        $completedMateri = $progressList->where('status', 'selesai')->count();
        $overallProgress = $totalMateri > 0
            ? round(($completedMateri / $totalMateri) * 100, 1)
            : 0;

        // ── Rekap Hasil Kuis (eager-load semua jawaban sekaligus — fix N+1) ──
        $kuisList = Quiz::with('kuisPertanyaan')->get();

        // Ambil semua jawaban siswa ini untuk semua kuis sekaligus
        $allAnswers = QuizAnswer::where('user_id', $user->id)
            ->with('opsiJawaban')
            ->get()
            ->groupBy('kuis_id');

        $hasilKuis = $kuisList->map(function ($kuis) use ($allAnswers) {
            $totalSoal = $kuis->kuisPertanyaan->count();
            if ($totalSoal === 0) return null;

            $answers = $allAnswers->get($kuis->id, collect());
            if ($answers->isEmpty()) return null; // belum mengerjakan

            $benar = $answers->filter(fn($a) => $a->opsiJawaban && $a->opsiJawaban->is_correct)->count();
            $skor  = round(($benar / $totalSoal) * 100, 1);

            return [
                'kuis'          => $kuis,
                'skor'          => $skor,
                'benar'         => $benar,
                'total_soal'    => $totalSoal,
                'dikerjakan_at' => $answers->max('updated_at'),
            ];
        })->filter()->values();

        // ── Rekap Nilai Tugas ─────────────────────────────────────────────────
        $submissions = Submission::where('user_id', $user->id)
            ->with('tugas.modulIqra')
            ->latest()
            ->get();

        return view('guru.progress.show', compact(
            'user', 'progressList', 'modules',
            'totalMateri', 'completedMateri', 'overallProgress',
            'hasilKuis', 'submissions'
        ));
    }

    // ─────────────────────────────────────────────────────────
    //  MONITORING HASIL KUIS SISWA
    // ─────────────────────────────────────────────────────────

    /**
     * Daftar semua kuis beserta rekap jumlah siswa yang sudah mengerjakan.
     */
    public function kuisMonitoring()
    {
        $kuisList = Quiz::with('modulIqra')
            ->withCount(['jawabanSiswa as total_pengerjaan' => function ($q) {
                $q->select(\DB::raw('COUNT(DISTINCT user_id)'));
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSiswa = User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count();

        return view('guru.kuis.monitoring', compact('kuisList', 'totalSiswa'));
    }

    /**
     * Detail hasil kuis: daftar siswa + skor masing-masing.
     */
    public function kuisMonitoringDetail(Quiz $kuis)
    {
        $kuis->load('modulIqra', 'kuisPertanyaan.opsiJawaban');
        $totalSoal = $kuis->kuisPertanyaan->count();

        // Eager-load semua jawaban + user + opsiJawaban sekaligus — fix N+1
        $allAnswers = QuizAnswer::where('kuis_id', $kuis->id)
            ->with(['opsiJawaban', 'user'])
            ->get()
            ->groupBy('user_id');

        $hasilSiswa = $allAnswers->map(function ($answers, $userId) use ($totalSoal) {
            $siswa = $answers->first()->user;
            if (!$siswa) return null;

            $benar = $answers->filter(fn($a) => $a->opsiJawaban && $a->opsiJawaban->is_correct)->count();
            $skor  = $totalSoal > 0 ? round(($benar / $totalSoal) * 100, 1) : 0;

            return [
                'siswa'         => $siswa,
                'benar'         => $benar,
                'salah'         => $totalSoal - $benar,
                'total_soal'    => $totalSoal,
                'skor'          => $skor,
                'dikerjakan_at' => $answers->max('updated_at'),
            ];
        })->filter()->sortByDesc('skor')->values();

        $avgSkor = $hasilSiswa->count() > 0
            ? round($hasilSiswa->avg('skor'), 1)
            : '-';

        $totalSiswa = User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count();

        return view('guru.kuis.monitoring-detail', compact(
            'kuis', 'hasilSiswa', 'avgSkor', 'totalSiswa', 'totalSoal'
        ));
    }
}
