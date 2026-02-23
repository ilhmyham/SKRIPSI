<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Fungsi helper untuk mempermudah input
        $seedKategori = function($namaModul, $daftarKategori) {
            $modul = Module::where('nama_modul', $namaModul)->first();
            
            if ($modul) {
                $urutan = 1;
                foreach ($daftarKategori as $kat) {
                    $slug = Str::slug($kat, '_');
                    
                    DB::table('material_categories')->updateOrInsert(
                        [
                            'nama' => $slug,
                            'module_id' => $modul->id
                        ],
                        [
                            'urutan' => $urutan,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                    $urutan++;
                }
            }
        };

        // 1. Kategori Iqra 2
        $seedKategori('Iqra 2', ['Fathah', 'Kasrah', 'Dammah']);

        // 2. Kategori Iqra 3
        $seedKategori('Iqra 3', ['Fathatain', 'Kasratain', 'Dammatain', 'Sukun', 'Tasydid']);

        // 3. Kategori Iqra 4
        $seedKategori('Iqra 4', ['Konsep Sambung', 'Latihan 2 Huruf', 'Latihan 3 Huruf', 'Latihan 4 Huruf']);

        // 4. Kategori Iqra 5
        $seedKategori('Iqra 5', ['Mad 2 Harakat', 'Mad 4 5 Harakat', 'Mad 6 Harakat']);

        // 5. Kategori Iqra 6
        $seedKategori('Iqra 6', ['Muqattaah', 'Tanda Sifir', 'Tanda Waqaf']);

        $this->command->info('✅ Seluruh Kategori Iqra 2 - 6 berhasil dibuat/diperbarui!');
    }
}
