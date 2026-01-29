<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'nama_role' => 'admin',
                'deskripsi' => 'Administrator dengan akses penuh ke sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'guru',
                'deskripsi' => 'Guru yang dapat mengelola materi, kuis, dan tugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'siswa',
                'deskripsi' => 'Siswa yang dapat belajar materi dan mengerjakan kuis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
