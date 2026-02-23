<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('nama_role', 'admin')->first()->id;
        $guruRoleId = DB::table('roles')->where('nama_role', 'guru')->first()->id;
        $siswaRoleId = DB::table('roles')->where('nama_role', 'siswa')->first()->id;

        DB::table('users')->insert([
            'role_id' => $adminRoleId,
            'name' => 'Administrator',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'role_id' => $guruRoleId,
            'name' => 'Guru Contoh',
            'email' => 'guru@lms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'role_id' => $siswaRoleId,
            'name' => 'Siswa Contoh',
            'email' => 'siswa@lms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
