<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;

class ResetIqra6MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra6 = Module::where('nama_modul', 'Iqra 6')->first();

        if (!$iqra6) {
            $this->command->error('Modul Iqra 6 tidak ditemukan!');
            return;
        }

        $oldCount = Material::where('module_id', $iqra6->id)->count();
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 6...");
        Material::where('module_id', $iqra6->id)->delete();
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama Iqra 6!");
    }
}
