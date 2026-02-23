<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class DebugIqra3ViewSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== DEBUGGING IQRA 3 VIEW ===');
        
        // Simulate what controller does
        $currentModuleId = 3; // Iqra 3
        $currentModule = ModulIqra::find($currentModuleId);
        
        if (!$currentModule) {
            $this->command->error('❌ Module Iqra 3 not found!');
            return;
        }
        
        $this->command->info("✅ Current Module: {$currentModule->nama_modul} (ID: {$currentModule->modul_id})");
        
        // Get materials
        $materis = Materi::where('modul_iqra_modul_id', $currentModuleId)
            ->orderBy('created_at')
            ->get();
        
        $this->command->info("✅ Total Materi: {$materis->count()}");
        
        // Check kategori
        $hasKategori = $materis->whereNotNull('kategori')->count() > 0;
        $this->command->info("Has Kategori: " . ($hasKategori ? 'YES' : 'NO'));
        
        if ($hasKategori) {
            $this->command->info("\nKategori breakdown:");
            $grouped = $materis->groupBy('kategori');
            foreach ($grouped as $kat => $items) {
                $this->command->info("  - {$kat}: {$items->count()} materi");
            }
        }
        
        // Check what view will see
        if ($materis->count() > 0) {
            $this->command->info("\n✅ VIEW SHOULD DISPLAY:");
            if ($hasKategori) {
                $this->command->info("  → Tab navigation for categories");
            } else {
                $this->command->info("  → Normal grid layout");
            }
            
            $this->command->info("\nFirst 5 materials:");
            $materis->take(5)->each(function($m) {
                $this->command->info("  - {$m->judul_materi} ({$m->huruf_hijaiyah})");
            });
        } else {
            $this->command->error("\n❌ NO MATERIALS FOUND - View will show 'Belum ada materi'");
        }
    }
}
