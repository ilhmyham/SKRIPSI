<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use App\Models\ModulIqra;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KuisController extends Controller
{
    /**
     * Display kuis index (module cards)
     */
    public function index()
    {
        $modules = ModulIqra::withCount('kuis')->orderBy('modul_id')->get();
        
        // Determine which view to use based on user role
        $view = auth()->user()->isAdmin() ? 'admin.kuis.index' : 'guru.kuis.index';
        
        return view($view, compact('modules'));
    }

    /**
     * Display kuis by module
     */
    public function byModule(ModulIqra $module)
    {
        $kuisList = Kuis::where('modul_iqra_modul_id', $module->modul_id)
                       ->withCount('pertanyaan')
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        // Determine which view to use based on user role
        $view = auth()->user()->isAdmin() ? 'admin.kuis.show' : 'guru.kuis.show';
        
        return view($view, compact('module', 'kuisList'));
    }

    /**
     * Show create kuis form
     */
    public function create(Request $request)
    {
        $modules = ModulIqra::all();
        $moduleId = $request->query('module_id');
        
        // Determine which view to use based on user role
        $view = auth()->user()->isAdmin() ? 'admin.kuis.create' : 'guru.kuis.create';
        
        return view($view, compact('modules', 'moduleId'));
    }

    /**
     * Store new kuis
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
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

        // Create kuis
        $kuis = Kuis::create([
            'modul_iqra_modul_id' => $validated['modul_iqra_modul_id'],
            'users_user_id' => auth()->id(),
            'judul_kuis' => $validated['judul_kuis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        // Create pertanyaan and opsi with image upload
        foreach ($validated['pertanyaan'] as $index => $pertanyaanData) {
            // Handle question image upload
            $gambarPath = null;
            if ($request->hasFile("pertanyaan.{$index}.gambar_pertanyaan")) {
                $gambarPath = $request->file("pertanyaan.{$index}.gambar_pertanyaan")
                                      ->store('kuis/pertanyaan', 'public');
            }
            
            $pertanyaan = Pertanyaan::create([
                'kuis_id' => $kuis->kuis_id,
                'text_pertanyaan' => $pertanyaanData['text_pertanyaan'] ?? null,
                'gambar_pertanyaan' => $gambarPath,
            ]);

            foreach ($pertanyaanData['opsi'] as $oIndex => $opsiData) {
                $gambarOpsiPath = null;
                if ($request->hasFile("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")) {
                    $gambarOpsiPath = $request->file("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")
                                              ->store('kuis/opsi', 'public');
                }
                
                OpsiJawaban::create([
                    'pertanyaan_id' => $pertanyaan->pertanyaan_id,
                    'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                    'gambar_opsi' => $gambarOpsiPath,
                    'is_benar' => $opsiData['is_benar'],
                ]);
            }
        }

        // Log activity only for admin
        if (auth()->user()->isAdmin()) {
            $module = ModulIqra::find($validated['modul_iqra_modul_id']);
            $this->logActivity('created', 'Kuis', $kuis->kuis_id, "Membuat kuis \"" . $kuis->judul_kuis . "\" untuk " . $module->nama_modul);
        }

        // Redirect based on user role
        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        
        return redirect()
            ->route($route, $validated['modul_iqra_modul_id'])
            ->with('success', 'Kuis berhasil ditambahkan');
    }

    /**
     * Show edit kuis form
     */
    public function edit(Kuis $kuis)
    {
        $kuis->load(['pertanyaan.opsiJawaban']);
        $modules = ModulIqra::all();
        
        // Prepare data for Alpine.js - include IDs for proper update logic
        $quizData = $kuis->pertanyaan->map(function($p) {
            return [
                'id' => $p->pertanyaan_id, // IMPORTANT: Include ID for update
                'text_pertanyaan' => $p->text_pertanyaan,
                'existing_gambar' => $p->gambar_pertanyaan,
                'gambar_preview' => null,
                'opsi' => $p->opsiJawaban->map(function($o) {
                    return [
                        'id' => $o->opsi_id, // IMPORTANT: Include ID for update
                        'teks_opsi' => $o->teks_opsi,
                        'existing_gambar' => $o->gambar_opsi,
                        'gambar_preview' => null,
                        'is_benar' => (bool)$o->is_benar
                    ];
                })->toArray()
            ];
        })->toArray();
        
        // Determine which view to use based on user role
        $view = auth()->user()->isAdmin() ? 'admin.kuis.edit' : 'guru.kuis.edit';
        
        return view($view, compact('kuis', 'modules', 'quizData'));
    }

    /**
     * Update kuis - PROPER UPDATE LOGIC (not delete-recreate)
     */
    public function update(Request $request, Kuis $kuis)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*.id' => 'nullable|integer|exists:pertanyaan,pertanyaan_id', // ID if updating existing
            'pertanyaan.*.text_pertanyaan' => 'nullable|string',
            'pertanyaan.*.gambar_pertanyaan' => 'nullable|image|max:2048',
            'pertanyaan.*.existing_gambar_pertanyaan' => 'nullable|string',
            'pertanyaan.*.opsi' => 'required|array|min:2',
            'pertanyaan.*.opsi.*.id' => 'nullable|integer|exists:opsi_jawaban,opsi_id', // ID if updating existing
            'pertanyaan.*.opsi.*.teks_opsi' => 'nullable|string',
            'pertanyaan.*.opsi.*.gambar_opsi' => 'nullable|image|max:2048',
            'pertanyaan.*.opsi.*.existing_gambar_opsi' => 'nullable|string',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ]);

        // Update kuis basic info
        $kuis->update([
            'modul_iqra_modul_id' => $validated['modul_iqra_modul_id'],
            'judul_kuis' => $validated['judul_kuis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        // Get existing question IDs and option IDs
        $existingQuestionIds = $kuis->pertanyaan->pluck('pertanyaan_id')->toArray();
        $processedQuestionIds = [];
        $processedOptionIds = [];

        // Process each question in the request
        foreach ($validated['pertanyaan'] as $index => $pertanyaanData) {
            $questionId = $pertanyaanData['id'] ?? null;

            // Handle question image
            $gambarPath = null;
            if ($request->hasFile("pertanyaan.{$index}.gambar_pertanyaan")) {
                // New upload
                $gambarPath = $request->file("pertanyaan.{$index}.gambar_pertanyaan")
                                      ->store('kuis/pertanyaan', 'public');
                
                // Delete old image if exists and we're updating
                if ($questionId) {
                    $oldQuestion = Pertanyaan::find($questionId);
                    if ($oldQuestion && $oldQuestion->gambar_pertanyaan) {
                        Storage::disk('public')->delete($oldQuestion->gambar_pertanyaan);
                    }
                }
            } elseif (!empty($pertanyaanData['existing_gambar_pertanyaan'])) {
                // Keep existing image
                $gambarPath = $pertanyaanData['existing_gambar_pertanyaan'];
            }

            // UPDATE or CREATE question
            if ($questionId && in_array($questionId, $existingQuestionIds)) {
                // UPDATE existing question
                $pertanyaan = Pertanyaan::find($questionId);
                $pertanyaan->update([
                    'text_pertanyaan' => $pertanyaanData['text_pertanyaan'] ?? null,
                    'gambar_pertanyaan' => $gambarPath,
                ]);
                $processedQuestionIds[] = $questionId;
            } else {
                // CREATE new question
                $pertanyaan = Pertanyaan::create([
                    'kuis_id' => $kuis->kuis_id,
                    'text_pertanyaan' => $pertanyaanData['text_pertanyaan'] ?? null,
                    'gambar_pertanyaan' => $gambarPath,
                ]);
                $processedQuestionIds[] = $pertanyaan->pertanyaan_id;
            }

            // Get existing option IDs for this question
            $existingOptionIds = $pertanyaan->opsiJawaban->pluck('opsi_id')->toArray();

            // Process each option
            foreach ($pertanyaanData['opsi'] as $oIndex => $opsiData) {
                $optionId = $opsiData['id'] ?? null;

                // Handle option image
                $gambarOpsiPath = null;
                if ($request->hasFile("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")) {
                    // New upload
                    $gambarOpsiPath = $request->file("pertanyaan.{$index}.opsi.{$oIndex}.gambar_opsi")
                                              ->store('kuis/opsi', 'public');
                    
                    // Delete old image if exists and we're updating
                    if ($optionId) {
                        $oldOption = OpsiJawaban::find($optionId);
                        if ($oldOption && $oldOption->gambar_opsi) {
                            Storage::disk('public')->delete($oldOption->gambar_opsi);
                        }
                    }
                } elseif (!empty($opsiData['existing_gambar_opsi'])) {
                    // Keep existing image
                    $gambarOpsiPath = $opsiData['existing_gambar_opsi'];
                }

                // UPDATE or CREATE option
                if ($optionId && in_array($optionId, $existingOptionIds)) {
                    // UPDATE existing option
                    $opsi = OpsiJawaban::find($optionId);
                    $opsi->update([
                        'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                        'gambar_opsi' => $gambarOpsiPath,
                        'is_benar' => $opsiData['is_benar'],
                    ]);
                    $processedOptionIds[] = $optionId;
                } else {
                    // CREATE new option
                    $opsi = OpsiJawaban::create([
                        'pertanyaan_id' => $pertanyaan->pertanyaan_id,
                        'teks_opsi' => $opsiData['teks_opsi'] ?? null,
                        'gambar_opsi' => $gambarOpsiPath,
                        'is_benar' => $opsiData['is_benar'],
                    ]);
                    $processedOptionIds[] = $opsi->opsi_id;
                }
            }

            // DELETE orphaned options for this question
            $orphanedOptions = OpsiJawaban::where('pertanyaan_id', $pertanyaan->pertanyaan_id)
                ->whereNotIn('opsi_id', $processedOptionIds)
                ->get();
            
            foreach ($orphanedOptions as $orphanedOption) {
                // Delete image if exists
                if ($orphanedOption->gambar_opsi) {
                    Storage::disk('public')->delete($orphanedOption->gambar_opsi);
                }
                $orphanedOption->delete();
            }
        }

        // DELETE orphaned questions
        $orphanedQuestions = Pertanyaan::where('kuis_id', $kuis->kuis_id)
            ->whereNotIn('pertanyaan_id', $processedQuestionIds)
            ->get();
        
        foreach ($orphanedQuestions as $orphanedQuestion) {
            // Delete question image
            if ($orphanedQuestion->gambar_pertanyaan) {
                Storage::disk('public')->delete($orphanedQuestion->gambar_pertanyaan);
            }
            
            // Delete associated options and their images
            foreach ($orphanedQuestion->opsiJawaban as $opsi) {
                if ($opsi->gambar_opsi) {
                    Storage::disk('public')->delete($opsi->gambar_opsi);
                }
                $opsi->delete();
            }
            
            $orphanedQuestion->delete();
        }

        // Log activity only for admin
        if (auth()->user()->isAdmin()) {
            $this->logActivity('updated', 'Kuis', $kuis->kuis_id, "Mengupdate kuis \"" . $kuis->judul_kuis . "\"");
        }

        // Redirect based on user role
        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';

        return redirect()
            ->route($route, $kuis->modul_iqra_modul_id)
            ->with('success', 'Kuis berhasil diupdate');
    }

    /**
     * Delete kuis
     */
    public function destroy(Kuis $kuis)
    {
        $moduleId = $kuis->modul_iqra_modul_id;
        $kuisName = $kuis->judul_kuis;
        
        // Delete associated images
        foreach ($kuis->pertanyaan as $pertanyaan) {
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
        
        // Log activity only for admin
        if (auth()->user()->isAdmin()) {
            $this->logActivity('deleted', 'Kuis', $kuis->kuis_id, "Menghapus kuis \"" . $kuisName . "\"");
        }
        
        // Redirect based on user role
        $route = auth()->user()->isAdmin() ? 'admin.kuis.by-module' : 'guru.kuis.by-module';
        
        return redirect()
            ->route($route, $moduleId)
            ->with('success', 'Kuis berhasil dihapus');
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
