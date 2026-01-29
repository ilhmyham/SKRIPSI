<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $adminRoleId = DB::table('roles')->where('nama_role', 'admin')->first()->role_id;
        $guruRoleId = DB::table('roles')->where('nama_role', 'guru')->first()->role_id;
        $siswaRoleId = DB::table('roles')->where('nama_role', 'siswa')->first()->role_id;

        // Create admin user
        DB::table('users')->insert([
            'roles_role_id' => $adminRoleId,
            'name' => 'Administrator',
            'email' => 'admin@lms.com',
            'password_2' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create sample guru user
        DB::table('users')->insert([
            'roles_role_id' => $guruRoleId,
            'name' => 'Guru Contoh',
            'email' => 'guru@lms.com',
            'password_2' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create sample siswa user
        DB::table('users')->insert([
            'roles_role_id' => $siswaRoleId,
            'name' => 'Siswa Contoh',
            'email' => 'siswa@lms.com',
            'password_2' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
