<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;

class ResetIqra1MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Reset seeder untuk membersihkan materi Iqra 1.
     */
    public function run(): void
    {
        // 1. Mencari modul berdasarkan nama 'Iqra 1'
        $iqra1 = Module::where('nama_modul', 'Iqra 1')->first();

        if (!$iqra1) {
            $this->command->error('Modul Iqra 1 tidak ditemukan!');
            return;
        }

        // 2. Menghitung jumlah materi yang ada sebelum dihapus
        $oldCount = Material::where('module_id', $iqra1->id)->count();

        $this->command->info("Menghapus {$oldCount} materi lama Iqra 1...");

        // 3. Menghapus materi berdasarkan module_id
        Material::where('module_id', $iqra1->id)->delete();

        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama Iqra 1!");
    }
}