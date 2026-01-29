@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<!-- Header removed - now in layout -->

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--color-text-secondary);">Total Pengguna</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--color-primary);">{{ $stats['total_users'] }}</p>
                </div>
                <div class="p-4 bg-green-100 rounded-full">
                    <x-icon name="users" class="w-8 h-8" style="color: var(--color-primary);" />
                </div>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--color-text-secondary);">Total Guru</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--color-primary);">{{ $stats['total_guru'] }}</p>
                </div>
                <div class="p-4 bg-blue-100 rounded-full">
                    <x-icon name="dashboard" class="w-8 h-8 text-blue-600" />
                </div>
            </div>
        </div>

        <!-- Total Siswa -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--color-text-secondary);">Total Siswa</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--color-primary);">{{ $stats['total_siswa'] }}</p>
                </div>
                <div class="p-4 bg-purple-100 rounded-full">
                    <x-icon name="check" class="w-8 h-8 text-purple-600" />
                </div>
            </div>
        </div>

        <!-- Total Modules -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--color-text-secondary);">Modul Iqra</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--color-primary);">{{ $stats['total_modules'] }}</p>
                </div>
                <div class="p-4 bg-yellow-100 rounded-full">
                    <x-icon name="book-open" class="w-8 h-8 text-yellow-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: Quick Actions & Activity History -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions (Left) -->
        <div class="card">
            <h2 class="text-2xl font-bold mb-6">Aksi Cepat</h2>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary text-center">
                    <x-icon name="plus" class="w-6 h-6" />
                    Tambah Pengguna Baru
                </a>
                <a href="{{ route('admin.modules.index') }}" class="btn btn-primary text-center">
                    <x-icon name="plus" class="w-6 h-6" />
                    Tambah Modul Iqra
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary text-center">
                    <x-icon name="users" class="w-6 h-6" />
                    Kelola Pengguna
                </a>
            </div>
        </div>

        <!-- Activity History (Right) -->
        <div class="card">
            <h2 class="text-2xl font-bold mb-6">Riwayat Aktivitas</h2>
            
            @if($recentActivities->count() > 0)
                <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($recentActivities as $activity)
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <!-- User Avatar/Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: var(--color-primary-dark);">
                                    <span class="text-white font-semibold text-sm">
                                        {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Activity Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $activity->user->name ?? 'Unknown User' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $activity->description }}
                                        </p>
                                    </div>
                                    
                                    <!-- Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->badge_color }}">
                                        @switch($activity->activity_type)
                                            @case('created')
                                                <x-icon name="plus" class="w-3 h-3 mr-1" />
                                                @break
                                            @case('updated')
                                                <x-icon name="edit" class="w-3 h-3 mr-1" />
                                                @break
                                            @case('deleted')
                                                <x-icon name="trash" class="w-3 h-3 mr-1" />
                                                @break
                                            @case('reset')
                                                <x-icon name="arrow-down" class="w-3 h-3 mr-1" />
                                                @break
                                        @endswitch
                                        {{ ucfirst($activity->activity_type) }}
                                    </span>
                                </div>
                                
                                <!-- Timestamp -->
                                <p class="text-xs" style="color: var(--color-text-muted); margin-top: 0.5rem;">
                                    {{ $activity->relative_time }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <x-icon name="document" class="mx-auto h-12 w-12 text-gray-400" />
                    <p class="mt-4 text-sm text-gray-500">Belum ada aktivitas</p>
                </div>
            @endif
        </div>
    </div>
@endsection
