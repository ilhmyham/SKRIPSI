<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;

class ResetIqra3MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra3 = Module::where('nama_modul', 'Iqra 3')->first();

        if (!$iqra3) {
            $this->command->error('Modul Iqra 3 tidak ditemukan!');
            return;
        }

        $oldCount = Material::where('module_id', $iqra3->id)->count();
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 3...");
        Material::where('module_id', $iqra3->id)->delete();
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama Iqra 3!");
    }
}
