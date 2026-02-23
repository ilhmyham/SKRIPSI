<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class ResetIqra2MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Iqra 2 module ID
        $iqra2 = ModulIqra::where('nama_modul', 'Iqra 2')->first();
        
        if (!$iqra2) {
            $this->command->error('Modul Iqra 2 tidak ditemukan!');
            return;
        }

        // Count existing materials
        $oldCount = Materi::where('modul_iqra_modul_id', $iqra2->modul_id)->count();
        
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 2...");
        
        // Delete all old Iqra 2 materials
        Materi::where('modul_iqra_modul_id', $iqra2->modul_id)->delete();
        
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama!");
    }
}
