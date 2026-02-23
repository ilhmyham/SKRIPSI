<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\KuisController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;

// Homepage - show landing page, redirect logged-in users to their dashboard
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $user->load('role');
        
        if ($user->isAdmin()) return redirect('/admin/dashboard');
        if ($user->isGuru()) return redirect('/guru/dashboard');
        if ($user->isSiswa()) return redirect('/siswa/dashboard');
    }
    return view('welcome');
})->name('home');


// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Google OAuth Routes
Route::get('/auth/google', [RegisterController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [RegisterController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management - Now using UserController
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    
    // Module Management - Now using ModuleController
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
    
    // Material Management - Now using shared MateriController
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/module/{module}', [MateriController::class, 'byModule'])->name('materi.by-module');
    Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
    
    // Quiz Management - Now using shared KuisController
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/module/{module}', [KuisController::class, 'byModule'])->name('kuis.by-module');
    Route::get('/kuis/create', [KuisController::class, 'create'])->name('kuis.create');
    Route::post('/kuis', [KuisController::class, 'store'])->name('kuis.store');
    Route::get('/kuis/{kuis}/edit', [KuisController::class, 'edit'])->name('kuis.edit');
    Route::put('/kuis/{kuis}', [KuisController::class, 'update'])->name('kuis.update');
    Route::delete('/kuis/{kuis}', [KuisController::class, 'destroy'])->name('kuis.destroy');
});

// Guru (Teacher) Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    
    // Material Management - Now using shared MateriController
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/module/{module}', [MateriController::class, 'byModule'])->name('materi.by-module');
    Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
    
    // Quiz Management - Now using shared KuisController
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/module/{module}', [KuisController::class, 'byModule'])->name('kuis.by-module');
    Route::get('/kuis/create', [KuisController::class, 'create'])->name('kuis.create');
    Route::post('/kuis', [KuisController::class, 'store'])->name('kuis.store');
    Route::get('/kuis/{kuis}/edit', [KuisController::class, 'edit'])->name('kuis.edit');
    Route::put('/kuis/{kuis}', [KuisController::class, 'update'])->name('kuis.update');
    Route::delete('/kuis/{kuis}', [KuisController::class, 'destroy'])->name('kuis.destroy');
    
    // Assignment Management
    Route::get('/tugas', [GuruController::class, 'tugas'])->name('tugas.index');
    Route::get('/tugas/create', [GuruController::class, 'createTugas'])->name('tugas.create');
    Route::post('/tugas', [GuruController::class, 'storeTugas'])->name('tugas.store');
    Route::get('/tugas/{tugas}/edit', [GuruController::class, 'editTugas'])->name('tugas.edit');
    Route::put('/tugas/{tugas}', [GuruController::class, 'updateTugas'])->name('tugas.update');
    Route::delete('/tugas/{tugas}', [GuruController::class, 'destroyTugas'])->name('tugas.destroy');
    Route::get('/tugas/{tugas}/submissions', [GuruController::class, 'submissions'])->name('tugas.submissions');
    Route::post('/submissions/{submission}/grade', [GuruController::class, 'gradeSubmission'])->name('submissions.grade');
    
    // Progress Monitoring
    Route::get('/progress', [GuruController::class, 'progress'])->name('progress.index');
    Route::get('/progress/{user}', [GuruController::class, 'studentProgress'])->name('progress.show');
});

// Siswa (Student) Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    
    // Learning Materials
    Route::get('/materi', [SiswaController::class, 'materi'])->name('materi.index');
    Route::post('/materi/{materi}/complete', [SiswaController::class, 'completeMateri'])->name('materi.complete');
    
    // Quizzes
    Route::get('/kuis', [SiswaController::class, 'kuis'])->name('kuis.index');
    Route::get('/kuis/{kuis}', [SiswaController::class, 'showKuis'])->name('kuis.show');
    Route::post('/kuis/{kuis}/start', [SiswaController::class, 'startKuis'])->name('kuis.start');
    Route::post('/kuis/{kuis}/submit', [SiswaController::class, 'submitKuis'])->name('kuis.submit');
    Route::get('/kuis/{kuis}/results', [SiswaController::class, 'kuisResults'])->name('kuis.results');
    
    // Assignments
    Route::get('/tugas', [SiswaController::class, 'tugas'])->name('tugas.index');
    Route::get('/tugas/{tugas}', [SiswaController::class, 'showTugas'])->name('tugas.show');
    Route::post('/tugas/{tugas}/submit', [SiswaController::class, 'submitTugas'])->name('tugas.submit');
    
    // Profile
    Route::get('/profile', [SiswaController::class, 'profile'])->name('profile');
    Route::put('/profile', [SiswaController::class, 'updateProfile'])->name('profile.update');
});
