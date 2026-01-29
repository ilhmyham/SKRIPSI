<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\ModulIqra;
use App\Models\Kuis;
use App\Models\Tugas;
use App\Models\ProgressBelajar;
use App\Models\JawabanSiswa;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display student dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get progress statistics
        $totalMateri = Materi::count();
        $completedMateri = ProgressBelajar::where('users_user_id', $user->id)
            ->where('status_2', 'selesai')
            ->count();
        
        $overallProgress = $totalMateri > 0 ? ($completedMateri / $totalMateri) * 100 : 0;
        
        // Get modules with progress
        $modules = ModulIqra::withCount('materi')->get()->map(function($module) use ($user) {
            $materiIds = $module->materi->pluck('materi_id');
            $completed = ProgressBelajar::where('users_user_id', $user->id)
                ->whereIn('materi_id', $materiIds)
                ->where('status_2', 'selesai')
                ->count();
            
            $module->progress = $module->materi_count > 0 ? ($completed / $module->materi_count) * 100 : 0;
            return $module;
        });
        
        // Count completed modules (progress = 100%)
        $completedModules = $modules->filter(function($module) {
            return $module->progress >= 100;
        })->count();
        
        // Upcoming assignments
        $tugasMendatang = Tugas::where('deadline', '>=', now())
            ->orderBy('deadline')
            ->limit(5)
            ->get();
        
        return view('siswa.dashboard', compact('overallProgress', 'modules', 'tugasMendatang', 'completedModules'));
    }

    /**
     * Display learning materials grid
     */
    public function materi(Request $request)
    {
        $user = auth()->user();
        
        // Get all modules for navigation
        $modules = ModulIqra::orderBy('modul_id')->get();
        
        // Get current module (default to first module)
        $currentModuleId = $request->get('module', $modules->first()->modul_id ?? null);
        $currentModule = ModulIqra::find($currentModuleId);
        
        // Get previous and next modules
        $previousModule = ModulIqra::where('modul_id', '<', $currentModuleId)
            ->orderBy('modul_id', 'desc')
            ->first();
        $nextModule = ModulIqra::where('modul_id', '>', $currentModuleId)
            ->orderBy('modul_id', 'asc')
            ->first();
        
        // Get materials for current module with progress
        $materis = Materi::with(['modulIqra', 'progress' => function($q) use ($user) {
            $q->where('users_user_id', $user->id);
        }])
        ->where('modul_iqra_modul_id', $currentModuleId)
        ->orderBy('created_at')
        ->get();
        
        // Calculate module position
        $totalModules = $modules->count();
        $currentPosition = $modules->search(function($module) use ($currentModuleId) {
            return $module->modul_id == $currentModuleId;
        }) + 1;

        return view('siswa.materi.index', compact(
            'materis', 
            'modules', 
            'currentModule',
            'previousModule',
            'nextModule',
            'currentPosition',
            'totalModules'
        ));
    }

    /**
     * Show learning material
     */
    public function showMateri(Materi $materi)
    {
        $materi->load('modulIqra');
        
        // Get user progress for this material
        $progress = ProgressBelajar::where('materi_id', $materi->materi_id)
            ->where('users_user_id', auth()->id())
            ->first();

        return view('siswa.materi.show', compact('materi', 'progress'));
    }

    /**
     * Mark material as complete
     */
    public function completeMateri(Materi $materi)
    {
        ProgressBelajar::updateOrCreate(
            [
                'materi_id' => $materi->materi_id,
                'users_user_id' => auth()->id(),
            ],
            [
                'status_2' => 'selesai',
                'progress_value' => 100,
                'tanggal_update' => now(),
            ]
        );

        return back()->with('success', 'Materi ditandai selesai!');
    }

    /**
     * Display quizzes
     */
    public function kuis()
    {
        $kuisList = Kuis::with('modulIqra')->get();
        return view('siswa.kuis.index', compact('kuisList'));
    }

    /**
     * Show quiz
     */
    public function showKuis(Kuis $kuis)
    {
        $kuis->load(['pertanyaan.opsiJawaban']);
        return view('siswa.kuis.show', compact('kuis'));
    }

    /**
     * Submit quiz answers
     */
    public function submitKuis(Request $request, Kuis $kuis)
    {
        $answers = $request->input('answers', []);
        $kuis->load(['pertanyaan.opsiJawaban']);
        
        $totalQuestions = $kuis->pertanyaan->count();
        $correctAnswers = 0;

        foreach ($answers as $pertanyaanId => $selectedOpsi) {
            $pertanyaan = $kuis->pertanyaan->find($pertanyaanId);
            if (!$pertanyaan) continue;

            $correctOpsi = $pertanyaan->opsiJawaban->where('is_correct', true)->first();
            $isCorrect = $correctOpsi && $correctOpsi->opsi_id == $selectedOpsi;
            
            if ($isCorrect) $correctAnswers++;

            JawabanSiswa::create([
                'kuis_id' => $kuis->kuis_id,
                'users_user_id' => auth()->id(),
                'pertanyaan_id' => $pertanyaanId,
                'jawaban_pilihan' => $selectedOpsi,
                'nilai' => $isCorrect ? 1 : 0,
                'waktu_dikerjakan' => now(),
            ]);
        }

        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        return redirect()->route('siswa.kuis.results', $kuis)
            ->with('score', $score)
            ->with('correct', $correctAnswers)
            ->with('total', $totalQuestions);
    }

    /**
     * Show quiz results
     */
    public function kuisResults(Kuis $kuis)
    {
        return view('siswa.kuis.results', compact('kuis'));
    }

    /**
     * Display assignments
     */
    public function tugas()
    {
        $tugasList = Tugas::with(['pengumpulan' => function($q) {
            $q->where('users_user_id', auth()->id());
        }])->orderBy('deadline')->get();

        return view('siswa.tugas.index', compact('tugasList'));
    }

    /**
     * Show assignment detail
     */
    public function showTugas(Tugas $tugas)
    {
        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->tugas_id)
            ->where('users_user_id', auth()->id())
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    /**
     * Submit assignment
     */
    public function submitTugas(Request $request, Tugas $tugas)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('tugas', $filename, 'public');

        PengumpulanTugas::updateOrCreate(
            [
                'tugas_id' => $tugas->tugas_id,
                'users_user_id' => auth()->id(),
            ],
            [
                'file_jawaban' => $path,
                'tanggal_kumpul' => now(),
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Display student profile
     */
    public function profile()
    {
        $user = auth()->user();
        
        // Get learning statistics
        $totalMateri = Materi::count();
        $completedMateri = ProgressBelajar::where('users_user_id', $user->id)
            ->where('status_2', 'selesai')
            ->count();
        
        $overallProgress = $totalMateri > 0 ? ($completedMateri / $totalMateri) * 100 : 0;
        
        // Get quiz statistics
        $totalKuis = Kuis::count();
        $completedKuis = JawabanSiswa::where('users_user_id', $user->id)
            ->distinct('kuis_id')
            ->count('kuis_id');
        
        // Get assignment statistics
        $totalTugas = Tugas::count();
        $completedTugas = PengumpulanTugas::where('users_user_id', $user->id)
            ->count();
        
        // Get recent activities
        $recentProgress = ProgressBelajar::where('users_user_id', $user->id)
            ->with('materi')
            ->latest('tanggal_update')
            ->limit(5)
            ->get();
        
        return view('siswa.profile', compact(
            'user',
            'totalMateri',
            'completedMateri',
            'overallProgress',
            'totalKuis',
            'completedKuis',
            'totalTugas',
            'completedTugas',
            'recentProgress'
        ));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // max 2MB
        ]);

        // Update name and email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password_2 = bcrypt($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists and not from Google OAuth
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                \Storage::disk('public')->delete($user->avatar);
            }

            // Store new photo
            $path = $request->file('photo')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui!');
    }
}
