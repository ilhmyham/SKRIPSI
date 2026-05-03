<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        if (!$user->role) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Role tidak ditemukan');
        }

        // Check if user's role is in allowed roles
        if (!in_array($user->role->nama_role, $roles)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
