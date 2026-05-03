<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\KuisPertanyaan;
use App\Models\KuisOpsiJawaban;
use App\Models\ModulIqra;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KuisController extends Controller
{
    public function index()
    {
        $modules = ModulIqra::withCount('kuis')->orderBy('id')->get();
        $view = auth()->user()->isAdmin() ? 'admin.kuis.index' : 'guru.kuis.index';
        return view($view, compact('modules'));
    }

    public function byModule(ModulIqra $module)
    {
        $kuisList = Kuis::where('modul_iqra_id', $module->id)
                       ->withCount('kuisPertanyaan')
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        $view = auth()->user()->isAdmin() ? 'admin.kuis.show' : 'guru.kuis.show';
        return view($view, compact('module', 'kuisList'));
    }

    public function create(Request $request)
    {
        $modules = ModulIqra::all();
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
            
            'pertanyaan.*.teks_pertanyaan' => 'required_without:pertanyaan.*.gambar_pertanyaan|nullable|string',
            'pertanyaan.*.gambar_pertanyaan' => 'required_without:pertanyaan.*.teks_pertanyaan|nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            'pertanyaan.*.opsi' => 'required|array|min:2',
            
            'pertanyaan.*.opsi.*.teks_opsi' => 'required_without:pertanyaan.*.opsi.*.gambar_opsi|nullable|string',
            'pertanyaan.*.opsi.*.gambar_opsi' => 'required_without:pertanyaan.*.opsi.*.teks_opsi|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ]);

        $kuis = DB::transaction(function () use ($validated, $request) {
            $kuis = Kuis::create([
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
                
                $pertanyaan = KuisPertanyaan::create([
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
                    
                    KuisOpsiJawaban::create([
                        'kuis_pertanyaan_id' => $pertanyaan->id,
                        'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                        'gambar_opsi' => $gambarOpsiPath,
                        'is_correct' => $opsiData['is_benar'],
                    ]);
                }
            }
            return $kuis;
        });

        $module = ModulIqra::find($validated['modul_iqra_id']);
        $this->logActivity('created', 'Quiz', $kuis->id, "Membuat kuis \"" . $kuis->judul_kuis . "\" untuk " . $module->nama_modul);

        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        return redirect()->route($route, $validated['modul_iqra_id'])->with('success', 'Kuis berhasil ditambahkan');
    }

    public function edit(Kuis $kuis)
    {
        $kuis->load(['kuisPertanyaan.opsiJawaban']);
        $modules = ModulIqra::all();
        
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

    public function update(Request $request, Kuis $kuis)
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
        ]);

        DB::transaction(function () use ($validated, $request, $kuis) {
            $kuis->update([
                'modul_iqra_id' => $validated['modul_iqra_id'],
                'judul_kuis' => $validated['judul_kuis'],
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            $existingQuestionIds = $kuis->kuisPertanyaan->pluck('id')->toArray();
            $processedQuestionIds = [];

            foreach ($validated['pertanyaan'] as $index => $pertanyaanData) {
                $questionId = $pertanyaanData['id'] ?? null;
                $oldQuestion = $questionId ? KuisPertanyaan::find($questionId) : null;
                $gambarPath = null;

                if ($request->hasFile("pertanyaan.{$index}.gambar_pertanyaan")) {
                    $gambarPath = $request->file("pertanyaan.{$index}.gambar_pertanyaan")->store('kuis/pertanyaan', 'public');
                    if ($oldQuestion && $oldQuestion->gambar_pertanyaan) {
                        Storage::disk('public')->delete($oldQuestion->gambar_pertanyaan);
                    }
                } else {
                    $gambarPath = $pertanyaanData['existing_gambar_pertanyaan'] ?? null;
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
                    $pertanyaan = $oldQuestion; 
                } else {
                    $pertanyaan = KuisPertanyaan::create([
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
                    $oldOption = $optionId ? KuisOpsiJawaban::find($optionId) : null;
                    $gambarOpsiPath = null;

                    if ($request->hasFile("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")) {
                        $gambarOpsiPath = $request->file("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")->store('kuis/opsi', 'public');
                        if ($oldOption && $oldOption->gambar_opsi) {
                            Storage::disk('public')->delete($oldOption->gambar_opsi);
                        }
                    } else {
                        $gambarOpsiPath = $opsiData['existing_gambar_opsi'] ?? null;
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
                        $opsi = KuisOpsiJawaban::create([
                            'kuis_pertanyaan_id' => $pertanyaan->id,
                            'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                            'gambar_opsi' => $gambarOpsiPath,
                            'is_correct' => $opsiData['is_benar'],
                        ]);
                        $processedOptionIdsForThisQuestion[] = $opsi->id;
                    }
                }
                
                $orphanedOptions = KuisOpsiJawaban::where('kuis_pertanyaan_id', $pertanyaan->id)
                    ->whereNotIn('id', $processedOptionIdsForThisQuestion)
                    ->get();
                
                foreach ($orphanedOptions as $orphanedOption) {
                    if ($orphanedOption->gambar_opsi) {
                        Storage::disk('public')->delete($orphanedOption->gambar_opsi);
                    }
                    $orphanedOption->delete();
                }
            }

            $orphanedQuestions = KuisPertanyaan::where('kuis_id', $kuis->id)
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
        });

        $this->logActivity('updated', 'Quiz', $kuis->id, "Mengupdate kuis \"" . $kuis->judul_kuis . "\"");

        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        return redirect()->route($route, $kuis->modul_iqra_id)->with('success', 'Kuis berhasil diupdate');
    }

    public function destroy(Kuis $kuis)
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

        $this->logActivity('deleted', 'Quiz', $kuis->id, "Menghapus kuis \"" . $kuisName . "\"");

        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        return redirect()->route($route, $moduleId)->with('success', 'Kuis berhasil dihapus');
    }
}