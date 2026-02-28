<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle manual registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get siswa role ID
        $siswaRole = Role::where('nama_role', 'siswa')->first();
        
        if (!$siswaRole) {
            return back()->withErrors(['error' => 'Role siswa tidak ditemukan. Hubungi administrator.']);
        }

        // Create new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $siswaRole->id,
        ]);

        // Log in the user
        Auth::login($user);

        return redirect()->route('siswa.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di LMS Bahasa Arab Isyarat.');
    }

}

