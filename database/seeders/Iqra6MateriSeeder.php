<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;

class Iqra6MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder Iqra 6: Simbol Khusus & Tanda Baca (Waqaf).
     * Fitur: Otomatis mapping gambar sesuai folder kategori.
     */
    public function run(): void
    {
        $iqra6 = Module::where('nama_modul', 'Iqra 6')->first();
        
        $getCategoryId = function($kategori) use ($iqra6) {
            $slug = Str::slug($kategori, '_');
            return \Illuminate\Support\Facades\DB::table('material_categories')
                ->where('module_id', $iqra6->id)
                ->where('nama', $slug)
                ->value('id');
        };
        
        if (!$iqra6) {
            $this->command->error('Modul Iqra 6 tidak ditemukan!');
            return;
        }

        // ==========================================================
        // DATA MATERI
        // ==========================================================
        
        // 1. MUQATTA'AH (Folder: muqattaah)
        $muqattaah = [
            ['judul' => 'Muqatta\'ah: Alif Lam Mim', 'huruf' => 'الۤمۤ', 'deskripsi' => 'Alif (pendek), Lam (panjang 6 harakat), Mim (panjang 6 harakat). (QS. Al-Baqarah: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Ya Sin', 'huruf' => 'يٰسۤ', 'deskripsi' => 'Ya (2 harakat), Sin (panjang 6 harakat). (QS. Yasin: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Ta Ha', 'huruf' => 'طٰهٰ', 'deskripsi' => 'Ta (2 harakat), Ha (2 harakat). Tidak ada tanda layar. (QS. Taha: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Qaf', 'huruf' => 'قۤ', 'deskripsi' => 'Qaf dibaca panjang 6 harakat. (QS. Qaf: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Alif Lam Ra', 'huruf' => 'الۤر', 'deskripsi' => 'Alif (pendek), Lam (6 harakat), Ra (2 harakat). (QS. Yunus: 1)', 'kategori' => 'muqattaah'],
        ];

        // 2. TANDA SIFIR (Folder: sifir)
        $tandaSifir = [
            ['judul' => 'Tanda Sifir: Ana', 'huruf' => 'اَنَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang (diam).', 'kategori' => 'tanda_sifir'],
            ['judul' => 'Tanda Sifir: Ar-Rasula', 'huruf' => 'الرَّسُوْلَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang saat wasal. (QS. Al-Ahzab: 66)', 'kategori' => 'tanda_sifir'],
            ['judul' => 'Tanda Sifir: Az-Zununa', 'huruf' => 'الظُّنُوْنَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang saat wasal. (QS. Al-Ahzab: 10)', 'kategori' => 'tanda_sifir'],
            ['judul' => 'Tanda Sifir: As-Sabila', 'huruf' => 'السَّبِيْلَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang saat wasal. (QS. Al-Ahzab: 67)', 'kategori' => 'tanda_sifir'],            
        ];

        // 3. TANDA WAQAF (Folder: waqaf)
        $tandaWaqaf = [
            ['judul' => 'Waqaf Lazim (Mim)', 'huruf' => 'فَلَا يَحْزُنْكَ قَوْلُهُمْ ۘ', 'deskripsi' => 'Tanda Mim kecil (Waqaf Lazim). Harus berhenti. (QS. Yasin: 76)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf Jaiz (Jim)', 'huruf' => 'ذٰلِكَ عِيسَى ابْنُ مَرْيَمَ ۚ', 'deskripsi' => 'Tanda Jim kecil (Waqaf Jaiz). Boleh berhenti, boleh lanjut. (QS. Maryam: 34)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf La (Lam Alif)', 'huruf' => 'فِي قُلُوبِهِم مَّرَضٌ ۙ', 'deskripsi' => 'Tanda Lam Alif (Waqaf La). Tidak boleh berhenti / Harus lanjut. (QS. Al-Baqarah: 10)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf Aula (Qaf Lam)', 'huruf' => 'وَاعْفُ عَنَّا ۗ', 'deskripsi' => 'Tanda Qaf Lam (Al-Waqfu Aula). Diutamakan berhenti. (QS. Al-Baqarah: 286)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf Mu\'anaqah (Titik Tiga)', 'huruf' => 'لَا رَيْبَ ۛ فِيهِ ۛ', 'deskripsi' => 'Tanda Titik Tiga. Berhenti di salah satu tanda saja, tidak boleh keduanya. (QS. Al-Baqarah: 2)', 'kategori' => 'tanda_waqaf'],
        ];

        $this->command->info('Mulai Seeding Iqra 6...');
        $urutan = 1;

        // Fungsi Helper untuk Insert Data dengan Folder Spesifik
        // Parameter $folderName ditambahkan untuk menentukan sub-folder
        $insertData = function($dataList, $folderName) use ($iqra6, $getCategoryId, &$urutan) {
            
            // Counter reset ke 1 setiap kali ganti kategori/folder
            // Asumsi: Di dalam folder 'muqattaah', file bernama 1.png, 2.png, dst.
            // Asumsi: Di dalam folder 'sifir', file juga mulai dari 1.png, 2.png, dst.
            $nomorGambar = 1;

            foreach ($dataList as $item) {
                
                // Membuat path: assets/images/iqra6/namafolder/1.png
                $fileName = $nomorGambar . '.png';
                $filePath = 'materi/iqra6/' . $folderName . '/' . $fileName;

                Material::create([
                    'module_id' => $iqra6->id,
                    'user_id' => 1,
                    'judul_materi' => $item['judul'],
                    'huruf_hijaiyah' => $item['huruf'],
                    'category_id' => $getCategoryId($item['kategori']),
                    'deskripsi' => $item['deskripsi'],
                    'file_path' => $filePath, 
                    'urutan' => $urutan++,
                ]);

                $nomorGambar++;
            }
        };

        // Eksekusi per kategori dengan nama folder yang sesuai gambar Anda
        $insertData($muqattaah, 'muqattaah'); 
        $this->command->info("✓ Berhasil input Muqatta'ah (Folder: muqattaah).");

        $insertData($tandaSifir, 'sifir');
        $this->command->info("✓ Berhasil input Tanda Sifir (Folder: sifir).");

        $insertData($tandaWaqaf, 'waqaf');
        $this->command->info("✓ Berhasil input Tanda Waqaf (Folder: waqaf).");
        
        $this->command->info("✅ SELESAI. Total 15 Materi Iqra 6 berhasil dibuat.");
    }
}
