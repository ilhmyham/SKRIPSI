<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Iqra4StrategisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder Iqra 4: Konsep Sambung & Latihan Kata.
     * Update: Reset urutan per kategori (konsep, 2huruf, 3huruf, 4huruf).
     */
    public function run(): void
    {
        $iqra4 = Module::where('nama_modul', 'Iqra 4')->first();
        
        if (!$iqra4) {
            $this->command->error('Modul Iqra 4 tidak ditemukan!');
            return;
        }

        $getCategoryId = function($kategori) use ($iqra4) {
            $slug = Str::slug($kategori, '_');
            return DB::table('material_categories')
                ->where('module_id', $iqra4->id)
                ->where('nama', $slug)
                ->value('id');
        };

        // ==========================================================
        // DATA MATERI
        // ==========================================================
        
        $konsep = [
            ['judul' => 'Huruf Ba Sambung', 'huruf' => 'ب  |  بـ ـبـ ـب', 'file' => 'ba.png', 'desc' => 'Huruf Ba kehilangan lengkungan bawahnya saat disambung di awal dan tengah.'],
            ['judul' => 'Huruf Dal Sambung', 'huruf' => 'د  |  ـد', 'file' => 'dal.png', 'desc' => 'Huruf Dal adalah huruf pemutus. Tidak bisa menyambung ke huruf setelahnya (kiri).'],
            ['judul' => 'Huruf Ain Sambung', 'huruf' => 'ع  |  عـ ـعـ ـع', 'file' => 'ain.png', 'desc' => 'Kepala Ain terbuka saat di awal, namun menjadi tertutup (pejal) saat di tengah/akhir.'],
            ['judul' => 'Huruf Mim Sambung', 'huruf' => 'م  |  مـ ـmـ ـm', 'file' => 'mim.png', 'desc' => 'Perhatikan posisi kepala Mim. Saat disambung, posisinya bisa berubah sedikit ke bawah garis.'],
            ['judul' => 'Huruf Ha Sambung', 'huruf' => 'ه  |  هـ ـهـ ـه', 'file' => 'ha_besar.png', 'desc' => 'Huruf Ha berubah drastis seperti pita kupu-kupu saat di tengah kalimat.'],
        ];

        $latihan2 = [
            ['judul' => 'Latihan: An', 'huruf' => 'عَنْ', 'file' => 'an.png', 'desc' => 'Menyambung Ain (Awal) dan Nun (Akhir).'],
            ['judul' => 'Latihan: Lan', 'huruf' => 'لَنْ', 'file' => 'lan.png', 'desc' => 'Menyambung Lam (Awal) dan Nun (Akhir).'],
            ['judul' => 'Latihan: Min', 'huruf' => 'مِنْ', 'file' => 'min.png', 'desc' => 'Menyambung Mim (Awal) dan Nun (Akhir).'],
            ['judul' => 'Latihan: Fi', 'huruf' => 'فِي', 'file' => 'fi.png', 'desc' => 'Menyambung Fa (Awal) dan Ya (Akhir).'],
            ['judul' => 'Latihan: Qul', 'huruf' => 'قُلْ', 'file' => 'qul.png', 'desc' => 'Menyambung Qaf (Awal) dan Lam (Akhir).'],
        ];

        $latihan3 = [
            ['judul' => 'Latihan: Nahnu', 'huruf' => 'نَحْنُ', 'file' => 'nahnu.png', 'desc' => 'Menyambung Nun, Ha (Tengah), dan Nun.'],
            ['judul' => 'Latihan: Ja\'ala', 'huruf' => 'جَعَلَ', 'file' => 'jaala.png', 'desc' => 'Menyambung Jim, Ain (Tengah), dan Lam.'],
            ['judul' => 'Latihan: La\'alla', 'huruf' => 'لَعَلَّ', 'file' => 'laala.png', 'desc' => 'Menyambung Lam, Ain, dan Lam (Tasydid).'],
            ['judul' => 'Latihan: Qablu', 'huruf' => 'قَبْلُ', 'file' => 'qablu.png', 'desc' => 'Menyambung Qaf, Ba (Tengah), dan Lam.'],
            ['judul' => 'Latihan: Lahum', 'huruf' => 'لَهُمْ', 'file' => 'lahum.png', 'desc' => 'Menyambung Lam, Ha (Tengah), dan Mim.'],
        ];

        $latihan4 = [
            ['judul' => 'Latihan: Ka\'batu', 'huruf' => 'كَعْبَةُ', 'file' => 'kabatu.png', 'desc' => 'Menyambung Kaf, Ain, Ba, dan Ta Marbutah.'],
            ['judul' => 'Latihan: Masjidun', 'huruf' => 'مَسْجِدٌ', 'file' => 'masjidun.png', 'desc' => 'Menyambung Mim, Sin, Jim, dan Dal.'],
            ['judul' => 'Latihan: Yaj\'alu', 'huruf' => 'يَجْعَلُ', 'file' => 'yajalu.png', 'desc' => 'Menyambung Ya, Jim, Ain, dan Lam.'],
            ['judul' => 'Latihan: Yaftahu', 'huruf' => 'يَفْتَحُ', 'file' => 'yaftahu.png', 'desc' => 'Menyambung Ya, Fa, Ta, dan Ha.'],
            ['judul' => 'Latihan: Yajlisu', 'huruf' => 'يَجْلِسُ', 'file' => 'yajlisu.png', 'desc' => 'Menyambung Ya, Jim, Lam, dan Sin.'],
        ];

        $this->command->info('Mulai Seeding Iqra 4 (Urutan per Kategori)...');

        // Fungsi Helper untuk Insert Data per Folder dengan Reset Urutan
        $insertData = function($dataList, $folderName, $kategoriName) use ($iqra4, $getCategoryId) {
            $urutan = 1; // Reset urutan menjadi 1 untuk setiap kategori baru
            foreach ($dataList as $item) {
                $filePath = 'materi/iqra4/' . $folderName . '/' . $item['file'];

                Material::create([
                    'module_id' => $iqra4->id,
                    'user_id' => 1,
                    'judul_materi' => $item['judul'],
                    'huruf_hijaiyah' => $item['huruf'],
                    'category_id' => $getCategoryId($kategoriName),
                    'deskripsi' => isset($item['deskripsi']) ? $item['deskripsi'] : $item['desc'],
                    'file_path' => $filePath, 
                    'urutan' => $urutan++,
                ]);
            }
            $this->command->info("✓ Selesai kategori: " . $kategoriName);
        };

        // Eksekusi per kategori
        $insertData($konsep,   'konsep', 'konsep_sambung');
        $insertData($latihan2, '2huruf', 'latihan_2_huruf');
        $insertData($latihan3, '3huruf', 'latihan_3_huruf');
        $insertData($latihan4, '4huruf', 'latihan_4_huruf');

        $this->command->info("✅ SELESAI. Total 20 Materi Iqra 4 berhasil dibuat dengan urutan per kategori.");
    }
}