@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Form Edit Pengguna">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <x-input 
                label="Nama Lengkap"
                name="name"
                type="text"
                :value="old('name', $user->name)"
                required
            />

            <x-input 
                label="Email"
                name="email"
                type="email"
                :value="old('email', $user->email)"
                required
            />

            <div>
                <label for="roles_role_id" class="block text-lg font-semibold mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select 
                    name="roles_role_id" 
                    id="roles_role_id"
                    required
                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition"
                >
                    @foreach($roles as $role)
                        <option value="{{ $role->role_id }}" {{ $user->roles_role_id == $role->role_id ? 'selected' : '' }}>
                            {{ ucfirst($role->nama_role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Reset Password (Opsional)</h3>
                <x-input 
                    label="Password Baru"
                    name="password"
                    type="password"
                    placeholder="Biarkan kosong jika tidak ingin mengganti password"
                />
            </div>

            <div class="flex gap-4 pt-4">
                <x-button type="submit" icon="check" class="flex-1 justify-center">
                    Update
                </x-button>
                <x-button variant="secondary" :href="route('admin.users.index')" class="flex-1 justify-center">
                    Batal
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
