<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

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

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update Google ID if user exists but registered manually
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            } else {
                // Get siswa role ID
                $siswaRole = Role::where('nama_role', 'siswa')->first();
                
                if (!$siswaRole) {
                    return redirect()->route('login')->withErrors(['error' => 'Role siswa tidak ditemukan. Hubungi administrator.']);
                }

                // Create new user from Google
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role_id' => $siswaRole->id,
                    'password' => null, // No password for OAuth users
                ]);
            }

            // Log in the user
            Auth::login($user);

            return redirect()->route('siswa.dashboard')->with('success', 'Login berhasil! Selamat datang di LMS Bahasa Arab Isyarat.');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.']);
        }
    }
}
