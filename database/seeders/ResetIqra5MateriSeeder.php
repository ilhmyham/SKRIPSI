<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;

class ResetIqra5MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra5 = Module::where('nama_modul', 'Iqra 5')->first();

        if (!$iqra5) {
            $this->command->error('Modul Iqra 5 tidak ditemukan!');
            return;
        }

        $oldCount = Material::where('module_id', $iqra5->id)->count();
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 5...");
        Material::where('module_id', $iqra5->id)->delete();
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama Iqra 5!");
    }
}
