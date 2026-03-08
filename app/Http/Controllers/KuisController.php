<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Module;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KuisController extends Controller
{
    public function index()
    {
        $modules = Module::withCount('kuis')->orderBy('id')->get();
        $view = auth()->user()->isAdmin() ? 'admin.kuis.index' : 'guru.kuis.index';
        return view($view, compact('modules'));
    }

    public function byModule(Module $module)
    {
        $kuisList = Quiz::where('modul_iqra_id', $module->id)
                       ->withCount('kuisPertanyaan')
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        $view = auth()->user()->isAdmin() ? 'admin.kuis.show' : 'guru.kuis.show';
        return view($view, compact('module', 'kuisList'));
    }

    public function create(Request $request)
    {
        $modules = Module::all();
        $moduleId = $request->query('modul_iqra_id');
        $view = auth()->user()->isAdmin() ? 'admin.kuis.create' : 'guru.kuis.create';
        return view($view, compact('modules', 'moduleId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_iqra_id' => 'required|exists:modul_iqra,id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            
            // Tambahan: mimes:jpg,jpeg,png agar sesuai dengan custom message
            'pertanyaan.*.teks_pertanyaan' => 'required_without:pertanyaan.*.gambar_pertanyaan|nullable|string',
            'pertanyaan.*.gambar_pertanyaan' => 'required_without:pertanyaan.*.teks_pertanyaan|nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            'pertanyaan.*.opsi' => 'required|array|min:2',
            
            'pertanyaan.*.opsi.*.teks_opsi' => 'required_without:pertanyaan.*.opsi.*.gambar_opsi|nullable|string',
            'pertanyaan.*.opsi.*.gambar_opsi' => 'required_without:pertanyaan.*.opsi.*.teks_opsi|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ], [
            'pertanyaan.*.teks_pertanyaan.required_without' => 'Pertanyaan tidak boleh dibiarkan kosong sepenuhnya (isi teks atau gambar).',
            'pertanyaan.*.opsi.*.teks_opsi.required_without' => 'Opsi jawaban tidak boleh kosong (isi teks atau gambar).',
            'pertanyaan.*.gambar_pertanyaan.max' => 'Ukuran gambar pada pertanyaan maksimal 2MB.',
            'pertanyaan.*.opsi.*.gambar_opsi.max' => 'Ukuran gambar pada opsi jawaban maksimal 2MB.',
            'pertanyaan.*.gambar_pertanyaan.image' => 'File pertanyaan harus berupa gambar (JPG/PNG).',
            'pertanyaan.*.opsi.*.gambar_opsi.image' => 'File opsi jawaban harus berupa gambar (JPG/PNG).',
            'pertanyaan.*.gambar_pertanyaan.mimes' => 'File pertanyaan harus berformat JPG atau PNG.',
            'pertanyaan.*.opsi.*.gambar_opsi.mimes' => 'File opsi jawaban harus berformat JPG atau PNG.',
        ]);

        $kuis = Quiz::create([
            'modul_iqra_id' => $validated['modul_iqra_id'],
            'user_id' => auth()->id(),
            'judul_kuis' => $validated['judul_kuis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        foreach ($validated['pertanyaan'] as $index => $pertanyaanData) {
            $gambarPath = null;
            if ($request->hasFile("pertanyaan.{$index}.gambar_pertanyaan")) {
                $gambarPath = $request->file("pertanyaan.{$index}.gambar_pertanyaan")
                                      ->store('kuis/pertanyaan', 'public');
            }
            
            $pertanyaan = Question::create([
                'kuis_id' => $kuis->id,
                'teks_pertanyaan' => $pertanyaanData['teks_pertanyaan'] ?? null,
                'gambar_pertanyaan' => $gambarPath,
            ]);

            foreach ($pertanyaanData['opsi'] as $oIndex => $opsiData) {
                $gambarOpsiPath = null;
                if ($request->hasFile("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")) {
                    $gambarOpsiPath = $request->file("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")
                                              ->store('kuis/opsi', 'public');
                }
                
                QuestionOption::create([
                    'kuis_pertanyaan_id' => $pertanyaan->id,
                    'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                    'gambar_opsi' => $gambarOpsiPath,
                    'is_correct' => $opsiData['is_benar'],
                ]);
            }
        }

        if (auth()->user()->isAdmin()) {
            $module = Module::find($validated['modul_iqra_id']);
            $this->logActivity('created', 'Quiz', $kuis->id, "Membuat kuis \"" . $kuis->judul_kuis . "\" untuk " . $module->nama_modul);
        }

        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        return redirect()->route($route, $validated['modul_iqra_id'])->with('success', 'Kuis berhasil ditambahkan');
    }

    public function edit(Quiz $kuis)
    {
        $kuis->load(['kuisPertanyaan.opsiJawaban']);
        $modules = Module::all();
        
        $quizData = $kuis->kuisPertanyaan->map(function($p) {
            return [
                'id' => $p->id,
                'teks_pertanyaan' => $p->teks_pertanyaan,
                'existing_gambar' => $p->gambar_pertanyaan,
                'gambar_preview' => null,
                'opsi' => $p->opsiJawaban->map(function($o) {
                    return [
                        'id' => $o->id,
                        'teks_opsi' => $o->teks_opsi,
                        'existing_gambar' => $o->gambar_opsi,
                        'gambar_preview' => null,
                        'is_benar' => (bool)$o->is_correct
                    ];
                })->toArray()
            ];
        })->toArray();
        
        $view = auth()->user()->isAdmin() ? 'admin.kuis.edit' : 'guru.kuis.edit';
        return view($view, compact('kuis', 'modules', 'quizData'));
    }

    public function update(Request $request, Quiz $kuis)
    {
        $validated = $request->validate([
            'modul_iqra_id' => 'required|exists:modul_iqra,id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*.id' => 'nullable|integer|exists:kuis_pertanyaan,id',
            
            'pertanyaan.*.teks_pertanyaan' => 'required_without_all:pertanyaan.*.gambar_pertanyaan,pertanyaan.*.existing_gambar_pertanyaan|nullable|string',
            'pertanyaan.*.gambar_pertanyaan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pertanyaan.*.existing_gambar_pertanyaan' => 'nullable|string',
            
            'pertanyaan.*.opsi' => 'required|array|min:2',
            'pertanyaan.*.opsi.*.id' => 'nullable|integer|exists:kuis_opsi_jawaban,id',
            
            'pertanyaan.*.opsi.*.teks_opsi' => 'required_without_all:pertanyaan.*.opsi.*.gambar_opsi,pertanyaan.*.opsi.*.existing_gambar_opsi|nullable|string',
            'pertanyaan.*.opsi.*.gambar_opsi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pertanyaan.*.opsi.*.existing_gambar_opsi' => 'nullable|string',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ], [
            'pertanyaan.*.teks_pertanyaan.required_without_all' => 'Pertanyaan tidak boleh dibiarkan kosong sepenuhnya.',
            'pertanyaan.*.opsi.*.teks_opsi.required_without_all' => 'Opsi jawaban tidak boleh kosong.',
            'pertanyaan.*.gambar_pertanyaan.max' => 'Ukuran gambar pada pertanyaan maksimal 2MB.',
            'pertanyaan.*.opsi.*.gambar_opsi.max' => 'Ukuran gambar pada opsi jawaban maksimal 2MB.',
            'pertanyaan.*.gambar_pertanyaan.image' => 'File pertanyaan harus berupa gambar (JPG/PNG).',
            'pertanyaan.*.opsi.*.gambar_opsi.image' => 'File opsi jawaban harus berupa gambar (JPG/PNG).',
            'pertanyaan.*.gambar_pertanyaan.mimes' => 'File pertanyaan harus berformat JPG atau PNG.',
            'pertanyaan.*.opsi.*.gambar_opsi.mimes' => 'File opsi jawaban harus berformat JPG atau PNG.',
        ]);

        $kuis->update([
            'modul_iqra_id' => $validated['modul_iqra_id'],
            'judul_kuis' => $validated['judul_kuis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        $existingQuestionIds = $kuis->kuisPertanyaan->pluck('id')->toArray();
        $processedQuestionIds = [];

        foreach ($validated['pertanyaan'] as $index => $pertanyaanData) {
            $questionId = $pertanyaanData['id'] ?? null;
            $oldQuestion = $questionId ? Question::find($questionId) : null;
            $gambarPath = null;

            // KOREKSI: Pengecekan Replace / Remove file fisik pertanyaan
            if ($request->hasFile("pertanyaan.{$index}.gambar_pertanyaan")) {
                $gambarPath = $request->file("pertanyaan.{$index}.gambar_pertanyaan")->store('kuis/pertanyaan', 'public');
                
                // Hapus file lama jika di-replace dengan file baru
                if ($oldQuestion && $oldQuestion->gambar_pertanyaan) {
                    Storage::disk('public')->delete($oldQuestion->gambar_pertanyaan);
                }
            } else {
                $gambarPath = $pertanyaanData['existing_gambar_pertanyaan'] ?? null;
                
                // Hapus file fisik jika user sengaja menghapus gambar dari form tanpa upload gambar baru
                if (empty($gambarPath) && $oldQuestion && $oldQuestion->gambar_pertanyaan) {
                    Storage::disk('public')->delete($oldQuestion->gambar_pertanyaan);
                }
            }

            if ($questionId && in_array($questionId, $existingQuestionIds)) {
                $oldQuestion->update([
                    'teks_pertanyaan' => $pertanyaanData['teks_pertanyaan'] ?? null,
                    'gambar_pertanyaan' => $gambarPath,
                ]);
                $processedQuestionIds[] = $questionId;
                $pertanyaan = $oldQuestion; // Set untuk digunakan di child (opsi)
            } else {
                $pertanyaan = Question::create([
                    'kuis_id' => $kuis->id,
                    'teks_pertanyaan' => $pertanyaanData['teks_pertanyaan'] ?? null,
                    'gambar_pertanyaan' => $gambarPath,
                ]);
                $processedQuestionIds[] = $pertanyaan->id;
            }

            $existingOptionIds = $pertanyaan->opsiJawaban->pluck('id')->toArray();
            $processedOptionIdsForThisQuestion = [];

            foreach ($pertanyaanData['opsi'] as $oIndex => $opsiData) {
                $optionId = $opsiData['id'] ?? null;
                $oldOption = $optionId ? QuestionOption::find($optionId) : null;
                $gambarOpsiPath = null;

                // KOREKSI: Pengecekan Replace / Remove file fisik opsi
                if ($request->hasFile("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")) {
                    $gambarOpsiPath = $request->file("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")->store('kuis/opsi', 'public');
                    
                    // Hapus file lama jika di-replace dengan file baru
                    if ($oldOption && $oldOption->gambar_opsi) {
                        Storage::disk('public')->delete($oldOption->gambar_opsi);
                    }
                } else {
                    $gambarOpsiPath = $opsiData['existing_gambar_opsi'] ?? null;
                    
                    // Hapus file fisik jika user sengaja menghapus gambar opsi dari form
                    if (empty($gambarOpsiPath) && $oldOption && $oldOption->gambar_opsi) {
                        Storage::disk('public')->delete($oldOption->gambar_opsi);
                    }
                }

                if ($optionId && in_array($optionId, $existingOptionIds)) {
                    $oldOption->update([
                        'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                        'gambar_opsi' => $gambarOpsiPath,
                        'is_correct' => $opsiData['is_benar'],
                    ]);
                    $processedOptionIdsForThisQuestion[] = $optionId;
                } else {
                    $opsi = QuestionOption::create([
                        'kuis_pertanyaan_id' => $pertanyaan->id,
                        'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                        'gambar_opsi' => $gambarOpsiPath,
                        'is_correct' => $opsiData['is_benar'],
                    ]);
                    $processedOptionIdsForThisQuestion[] = $opsi->id;
                }
            }
            
            // Hapus opsi yang di-delete oleh user (Orphan options)
            $orphanedOptions = QuestionOption::where('kuis_pertanyaan_id', $pertanyaan->id)
                ->whereNotIn('id', $processedOptionIdsForThisQuestion)
                ->get();
            
            foreach ($orphanedOptions as $orphanedOption) {
                if ($orphanedOption->gambar_opsi) {
                    Storage::disk('public')->delete($orphanedOption->gambar_opsi);
                }
                $orphanedOption->delete();
            }
        }

        // Hapus pertanyaan yang di-delete oleh user (Orphan questions)
        $orphanedQuestions = Question::where('kuis_id', $kuis->id)
            ->whereNotIn('id', $processedQuestionIds)
            ->get();
        
        foreach ($orphanedQuestions as $orphanedQuestion) {
            if ($orphanedQuestion->gambar_pertanyaan) {
                Storage::disk('public')->delete($orphanedQuestion->gambar_pertanyaan);
            }
            foreach ($orphanedQuestion->opsiJawaban as $opsi) {
                if ($opsi->gambar_opsi) {
                    Storage::disk('public')->delete($opsi->gambar_opsi);
                }
                $opsi->delete();
            }
            $orphanedQuestion->delete();
        }

        if (auth()->user()->isAdmin()) {
            $this->logActivity('updated', 'Quiz', $kuis->id, "Mengupdate kuis \"" . $kuis->judul_kuis . "\"");
        }

        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        return redirect()->route($route, $kuis->modul_iqra_id)->with('success', 'Kuis berhasil diupdate');
    }

    public function destroy(Quiz $kuis)
    {
        $moduleId = $kuis->modul_iqra_id;
        $kuisName = $kuis->judul_kuis;

        $kuis->load('kuisPertanyaan.opsiJawaban');

        foreach ($kuis->kuisPertanyaan as $pertanyaan) {
            if ($pertanyaan->gambar_pertanyaan) {
                Storage::disk('public')->delete($pertanyaan->gambar_pertanyaan);
            }
            foreach ($pertanyaan->opsiJawaban as $opsi) {
                if ($opsi->gambar_opsi) {
                    Storage::disk('public')->delete($opsi->gambar_opsi);
                }
            }
        }

        $kuis->delete();

        if (auth()->user()->isAdmin()) {
            $this->logActivity('deleted', 'Quiz', $kuis->id, "Menghapus kuis \"" . $kuisName . "\"");
        }

        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        return redirect()->route($route, $moduleId)->with('success', 'Kuis berhasil dihapus');
    }
}