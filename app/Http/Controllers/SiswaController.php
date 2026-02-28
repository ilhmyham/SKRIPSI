<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\LearningProgress;
use App\Models\QuizAnswer;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    private function getStudentStats(int $userId): array
    {
        $totalMateri = Material::count();
        $completedMateri = LearningProgress::where('user_id', $userId)
            ->where('status', 'selesai')
            ->count();

        $overallProgress = $totalMateri > 0
            ? round(($completedMateri / $totalMateri) * 100, 1)
            : 0;

        return compact('totalMateri', 'completedMateri', 'overallProgress');
    }

    public function dashboard()
    {
        $user = auth()->user();

        ['totalMateri'    => $totalMateri,
         'completedMateri' => $completedMateri,
         'overallProgress' => $overallProgress] = $this->getStudentStats($user->id);

        $modules = Module::withCount('materials')->with('materials:id,module_id')->get();

        $completedIds = LearningProgress::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->pluck('material_id')
            ->flip();

        $modules = $modules->map(function ($module) use ($completedIds) {
            $moduleMateriIds = $module->materials->pluck('id');
            $done = $moduleMateriIds->filter(fn($id) => isset($completedIds[$id]))->count();
            $module->done_count = $done;
            $module->progress = $module->materials_count > 0
                ? ($done / $module->materials_count) * 100
                : 0;
            return $module;
        });

        $completedModules = $modules->filter(fn($m) => $m->progress >= 100)->count();

        $tugasMendatang = Assignment::with(['submissions' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        return view('siswa.dashboard', compact(
            'overallProgress',
            'modules',
            'tugasMendatang',
            'completedModules',
            'completedMateri',
            'totalMateri'
        ));
    }

    public function materi(Request $request)
    {
        $user = auth()->user();
        $modules = Module::orderBy('id')->get();

        $currentModuleId = $request->get('module', $modules->first()?->id);
        $currentModule = Module::find($currentModuleId);

        $previousModule = Module::where('id', '<', $currentModuleId)
            ->orderBy('id', 'desc')->first();
        $nextModule = Module::where('id', '>', $currentModuleId)
            ->orderBy('id')->first();

        $materis = Material::with(['module', 'category', 'progress' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->where('module_id', $currentModuleId)
            ->orderBy('urutan')
            ->get();

        $totalModules = $modules->count();
        $currentPosition = $modules->search(fn($m) => $m->id == $currentModuleId) + 1;

        $hasKategori = $materis->whereNotNull('category_id')->isNotEmpty();

        $materisByKategori = null;
        $kategoriInfo = [];
        $kategoriList = [];
        $materiData = [];
        $firstTab = null;

        if ($hasKategori) {
            // Ambil urutan kategori langsung dari DB berdasarkan kolom 'urutan'
            $kategorisOrdered = \App\Models\MaterialCategory::where('module_id', $currentModuleId)
                ->orderBy('urutan')
                ->get();

            $materisByKategori = $materis->groupBy(function ($m) {
                return $m->category ? $m->category->nama : '';
            })->map(function ($items) {
                return $items->map(fn($materi) => [
                    'id'             => $materi->id,
                    'judul_materi'   => $materi->judul_materi,
                    'huruf_hijaiyah' => $materi->huruf_hijaiyah,
                    'file_video'     => $materi->file_video,
                    'deskripsi'      => $materi->deskripsi,
                    'file_path'      => $materi->file_path,
                    'is_completed'   => $materi->progress?->first()?->status === 'selesai',
                ]);
            });

            $existingKategori = $materisByKategori->keys()->toArray();

            $allKategoriConfig = [
                'fathah'          => ['label' => 'Fathah',      'fullLabel' => 'FATHAH (ــَ)',         'color' => 'emerald'],
                'kasrah'          => ['label' => 'Kasrah',      'fullLabel' => 'KASRAH (ــِ)',         'color' => 'blue'],
                'dammah'          => ['label' => 'Dammah',      'fullLabel' => 'DAMMAH (ــُ)',         'color' => 'orange'],
                'fathatain'       => ['label' => 'Fathatain',   'fullLabel' => 'FATHATAIN (ً)',        'color' => 'emerald'],
                'kasratain'       => ['label' => 'Kasratain',   'fullLabel' => 'KASRATAIN (ٍ)',        'color' => 'blue'],
                'dammatain'       => ['label' => 'Dammatain',   'fullLabel' => 'DAMMATAIN (ٌ)',        'color' => 'orange'],
                'sukun'           => ['label' => 'Sukun',       'fullLabel' => 'SUKUN (ْ)',            'color' => 'purple'],
                'tasydid'         => ['label' => 'Tasydid',     'fullLabel' => 'TASYDID (ّ)',          'color' => 'red'],
                'konsep_sambung'  => ['label' => 'Konsep',      'fullLabel' => 'KONSEP HURUF SAMBUNG', 'color' => 'emerald'],
                'latihan_2_huruf' => ['label' => '2 Huruf',     'fullLabel' => 'LATIHAN 2 HURUF',      'color' => 'blue'],
                'latihan_3_huruf' => ['label' => '3 Huruf',     'fullLabel' => 'LATIHAN 3 HURUF',      'color' => 'orange'],
                'latihan_4_huruf' => ['label' => '4 Huruf',     'fullLabel' => 'LATIHAN 4 HURUF',      'color' => 'purple'],
                'mad_2_harakat'   => ['label' => 'Mad 2',       'fullLabel' => 'MAD 2 HARAKAT',        'color' => 'emerald'],
                'mad_4_5_harakat' => ['label' => 'Mad 4-5',     'fullLabel' => 'MAD 4-5 HARAKAT',      'color' => 'blue'],
                'mad_6_harakat'   => ['label' => 'Mad 6',       'fullLabel' => 'MAD 6 HARAKAT',        'color' => 'orange'],
                'muqattaah'       => ['label' => "Muqatta'ah",  'fullLabel' => "HURUF MUQATTA'AH",     'color' => 'emerald'],
                'tanda_sifir'     => ['label' => 'Sifir',       'fullLabel' => 'TANDA SIFIR',          'color' => 'blue'],
                'tanda_waqaf'     => ['label' => 'Waqaf',       'fullLabel' => 'TANDA WAQAF',          'color' => 'orange'],
            ];

            // Urutan kategori diambil dari DB (kolom 'urutan'), bukan dari array hardcoded.
            // Kategoris yang ada di DB didahulukan sesuai urutan DB,
            // lalu tambahkan kategori dinamis yang tidak ada di DB di akhir.
            $dbNamaOrdered  = $kategorisOrdered->pluck('nama')->toArray();
            $orderedKnown   = array_values(array_intersect($dbNamaOrdered, $existingKategori));
            $dynamicUnknown = array_values(array_diff($existingKategori, $dbNamaOrdered));
            $kategoriList   = array_merge($orderedKnown, $dynamicUnknown);

            // Build $kategoriInfo: use hardcoded config for known ones,
            // and auto-generate label from slug for dynamic ones.
            foreach ($kategoriList as $kat) {
                if (isset($allKategoriConfig[$kat])) {
                    $kategoriInfo[$kat] = $allKategoriConfig[$kat];
                } else {
                    // Auto-generate human-readable label from slug
                    // e.g. "ragam_hamzah" → label: "Ragam Hamzah", fullLabel: "RAGAM HAMZAH"
                    $humanLabel = \Illuminate\Support\Str::title(str_replace('_', ' ', $kat));
                    $kategoriInfo[$kat] = [
                        'label'     => $humanLabel,
                        'fullLabel' => strtoupper(str_replace('_', ' ', $kat)),
                        'color'     => 'emerald',
                    ];
                }
            }

            foreach ($kategoriList as $kat) {
                if ($materisByKategori->has($kat)) {
                    $materiData[$kat] = $materisByKategori[$kat]->values()->all();
                }
            }

            $firstTab = $kategoriList[0] ?? null;
        }

        return view('siswa.materi.index', compact(
            'materis', 'modules', 'currentModule',
            'previousModule', 'nextModule',
            'currentPosition', 'totalModules',
            'hasKategori', 'materisByKategori',
            'kategoriInfo', 'kategoriList', 'materiData', 'firstTab'
        ));
    }

    public function completeMateri(Material $materi)
    {
        LearningProgress::updateOrCreate(
            ['material_id' => $materi->id, 'user_id' => auth()->id()],
            ['status' => 'selesai', 'progress_value' => 100]
        );

        return back()->with('success', 'Materi ditandai selesai!');
    }

    public function kuis()
    {
        $kuisList = Quiz::with(['module', 'questions'])->get();
        return view('siswa.kuis.index', compact('kuisList'));
    }

    public function showKuis(Quiz $kuis)
    {
        $kuis->load(['questions.options']);

        // Check if already completed
        $hasAnswered = \App\Models\QuizAnswer::where('quiz_id', $kuis->id)
            ->where('user_id', auth()->id())
            ->exists();

        return view('siswa.kuis.show', compact('kuis', 'hasAnswered'));
    }

    public function startKuis(Quiz $kuis)
    {
        // Just redirect to the quiz show page; the form is already there
        return redirect()->route('siswa.kuis.show', $kuis);
    }

    public function submitKuis(Request $request, Quiz $kuis)
    {
        $answers = $request->input('answers', []);
        $kuis->load(['questions.options']);

        $totalQuestions = $kuis->questions->count();
        $correctAnswers = 0;

        foreach ($answers as $pertanyaanId => $selectedOpsi) {
            $pertanyaan = $kuis->questions->find($pertanyaanId);
            if (! $pertanyaan) continue;

            $correctOpsi = $pertanyaan->options->firstWhere('is_correct', true);
            $isCorrect   = $correctOpsi && $correctOpsi->id == $selectedOpsi;

            if ($isCorrect) $correctAnswers++;

            QuizAnswer::updateOrCreate(
                [
                    'quiz_id'         => $kuis->id,
                    'user_id'         => auth()->id(),
                    'question_id'     => $pertanyaanId,
                ],
                [
                    'option_id'       => $selectedOpsi,
                ]
            );
        }

        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        return redirect()->route('siswa.kuis.results', $kuis)
            ->with('score', $score)
            ->with('correct', $correctAnswers)
            ->with('total', $totalQuestions);
    }

    public function kuisResults(Quiz $kuis)
    {
        $kuis->load(['questions.options']);

        // Load user answers from DB (persistent — no session dependency)
        $userAnswers = \App\Models\QuizAnswer::where('quiz_id', $kuis->id)
            ->where('user_id', auth()->id())
            ->pluck('option_id', 'question_id');

        $totalQuestions = $kuis->questions->count();
        $correctAnswers = 0;

        foreach ($kuis->questions as $question) {
            $selectedOptionId = $userAnswers[$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);
            if ($correctOption && $correctOption->id == $selectedOptionId) {
                $correctAnswers++;
            }
        }

        $score = $totalQuestions > 0
            ? round(($correctAnswers / $totalQuestions) * 100, 1)
            : 0;

        return view('siswa.kuis.results', compact(
            'kuis', 'score', 'correctAnswers', 'totalQuestions', 'userAnswers'
        ));
    }

    public function tugas()
    {
        $tugasList = Assignment::with(['submissions' => function ($q) {
            $q->where('user_id', auth()->id());
        }])->orderBy('deadline')->get();

        return view('siswa.tugas.index', compact('tugasList'));
    }

    public function showTugas(Assignment $tugas)
    {
        $pengumpulan = Submission::where('assignment_id', $tugas->id)
            ->where('user_id', auth()->id())
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    public function submitTugas(Request $request, Assignment $tugas)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file     = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path     = $file->storeAs('tugas', $filename, 'public');

        Submission::updateOrCreate(
            ['assignment_id' => $tugas->id, 'user_id' => auth()->id()],
            ['file_jawaban' => $path]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function profile()
    {
        $user = auth()->user();

        ['totalMateri'    => $totalMateri,
         'completedMateri' => $completedMateri,
         'overallProgress' => $overallProgress] = $this->getStudentStats($user->id);

        $totalKuis     = Quiz::count();
        $completedKuis = QuizAnswer::where('user_id', $user->id)
            ->distinct('quiz_id')
            ->count('quiz_id');

        $totalTugas     = Assignment::count();
        $completedTugas = Submission::where('user_id', $user->id)->count();

        $recentProgress = LearningProgress::where('user_id', $user->id)
            ->with('material')
            ->latest('updated_at')
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

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'photo'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->avatar && ! str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('photo')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui!');
    }
}
