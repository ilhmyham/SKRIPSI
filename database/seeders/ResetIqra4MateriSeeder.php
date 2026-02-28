<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;

class ResetIqra4MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra4 = Module::where('nama_modul', 'Iqra 4')->first();

        if (!$iqra4) {
            $this->command->error('Modul Iqra 4 tidak ditemukan!');
            return;
        }

        $oldCount = Material::where('module_id', $iqra4->id)->count();
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 4...");
        Material::where('module_id', $iqra4->id)->delete();
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama Iqra 4!");
    }
}
