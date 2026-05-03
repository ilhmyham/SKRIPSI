<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Materi;
use App\Models\ModulIqra;
use App\Models\Kuis;
use App\Models\KuisJawabanSiswa;
use App\Models\ProgressBelajar;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GuruController extends Controller
{   
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_materi' => Materi::where('user_id', $user->id)->count(),
            'total_kuis'   => Kuis::where('user_id', $user->id)->count(),
            'total_tugas'  => 0,
            'total_siswa'  => User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count(),
        ];

        $recentActivities = ActivityLog::with('user')
            ->where('user_id', $user->id)
            ->recent(10)
            ->get();

        return view('guru.dashboard', compact('stats', 'recentActivities'));
    }

    public function progress()
    {
        $siswaList = User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))
            ->with(['progressBelajar'])
            ->get();

        $totalMateri = Materi::count();

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
        $progressList = ProgressBelajar::where('user_id', $user->id)
            ->with('materi.modulIqra')
            ->latest('updated_at')
            ->get();

        $modules = ModulIqra::withCount('materi')->get();
        $totalMateri = Materi::count();
        $completedMateri = $progressList->where('status', 'selesai')->count();
        $overallProgress = $totalMateri > 0
            ? round(($completedMateri / $totalMateri) * 100, 1)
            : 0;

        $kuisList = Kuis::with('kuisPertanyaan')->get();

        $allAnswers = KuisJawabanSiswa::where('user_id', $user->id)
            ->with('opsiJawaban')
            ->get()
            ->groupBy('kuis_id');

        $hasilKuis = $kuisList->map(function ($kuis) use ($allAnswers) {
            $totalSoal = $kuis->kuisPertanyaan->count();
            if ($totalSoal === 0) return null;

            $answers = $allAnswers->get($kuis->id, collect());
            if ($answers->isEmpty()) return null;

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

        return view('guru.progress.show', compact(
            'user', 'progressList', 'modules',
            'totalMateri', 'completedMateri', 'overallProgress',
            'hasilKuis'
        ));
    }
    
    public function kuisMonitoring()
    {
        $kuisList = Kuis::with('modulIqra')
            ->withCount(['jawabanSiswa as total_pengerjaan' => function ($q) {
                $q->select(\DB::raw('COUNT(DISTINCT user_id)'));
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSiswa = User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count();

        return view('guru.kuis.monitoring', compact('kuisList', 'totalSiswa'));
    }
   
    public function kuisMonitoringDetail(Kuis $kuis)
    {
        $kuis->load('modulIqra', 'kuisPertanyaan.opsiJawaban');
        $totalSoal = $kuis->kuisPertanyaan->count();

        $siswaIds = KuisJawabanSiswa::where('kuis_id', $kuis->id)
            ->distinct('user_id')
            ->pluck('user_id');

        $siswaMap = User::whereIn('id', $siswaIds)->get()->keyBy('id');
        $answerMap = KuisJawabanSiswa::where('kuis_id', $kuis->id)
            ->with('opsiJawaban')
            ->get()
            ->groupBy('user_id');

        $hasilSiswa = $siswaIds->map(function ($userId) use ($siswaMap, $answerMap, $totalSoal) {
            $siswa = $siswaMap->get($userId);
            if (!$siswa) return null;

            $answers = $answerMap->get($userId, collect());
            $benar   = $answers->filter(fn($a) => $a->opsiJawaban && $a->opsiJawaban->is_correct)->count();
            $skor    = $totalSoal > 0 ? round(($benar / $totalSoal) * 100, 1) : 0;

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
