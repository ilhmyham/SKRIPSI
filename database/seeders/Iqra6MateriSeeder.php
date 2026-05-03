<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class Iqra6MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra6 = ModulIqra::where('nama_modul', 'Iqra 6')->first();
        
        if (!$iqra6) {
            $this->command->error('Modul Iqra 6 tidak ditemukan!');
            return;
        }

        $muqattaah = [
            ['judul' => 'Muqatta\'ah: Alif Lam Mim', 'huruf' => 'الۤمۤ', 'deskripsi' => 'Alif (pendek), Lam (panjang 6 harakat), Mim (panjang 6 harakat). (QS. Al-Baqarah: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Ya Sin', 'huruf' => 'يٰسۤ', 'deskripsi' => 'Ya (2 harakat), Sin (panjang 6 harakat). (QS. Yasin: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Ta Ha', 'huruf' => 'طٰهٰ', 'deskripsi' => 'Ta (2 harakat), Ha (2 harakat). Tidak ada tanda layar. (QS. Taha: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Qaf', 'huruf' => 'قۤ', 'deskripsi' => 'Qaf dibaca panjang 6 harakat. (QS. Qaf: 1)', 'kategori' => 'muqattaah'],
            ['judul' => 'Muqatta\'ah: Alif Lam Ra', 'huruf' => 'الۤر', 'deskripsi' => 'Alif (pendek), Lam (6 harakat), Ra (2 harakat). (QS. Yunus: 1)', 'kategori' => 'muqattaah'],
        ];

        $tandaSifir = [
            ['judul' => 'Tanda Sifir: Ana', 'huruf' => 'اَنَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang (diam).', 'kategori' => 'tanda_sifir'],
            ['judul' => 'Tanda Sifir: Ar-Rasula', 'huruf' => 'الرَّسُوْلَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang saat wasal. (QS. Al-Ahzab: 66)', 'kategori' => 'tanda_sifir'],
            ['judul' => 'Tanda Sifir: Az-Zununa', 'huruf' => 'الظُّنُوْنَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang saat wasal. (QS. Al-Ahzab: 10)', 'kategori' => 'tanda_sifir'],
            ['judul' => 'Tanda Sifir: As-Sabila', 'huruf' => 'السَّبِيْلَا۠', 'deskripsi' => 'Tanda sifir pada Alif terakhir. Alif tidak dibaca panjang saat wasal. (QS. Al-Ahzab: 67)', 'kategori' => 'tanda_sifir'],            
        ];

        $tandaWaqaf = [
            ['judul' => 'Waqaf Lazim (Mim)', 'huruf' => 'فَلَا يَحْزُنْكَ قَوْلُهُمْ ۘ', 'deskripsi' => 'Tanda Mim kecil (Waqaf Lazim). Harus berhenti. (QS. Yasin: 76)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf Jaiz (Jim)', 'huruf' => 'ذٰلِكَ عِيسَى ابْنُ مَرْيَمَ ۚ', 'deskripsi' => 'Tanda Jim kecil (Waqaf Jaiz). Boleh berhenti, boleh lanjut. (QS. Maryam: 34)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf La (Lam Alif)', 'huruf' => 'فِي قُلُوبِهِم مَّرَضٌ ۙ', 'deskripsi' => 'Tanda Lam Alif (Waqaf La). Tidak boleh berhenti / Harus lanjut. (QS. Al-Baqarah: 10)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf Aula (Qaf Lam)', 'huruf' => 'وَاعْفُ عَنَّا ۗ', 'deskripsi' => 'Tanda Qaf Lam (Al-Waqfu Aula). Diutamakan berhenti. (QS. Al-Baqarah: 286)', 'kategori' => 'tanda_waqaf'],
            ['judul' => 'Waqaf Mu\'anaqah (Titik Tiga)', 'huruf' => 'لَا رَيْبَ ۛ فِيهِ ۛ', 'deskripsi' => 'Tanda Titik Tiga. Berhenti di salah satu tanda saja, tidak boleh keduanya. (QS. Al-Baqarah: 2)', 'kategori' => 'tanda_waqaf'],
        ];

        $this->command->info('Mulai Seeding Iqra 6 (Urutan per Kategori)...');

        $insertData = function($dataList, $folderName) use ($iqra6) {
            
            $urutanKategori = 1; 
            $nomorGambar = 1;    

            foreach ($dataList as $item) {
                $filePath = 'materi/iqra6/' . $folderName . '/' . $nomorGambar . '.png';

                Materi::create([
                    'modul_iqra_id' => $iqra6->id,
                    'user_id' => 1,
                    'judul_materi' => $item['judul'],
                    'huruf_hijaiyah' => $item['huruf'],
                    'kategori_materi' => $item['kategori'],
                    'deskripsi' => $item['deskripsi'],
                    'path_file' => $filePath, 
                    'urutan' => $urutanKategori++, 
                ]);

                $nomorGambar++;
            }
            $this->command->info("✓ Selesai kategori: " . $folderName);
        };

        $insertData($muqattaah, 'muqattaah'); 
        $insertData($tandaSifir, 'sifir');
        $insertData($tandaWaqaf, 'waqaf');
        
        $this->command->info("✅ SELESAI. Semua materi Iqra 6 berhasil dibuat dengan urutan per kategori.");
    }
}