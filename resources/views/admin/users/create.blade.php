@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Form Tambah Pengguna">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <x-input 
                label="Nama Lengkap"
                name="name"
                type="text"
                :value="old('name')"
                required
                placeholder="Masukkan nama lengkap"
            />

            <x-input 
                label="Email"
                name="email"
                type="email"
                :value="old('email')"
                required
                placeholder="contoh@email.com"
            />

            <div>
                <label for="roles_role_id" class="block text-lg font-semibold mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select 
                    name="roles_role_id" 
                    id="roles_role_id"
                    required
                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition @error('roles_role_id') border-red-500 @enderror"
                >
                    <option value="">Pilih Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->role_id }}" {{ old('roles_role_id') == $role->role_id ? 'selected' : '' }}>
                            {{ ucfirst($role->nama_role) }}
                        </option>
                    @endforeach
                </select>
                @error('roles_role_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-input 
                label="Password"
                name="password"
                type="password"
                required
                placeholder="Minimal 8 karakter"
            />

            <div class="flex gap-4 pt-4">
                <x-button type="submit" icon="check" class="flex-1 justify-center">
                    Simpan
                </x-button>
                <x-button variant="secondary" :href="route('admin.users.index')" class="flex-1 justify-center">
                    Batal
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
