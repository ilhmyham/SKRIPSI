<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display users list
     */
    public function index(Request $request)
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
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles_role_id' => 'required|exists:roles,role_id',
        ]);

        $validated['password_2'] = Hash::make($validated['password']);
        unset($validated['password']);

        $user = User::create($validated);
        $role = Role::find($validated['roles_role_id']);

        $this->logActivity('created', 'User', $user->id, "Menambahkan pengguna baru \"" . $user->name . "\" sebagai " . $role->nama_role);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles_role_id' => 'required|exists:roles,role_id',
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password_2'] = Hash::make($validated['password']);
        }
        unset($validated['password']);

        $user->update($validated);

        $this->logActivity('updated', 'User', $user->id, "Mengupdate data pengguna \"" . $user->name . "\"");

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diupdate');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();
        
        $this->logActivity('deleted', 'User', $user->id, "Menghapus pengguna \"" . $userName . "\"");
        
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

        $this->logActivity('reset', 'User', $user->id, "Mereset password untuk \"" . $user->name . "\"");

        return back()->with('success', 'Password berhasil direset');
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
