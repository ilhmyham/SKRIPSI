<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Iqra 2
            ['module_id' => 2, 'nama' => 'fathah', 'urutan' => 1],
            ['module_id' => 2, 'nama' => 'kasrah', 'urutan' => 2],
            ['module_id' => 2, 'nama' => 'dhomah', 'urutan' => 3],
            // Iqra 3
            ['module_id' => 3, 'nama' => 'fathatain', 'urutan' => 1],
            ['module_id' => 3, 'nama' => 'kasratain', 'urutan' => 2],
            ['module_id' => 3, 'nama' => 'dammatain', 'urutan' => 3],
            ['module_id' => 3, 'nama' => 'sukun', 'urutan' => 4],
            ['module_id' => 3, 'nama' => 'tasydid', 'urutan' => 5],
            // Iqra 4
            ['module_id' => 4, 'nama' => 'konsep_sambung', 'urutan' => 1],
            ['module_id' => 4, 'nama' => 'latihan_2_huruf', 'urutan' => 2],
            ['module_id' => 4, 'nama' => 'latihan_3_huruf', 'urutan' => 3],
            ['module_id' => 4, 'nama' => 'latihan_4_huruf', 'urutan' => 4],
            // Iqra 5
            ['module_id' => 5, 'nama' => 'mad_2_harakat', 'urutan' => 1],
            ['module_id' => 5, 'nama' => 'mad_4_5_harakat', 'urutan' => 2],
            ['module_id' => 5, 'nama' => 'mad_6_harakat', 'urutan' => 3],
            // Iqra 6
            ['module_id' => 6, 'nama' => 'muqattaah', 'urutan' => 1],
            ['module_id' => 6, 'nama' => 'tanda_sifir', 'urutan' => 2],
            ['module_id' => 6, 'nama' => 'tanda_waqaf', 'urutan' => 3],
        ];

        foreach ($categories as $cat) {
            DB::table('material_categories')->insert([
                'module_id' => $cat['module_id'],
                'nama' => $cat['nama'],
                'urutan' => $cat['urutan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
