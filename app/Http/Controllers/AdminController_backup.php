<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ModulIqra;
use App\Models\Materi;
use App\Models\Kuis;
use App\Models\OpsiJawaban;
use App\Models\Pertanyaan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_guru' => User::whereHas('role', fn($q) => $q->where('nama_role', 'guru'))->count(),
            'total_siswa' => User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count(),
            'total_modules' => ModulIqra::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display users list
     */
    public function users(Request $request)
    {
        $query = User::with('role');

        if ($request->has('role') && $request->role) {
            $query->whereHas('role', fn($q) => $q->where('nama_role', $request->role));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles_role_id' => 'required|exists:roles,role_id',
        ]);

        $validated['password_2'] = Hash::make($validated['password']);
        unset($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'roles_role_id' => 'required|exists:roles,role_id',
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password_2'] = Hash::make($validated['password']);
        }
        unset($validated['password']);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diupdate');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|min:6',
        ]);

        $user->update([
            'password_2' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil direset');
    }

    /**
     * Display modules
     */
    public function modules()
    {
        $modules = ModulIqra::withCount('materi')->orderBy('modul_id')->get();
        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Show create module form
     */
    public function createModule()
    {
        return view('admin.modules.create');
    }

    /**
     * Store new module
     */
    public function storeModule(Request $request)
    {
        $validated = $request->validate([
            'nama_modul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        ModulIqra::create($validated);

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil ditambahkan');
    }

    /**
     * Show edit module form
     */
    public function editModule(ModulIqra $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    /**
     * Update module
     */
    public function updateModule(Request $request, ModulIqra $module)
    {
        $validated = $request->validate([
            'nama_modul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $module->update($validated);

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil diupdate');
    }

    /**
     * Delete module
     */
    public function destroyModule(ModulIqra $module)
    {
        $module->delete();
        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil dihapus');
    }

    /**
     * Display materi index (module cards)
     */
    public function materiIndex()
    {
        $modules = ModulIqra::withCount('materi')->orderBy('modul_id')->get();
        return view('admin.materi.index', compact('modules'));
    }

    /**
     * Display materi by module
     */
    public function materiByModule(ModulIqra $module)
    {
        $materis = $module->materi()->orderBy('created_at', 'desc')->get();
        $modules = ModulIqra::all(); // For dropdown in modal
        
        return view('admin.materi.show', compact('module', 'materis', 'modules'));
    }

    /**
     * Store new materi
     */
    public function storeMateri(Request $request)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
            'judul_materi' => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'file_video' => 'nullable|string',
            'file_path' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Add the user who created this materi
        $validated['users_user_id'] = auth()->id();

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('materi', 'public');
        }

        Materi::create($validated);

        return back()->with('success', 'Materi berhasil ditambahkan');
    }

    /**
     * Update materi
     */
    public function updateMateri(Request $request, Materi $materi)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
            'judul_materi' => 'required|string|max:255',
            'huruf_hijaiyah' => 'nullable|string|max:10',
            'video_url' => 'nullable|string',
            'gambar_isyarat' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('gambar_isyarat')) {
            // Delete old image
            if ($materi->gambar_isyarat) {
                Storage::disk('public')->delete($materi->gambar_isyarat);
            }
            $validated['gambar_isyarat'] = $request->file('gambar_isyarat')->store('materi', 'public');
        }

        $materi->update($validated);

        return back()->with('success', 'Materi berhasil diupdate');
    }

    /**
     * Delete materi
     */
    public function destroyMateri(Materi $materi)
    {
        // Delete image if exists
        if ($materi->gambar_isyarat) {
            Storage::disk('public')->delete($materi->gambar_isyarat);
        }

        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus');
    }


    /**
     * Display kuis index (module cards)
     */
    public function kuisIndex()
    {
        $modules = ModulIqra::withCount('kuis')->orderBy('modul_id')->get();
        return view('admin.kuis.index', compact('modules'));
    }

    /**
     * Display kuis by module
     */
    public function kuisByModule(ModulIqra $module)
    {
        $kuisList = Kuis::where('modul_iqra_modul_id', $module->modul_id)
                       ->withCount('pertanyaan')
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        return view('admin.kuis.show', compact('module', 'kuisList'));
    }

    /**
     * Show create kuis form
     */
    public function createKuis(Request $request)
    {
        $modules = ModulIqra::all();
        $moduleId = $request->query('module_id');
        
        return view('admin.kuis.create', compact('modules', 'moduleId'));
    }

    /**
     * Store new kuis
     */
    public function storeKuis(Request $request)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*.teks_pertanyaan' => 'required|string',
            'pertanyaan.*.opsi' => 'required|array|min:2',
            'pertanyaan.*.opsi.*.teks_opsi' => 'required|string',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ]);

        // Create kuis
        $kuis = Kuis::create([
            'modul_iqra_modul_id' => $validated['modul_iqra_modul_id'],
            'users_user_id' => auth()->id(),
            'judul_kuis' => $validated['judul_kuis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        // Create pertanyaan and opsi
        foreach ($validated['pertanyaan'] as $pertanyaanData) {
            $pertanyaan = Pertanyaan::create([
                'kuis_kuis_id' => $kuis->kuis_id,
                'teks_pertanyaan' => $pertanyaanData['teks_pertanyaan'],
            ]);

            foreach ($pertanyaanData['opsi'] as $opsiData) {
                OpsiJawaban::create([
                    'pertanyaan_pertanyaan_id' => $pertanyaan->pertanyaan_id,
                    'teks_opsi' => $opsiData['teks_opsi'],
                    'is_benar' => $opsiData['is_benar'],
                ]);
            }
        }

        return redirect()
            ->route('admin.kuis.by-module', $validated['modul_iqra_modul_id'])
            ->with('success', 'Kuis berhasil ditambahkan');
    }

    /**
     * Show edit kuis form
     */
    public function editKuis(Kuis $kuis)
    {
        $kuis->load(['pertanyaan.opsiJawaban']);
        $modules = ModulIqra::all();
        
        return view('admin.kuis.edit', compact('kuis', 'modules'));
    }

    /**
     * Update kuis
     */
    public function updateKuis(Request $request, Kuis $kuis)
    {
        $validated = $request->validate([
            'modul_iqra_modul_id' => 'required|exists:modul_iqra,modul_id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*.teks_pertanyaan' => 'required|string',
            'pertanyaan.*.opsi' => 'required|array|min:2',
            'pertanyaan.*.opsi.*.teks_opsi' => 'required|string',
            'pertanyaan.*.opsi.*.is_benar' => 'required|boolean',
        ]);

        // Update kuis
        $kuis->update([
            'modul_iqra_modul_id' => $validated['modul_iqra_modul_id'],
            'judul_kuis' => $validated['judul_kuis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        // Delete old pertanyaan and opsi
        $kuis->pertanyaan()->each(function ($pertanyaan) {
            $pertanyaan->opsiJawaban()->delete();
            $pertanyaan->delete();
        });

        // Create new pertanyaan and opsi
        foreach ($validated['pertanyaan'] as $pertanyaanData) {
            $pertanyaan = Pertanyaan::create([
                'kuis_kuis_id' => $kuis->kuis_id,
                'teks_pertanyaan' => $pertanyaanData['teks_pertanyaan'],
            ]);

            foreach ($pertanyaanData['opsi'] as $opsiData) {
                OpsiJawaban::create([
                    'pertanyaan_pertanyaan_id' => $pertanyaan->pertanyaan_id,
                    'teks_opsi' => $opsiData['teks_opsi'],
                    'is_benar' => $opsiData['is_benar'],
                ]);
            }
        }

        return redirect()
            ->route('admin.kuis.by-module', $kuis->modul_iqra_modul_id)
            ->with('success', 'Kuis berhasil diupdate');
    }

    /**
     * Delete kuis
     */
    public function destroyKuis(Kuis $kuis)
    {
        $moduleId = $kuis->modul_iqra_modul_id;
        $kuis->delete();
        
        return redirect()
            ->route('admin.kuis.by-module', $moduleId)
            ->with('success', 'Kuis berhasil dihapus');
    }
}
