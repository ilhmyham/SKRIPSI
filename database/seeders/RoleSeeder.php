<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'nama_role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'siswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
