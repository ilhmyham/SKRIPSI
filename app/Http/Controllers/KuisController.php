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
        $modules = Module::withCount('quizzes')->orderBy('id')->get();
        $view = auth()->user()->isAdmin() ? 'admin.kuis.index' : 'guru.kuis.index';
        return view($view, compact('modules'));
    }

    public function byModule(Module $module)
    {
        $kuisList = Quiz::where('module_id', $module->id)
                       ->withCount('questions')
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        $view = auth()->user()->isAdmin() ? 'admin.kuis.show' : 'guru.kuis.show';
        return view($view, compact('module', 'kuisList'));
    }

    public function create(Request $request)
    {
        $modules = Module::all();
        $moduleId = $request->query('module_id');
        $view = auth()->user()->isAdmin() ? 'admin.kuis.create' : 'guru.kuis.create';
        return view($view, compact('modules', 'moduleId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*.text_pertanyaan' => 'nullable|string',
            'pertanyaan.*.gambar_pertanyaan' => 'nullable|image|max:2048',
            'pertanyaan.*.opsi' => 'required|array|min:2',
            'pertanyaan.*.opsi.*.teks_opsi' => 'nullable|string',
            'pertanyaan.*.opsi.*.gambar_opsi' => 'nullable|image|max:2048',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ]);

        $kuis = Quiz::create([
            'module_id' => $validated['module_id'],
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
                'quiz_id' => $kuis->id,
                'text_pertanyaan' => $pertanyaanData['text_pertanyaan'] ?? null,
                'gambar_pertanyaan' => $gambarPath,
            ]);

            foreach ($pertanyaanData['opsi'] as $oIndex => $opsiData) {
                $gambarOpsiPath = null;
                if ($request->hasFile("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")) {
                    $gambarOpsiPath = $request->file("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")
                                              ->store('kuis/opsi', 'public');
                }
                
                QuestionOption::create([
                    'question_id' => $pertanyaan->id,
                    'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                    'gambar_opsi' => $gambarOpsiPath,
                    'is_correct' => $opsiData['is_benar'],
                ]);
            }
        }

        if (auth()->user()->isAdmin()) {
            $module = Module::find($validated['module_id']);
            $this->logActivity('created', 'Quiz', $kuis->id, "Membuat kuis \"" . $kuis->judul_kuis . "\" untuk " . $module->nama_modul);
        }

        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        return redirect()->route($route, $validated['module_id'])->with('success', 'Kuis berhasil ditambahkan');
    }

    public function edit(Quiz $kuis)
    {
        $kuis->load(['questions.options']);
        $modules = Module::all();
        
        $quizData = $kuis->questions->map(function($p) {
            return [
                'id' => $p->id,
                'text_pertanyaan' => $p->text_pertanyaan,
                'existing_gambar' => $p->gambar_pertanyaan,
                'gambar_preview' => null,
                'opsi' => $p->options->map(function($o) {
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
            'module_id' => 'required|exists:modules,id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*.id' => 'nullable|integer|exists:questions,id',
            'pertanyaan.*.text_pertanyaan' => 'nullable|string',
            'pertanyaan.*.gambar_pertanyaan' => 'nullable|image|max:2048',
            'pertanyaan.*.existing_gambar_pertanyaan' => 'nullable|string',
            'pertanyaan.*.opsi' => 'required|array|min:2',
            'pertanyaan.*.opsi.*.id' => 'nullable|integer|exists:question_options,id',
            'pertanyaan.*.opsi.*.teks_opsi' => 'nullable|string',
            'pertanyaan.*.opsi.*.gambar_opsi' => 'nullable|image|max:2048',
            'pertanyaan.*.opsi.*.existing_gambar_opsi' => 'nullable|string',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ]);

        $kuis->update([
            'module_id' => $validated['module_id'],
            'judul_kuis' => $validated['judul_kuis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        $existingQuestionIds = $kuis->questions->pluck('id')->toArray();
        $processedQuestionIds = [];
        $processedOptionIds = [];

        foreach ($validated['pertanyaan'] as $index => $pertanyaanData) {
            $questionId = $pertanyaanData['id'] ?? null;

            $gambarPath = null;
            if ($request->hasFile("pertanyaan.{$index}.gambar_pertanyaan")) {
                $gambarPath = $request->file("pertanyaan.{$index}.gambar_pertanyaan")->store('kuis/pertanyaan', 'public');
                
                if ($questionId) {
                    $oldQuestion = Question::find($questionId);
                    if ($oldQuestion && $oldQuestion->gambar_pertanyaan) {
                        Storage::disk('public')->delete($oldQuestion->gambar_pertanyaan);
                    }
                }
            } elseif (!empty($pertanyaanData['existing_gambar_pertanyaan'])) {
                $gambarPath = $pertanyaanData['existing_gambar_pertanyaan'];
            }

            if ($questionId && in_array($questionId, $existingQuestionIds)) {
                $pertanyaan = Question::find($questionId);
                $pertanyaan->update([
                    'text_pertanyaan' => $pertanyaanData['text_pertanyaan'] ?? null,
                    'gambar_pertanyaan' => $gambarPath,
                ]);
                $processedQuestionIds[] = $questionId;
            } else {
                $pertanyaan = Question::create([
                    'quiz_id' => $kuis->id,
                    'text_pertanyaan' => $pertanyaanData['text_pertanyaan'] ?? null,
                    'gambar_pertanyaan' => $gambarPath,
                ]);
                $processedQuestionIds[] = $pertanyaan->id;
            }

            $existingOptionIds = $pertanyaan->options->pluck('id')->toArray();

            foreach ($pertanyaanData['opsi'] as $oIndex => $opsiData) {
                $optionId = $opsiData['id'] ?? null;

                $gambarOpsiPath = null;
                if ($request->hasFile("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")) {
                    $gambarOpsiPath = $request->file("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")->store('kuis/opsi', 'public');
                    
                    if ($optionId) {
                        $oldOption = QuestionOption::find($optionId);
                        if ($oldOption && $oldOption->gambar_opsi) {
                            Storage::disk('public')->delete($oldOption->gambar_opsi);
                        }
                    }
                } elseif (!empty($opsiData['existing_gambar_opsi'])) {
                    $gambarOpsiPath = $opsiData['existing_gambar_opsi'];
                }

                if ($optionId && in_array($optionId, $existingOptionIds)) {
                    $opsi = QuestionOption::find($optionId);
                    $opsi->update([
                        'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                        'gambar_opsi' => $gambarOpsiPath,
                        'is_correct' => $opsiData['is_benar'],
                    ]);
                    $processedOptionIds[] = $optionId;
                } else {
                    $opsi = QuestionOption::create([
                        'question_id' => $pertanyaan->id,
                        'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                        'gambar_opsi' => $gambarOpsiPath,
                        'is_correct' => $opsiData['is_benar'],
                    ]);
                    $processedOptionIds[] = $opsi->id;
                }
            }

            $orphanedOptions = QuestionOption::where('question_id', $pertanyaan->id)
                ->whereNotIn('id', $processedOptionIds)
                ->get();
            
            foreach ($orphanedOptions as $orphanedOption) {
                if ($orphanedOption->gambar_opsi) {
                    Storage::disk('public')->delete($orphanedOption->gambar_opsi);
                }
                $orphanedOption->delete();
            }
        }

        $orphanedQuestions = Question::where('quiz_id', $kuis->id)
            ->whereNotIn('id', $processedQuestionIds)
            ->get();
        
        foreach ($orphanedQuestions as $orphanedQuestion) {
            if ($orphanedQuestion->gambar_pertanyaan) {
                Storage::disk('public')->delete($orphanedQuestion->gambar_pertanyaan);
            }
            foreach ($orphanedQuestion->options as $opsi) {
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
        return redirect()->route($route, $kuis->module_id)->with('success', 'Kuis berhasil diupdate');
    }

    public function destroy(Quiz $kuis)
    {
        $moduleId = $kuis->module_id;
        $kuisName = $kuis->judul_kuis;

        $kuis->load('questions.options');

        foreach ($kuis->questions as $pertanyaan) {
            if ($pertanyaan->gambar_pertanyaan) {
                Storage::disk('public')->delete($pertanyaan->gambar_pertanyaan);
            }
            foreach ($pertanyaan->options as $opsi) {
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
