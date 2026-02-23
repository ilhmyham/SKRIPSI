<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;

class Iqra4StrategisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder Iqra 4: Konsep Sambung & Latihan Kata.
     * Fitur: Path gambar otomatis sesuai folder (konsep, 2huruf, 3huruf, 4huruf).
     */
    public function run(): void
    {
        $iqra4 = Module::where('nama_modul', 'Iqra 4')->first();
        
        $getCategoryId = function($kategori) use ($iqra4) {
            $slug = Str::slug($kategori, '_');
            return \Illuminate\Support\Facades\DB::table('material_categories')
                ->where('module_id', $iqra4->id)
                ->where('nama', $slug)
                ->value('id');
        };
        
        if (!$iqra4) {
            $this->command->error('Modul Iqra 4 tidak ditemukan!');
            return;
        }

        // ==========================================================
        // BAGIAN 1: KONSEP HURUF SAMBUNG
        // Folder: assets/images/iqra4/konsep/
        // ==========================================================
        $konsep = [
            [
                'judul' => 'Huruf Ba Sambung',
                'huruf' => 'ب  |  بـ ـبـ ـب', 
                'file'  => 'ba.png',
                'deskripsi' => 'Huruf Ba kehilangan lengkungan bawahnya saat disambung di awal dan tengah.',
                'kategori' => 'konsep_sambung'
            ],
            [
                'judul' => 'Huruf Dal Sambung',
                'huruf' => 'د  |  ـد', 
                'file'  => 'dal.png',
                'deskripsi' => 'Huruf Dal adalah huruf pemutus. Tidak bisa menyambung ke huruf setelahnya (kiri).',
                'kategori' => 'konsep_sambung'
            ],
            [
                'judul' => 'Huruf Ain Sambung',
                'huruf' => 'ع  |  عـ ـعـ ـع', 
                'file'  => 'ain.png',
                'deskripsi' => 'Kepala Ain terbuka saat di awal, namun menjadi tertutup (pejal) saat di tengah/akhir.',
                'kategori' => 'konsep_sambung'
            ],
            [
                'judul' => 'Huruf Mim Sambung',
                'huruf' => 'م  |  مـ ـمـ ـم', 
                'file'  => 'mim.png',
                'deskripsi' => 'Perhatikan posisi kepala Mim. Saat disambung, posisinya bisa berubah sedikit ke bawah garis.',
                'kategori' => 'konsep_sambung'
            ],
            [
                'judul' => 'Huruf Ha Sambung',
                'huruf' => 'ه  |  هـ ـهـ ـه', 
                'file'  => 'ha_besar.png', // Sesuai nama file di screenshot
                'deskripsi' => 'Huruf Ha berubah drastis seperti pita kupu-kupu saat di tengah kalimat.',
                'kategori' => 'konsep_sambung'
            ],
        ];

        // ==========================================================
        // BAGIAN 2: LATIHAN 2 HURUF
        // Folder: assets/images/iqra4/2huruf/
        // ==========================================================
        $latihan2 = [
            ['judul' => 'Latihan: An',  'huruf' => 'عَنْ', 'file' => 'an.png',  'desc' => 'Menyambung Ain (Awal) dan Nun (Akhir).'],
            ['judul' => 'Latihan: Lan', 'huruf' => 'لَنْ', 'file' => 'lan.png', 'desc' => 'Menyambung Lam (Awal) dan Nun (Akhir).'],
            ['judul' => 'Latihan: Min', 'huruf' => 'مِنْ', 'file' => 'min.png', 'desc' => 'Menyambung Mim (Awal) dan Nun (Akhir).'],
            ['judul' => 'Latihan: Fi',  'huruf' => 'فِي', 'file' => 'fi.png',  'desc' => 'Menyambung Fa (Awal) dan Ya (Akhir).'],
            ['judul' => 'Latihan: Qul', 'huruf' => 'قُلْ', 'file' => 'qul.png', 'desc' => 'Menyambung Qaf (Awal) dan Lam (Akhir).'],
        ];

        // ==========================================================
        // BAGIAN 3: LATIHAN 3 HURUF
        // Folder: assets/images/iqra4/3huruf/
        // ==========================================================
        $latihan3 = [
            ['judul' => 'Latihan: Nahnu',   'huruf' => 'نَحْنُ',  'file' => 'nahnu.png', 'desc' => 'Menyambung Nun, Ha (Tengah), dan Nun.'],
            ['judul' => 'Latihan: Ja\'ala', 'huruf' => 'جَعَلَ',  'file' => 'jaala.png', 'desc' => 'Menyambung Jim, Ain (Tengah), dan Lam.'],
            ['judul' => 'Latihan: La\'alla', 'huruf' => 'لَعَلَّ', 'file' => 'laala.png', 'desc' => 'Menyambung Lam, Ain, dan Lam (Tasydid).'],
            ['judul' => 'Latihan: Qablu',   'huruf' => 'قَبْلُ',  'file' => 'qablu.png', 'desc' => 'Menyambung Qaf, Ba (Tengah), dan Lam.'],
            ['judul' => 'Latihan: Lahum',   'huruf' => 'لَهُمْ',  'file' => 'lahum.png', 'desc' => 'Menyambung Lam, Ha (Tengah), dan Mim.'],
        ];

        // ==========================================================
        // BAGIAN 4: LATIHAN 4 HURUF
        // Folder: assets/images/iqra4/4huruf/
        // ==========================================================
        $latihan4 = [
            ['judul' => 'Latihan: Ka\'batu', 'huruf' => 'كَعْبَةُ', 'file' => 'kabatu.png',   'desc' => 'Menyambung Kaf, Ain, Ba, dan Ta Marbutah.'],
            ['judul' => 'Latihan: Masjidun', 'huruf' => 'مَسْجِدٌ', 'file' => 'masjidun.png', 'desc' => 'Menyambung Mim, Sin, Jim, dan Dal.'],
            ['judul' => 'Latihan: Yaj\'alu', 'huruf' => 'يَجْعَلُ', 'file' => 'yajalu.png',   'desc' => 'Menyambung Ya, Jim, Ain, dan Lam.'],
            ['judul' => 'Latihan: Yaftahu',  'huruf' => 'يَفْتَحُ', 'file' => 'yaftahu.png',  'desc' => 'Menyambung Ya, Fa, Ta, dan Ha.'],
            ['judul' => 'Latihan: Yajlisu',  'huruf' => 'يَجْلِسُ', 'file' => 'yajlisu.png',  'desc' => 'Menyambung Ya, Jim, Lam, dan Sin.'],
        ];

        $this->command->info('Mulai Seeding Iqra 4...');
        $urutan = 1;

        // Fungsi Helper untuk Insert Data per Folder
        $insertData = function($dataList, $folderName, $kategori) use ($iqra4, $getCategoryId, &$urutan) {
            foreach ($dataList as $item) {
                // Path otomatis: assets/images/iqra4/[namafolder]/[namafile]
                $filePath = 'materi/iqra4/' . $folderName . '/' . $item['file'];

                Material::create([
                    'module_id' => $iqra4->id,
                    'user_id' => 1,
                    'judul_materi' => $item['judul'],
                    'huruf_hijaiyah' => $item['huruf'],
                    'category_id' => $getCategoryId($kategori),
                    'deskripsi' => isset($item['deskripsi']) ? $item['deskripsi'] : $item['desc'],
                    'file_path' => $filePath, 
                    'urutan' => $urutan++,
                ]);
            }
        };

        // Eksekusi Insert per Kategori
        $insertData($konsep,   'konsep', 'konsep_sambung');
        $this->command->info("✓ Input Konsep (Folder: konsep)");

        $insertData($latihan2, '2huruf', 'latihan_2_huruf');
        $this->command->info("✓ Input Latihan 2 Huruf (Folder: 2huruf)");

        $insertData($latihan3, '3huruf', 'latihan_3_huruf');
        $this->command->info("✓ Input Latihan 3 Huruf (Folder: 3huruf)");

        $insertData($latihan4, '4huruf', 'latihan_4_huruf');
        $this->command->info("✓ Input Latihan 4 Huruf (Folder: 4huruf)");

        $this->command->info("✅ SELESAI. Total 20 Materi Iqra 4 berhasil dibuat.");
    }
}
