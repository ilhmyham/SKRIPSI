<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulIqraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['nama_modul' => 'Iqra 1', 'deskripsi' => 'Modul pembelajaran Iqra tingkat 1'],
            ['nama_modul' => 'Iqra 2', 'deskripsi' => 'Modul pembelajaran Iqra tingkat 2'],
            ['nama_modul' => 'Iqra 3', 'deskripsi' => 'Modul pembelajaran Iqra tingkat 3'],
            ['nama_modul' => 'Iqra 4', 'deskripsi' => 'Modul pembelajaran Iqra tingkat 4'],
            ['nama_modul' => 'Iqra 5', 'deskripsi' => 'Modul pembelajaran Iqra tingkat 5'],
            ['nama_modul' => 'Iqra 6', 'deskripsi' => 'Modul pembelajaran Iqra tingkat 6'],
        ];

        foreach ($modules as $module) {
            DB::table('modul_iqra')->insert([
                'nama_modul' => $module['nama_modul'],
                'deskripsi' => $module['deskripsi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
