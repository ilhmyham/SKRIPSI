<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class ResetIqra3MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Iqra 3 module ID
        $iqra3 = ModulIqra::where('nama_modul', 'Iqra 3')->first();
        
        if (!$iqra3) {
            $this->command->error('Modul Iqra 3 tidak ditemukan!');
            return;
        }

        // Count existing materials
        $oldCount = Materi::where('modul_iqra_modul_id', $iqra3->modul_id)->count();
        
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 3...");
        
        // Delete all old Iqra 3 materials
        Materi::where('modul_iqra_modul_id', $iqra3->modul_id)->delete();
        
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama!");
    }
}
