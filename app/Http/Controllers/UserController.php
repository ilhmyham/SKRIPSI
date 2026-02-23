<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $role = Role::find($validated['role_id']);

        $this->logActivity('created', 'User', $user->id, "Menambahkan pengguna baru \"" . $user->name . "\" sebagai " . $role->nama_role);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $this->logActivity('updated', 'User', $user->id, "Mengupdate data pengguna \"" . $user->name . "\"");

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();
        
        $this->logActivity('deleted', 'User', $user->id, "Menghapus pengguna \"" . $userName . "\"");
        
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|min:6',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->logActivity('reset', 'User', $user->id, "Mereset password untuk \"" . $user->name . "\"");

        return back()->with('success', 'Password berhasil direset');
    }
}
