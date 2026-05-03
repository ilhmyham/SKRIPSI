<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;

class ResetIqra2MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra2 = Module::where('nama_modul', 'Iqra 2')->first();

        if (!$iqra2) {
            $this->command->error('Modul Iqra 2 tidak ditemukan!');
            return;
        }

        $oldCount = Material::where('module_id', $iqra2->id)->count();

        $this->command->info("Menghapus {$oldCount} materi lama Iqra 2...");

        Material::where('module_id', $iqra2->id)->delete();

        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama Iqra 2!");
    }
}
