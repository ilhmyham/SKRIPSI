<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_guru' => User::whereHas('role', fn($q) => $q->where('nama_role', 'guru'))->count(),
            'total_siswa' => User::whereHas('role', fn($q) => $q->where('nama_role', 'siswa'))->count(),
            'total_modules' => Module::count(),
        ];
        
        $recentActivities = ActivityLog::with('user')->recent(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recentActivities'));
    }
}