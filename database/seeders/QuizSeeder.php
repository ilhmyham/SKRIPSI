<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Admin
        $adminId = 1;
        $adminRole = DB::table('roles')->where('nama_role', 'admin')->first();
        if ($adminRole) {
            $admin = User::where('role_id', $adminRole->id)->first();
            $adminId = $admin ? $admin->id : 1;
        }

        // 2. Data 5 Soal Spesifik tiap Modul
        $dataKuis = [
            'Iqra 1' => [
                'deskripsi' => 'Evaluasi pengenalan huruf tunggal hijaiyah Alif - Ya.',
                'soal' => [
                    ['tanya' => 'Manakah isyarat tangan yang benar untuk huruf Alif?', 'opsi' => ['Jari telunjuk lurus ke atas', 'Ibu jari menunjuk ke kiri', 'Empat jari menekuk siku-siku'], 'benar' => 0],
                    ['tanya' => 'Isyarat tangan membentuk lingkaran dengan ujung telunjuk dan ibu jari adalah huruf...', 'opsi' => ['Fa', 'Qaf', 'Ha'], 'benar' => 0],
                    ['tanya' => 'Isyarat untuk huruf Ta diwakili oleh dua jari (telunjuk & tengah) yang artinya...', 'opsi' => ['Satu titik', 'Dua titik', 'Tiga titik'], 'benar' => 1],
                    ['tanya' => 'Huruf Jim diwakili isyarat tangan dengan empat jari menekuk siku-siku dan posisi ibu jari di...', 'opsi' => ['Bawah jari lain', 'Samping telunjuk', 'Menunjuk ke atas'], 'benar' => 0],
                    ['tanya' => 'Manakah isyarat tangan yang benar untuk huruf Ya?', 'opsi' => ['Ibu jari dan kelingking menunjuk ke atas', 'Tangan mengepal', 'Jari telunjuk melengkung'], 'benar' => 0],
                ]
            ],
            'Iqra 2' => [
                'deskripsi' => 'Evaluasi harakat dasar Fathah (A), Kasrah (I), dan Dammah (U).',
                'soal' => [
                    ['tanya' => 'Gerakan garis lurus dari kanan ke kiri di atas huruf adalah tanda...', 'opsi' => ['Kasrah', 'Dammah', 'Fathah'], 'benar' => 2],
                    ['tanya' => 'Jika huruf Ba diberi harakat Kasrah (garis di bawah), maka dibaca...', 'opsi' => ['Ba', 'Bi', 'Bu'], 'benar' => 1],
                    ['tanya' => 'Harakat yang berbentuk seperti angka sembilan kecil di atas huruf disebut...', 'opsi' => ['Fathah', 'Kasrah', 'Dammah'], 'benar' => 2],
                    ['tanya' => 'Bunyi vokal "U" dihasilkan oleh harakat...', 'opsi' => ['Fathah', 'Kasrah', 'Dammah'], 'benar' => 2],
                    ['tanya' => 'Jika huruf Sin diberi harakat Fathah, maka bunyinya menjadi...', 'opsi' => ['Sa', 'Si', 'Su'], 'benar' => 0],
                ]
            ],
            'Iqra 3' => [
                'deskripsi' => 'Evaluasi Tanwin, Sukun, dan Tasydid.',
                'soal' => [
                    ['tanya' => 'Tanda baca yang berfungsi menekan atau mendobelkan bacaan disebut...', 'opsi' => ['Sukun', 'Tasydid', 'Tanwin'], 'benar' => 1],
                    ['tanya' => 'Bunyi "An, In, Un" secara umum disebut dengan tanda baca...', 'opsi' => ['Tanwin', 'Mad', 'Waqaf'], 'benar' => 0],
                    ['tanya' => 'Tanda Sukun digambarkan dengan simbol...', 'opsi' => ['Bulatan kecil di atas', 'Garis miring', 'Kepala huruf Sin'], 'benar' => 0],
                    ['tanya' => 'Harakat Fathatain (An) pada huruf Ba ditulis dengan simbol...', 'opsi' => ['Dua garis di atas', 'Dua garis di bawah', 'Dua dammah'], 'benar' => 0],
                    ['tanya' => 'Isyarat tangan yang ditekan kuat menunjukkan keberadaan tanda...', 'opsi' => ['Fathah', 'Tasydid', 'Sukun'], 'benar' => 1],
                ]
            ],
            'Iqra 4' => [
                'deskripsi' => 'Evaluasi konsep huruf sambung dalam kata.',
                'soal' => [
                    ['tanya' => 'Manakah huruf yang tidak bisa menyambung ke kiri (huruf sesudahnya)?', 'opsi' => ['Ba', 'Dal', 'Jim'], 'benar' => 1],
                    ['tanya' => 'Huruf Ha besar (هـ) saat berada di tengah kata berubah bentuk menjadi seperti...', 'opsi' => ['Pita/Simpul', 'Garis lurus', 'Tetap'], 'benar' => 0],
                    ['tanya' => 'Huruf Ain (ع) saat disambung di tengah atau akhir kata, bentuk kepalanya menjadi...', 'opsi' => ['Terbuka', 'Tertutup/Pejal', 'Hilang'], 'benar' => 1],
                    ['tanya' => 'Huruf Ba, Ta, dan Tsa memiliki bentuk sambung yang sama, perbedaannya terletak pada...', 'opsi' => ['Panjangnya', 'Jumlah dan letak titik', 'Tinggi hurufnya'], 'benar' => 1],
                    ['tanya' => 'Huruf Alif jika berada di tengah kata hanya bisa menyambung dengan huruf di...', 'opsi' => ['Sebelah kanannya', 'Sebelah kirinya', 'Kedua sisinya'], 'benar' => 0],
                ]
            ],
            'Iqra 5' => [
                'deskripsi' => 'Evaluasi bacaan panjang (Mad).',
                'soal' => [
                    ['tanya' => 'Berapa harakat Mad Thabi\'i (Mad Asli) harus dibaca?', 'opsi' => ['1 Harakat', '2 Harakat', '6 Harakat'], 'benar' => 1],
                    ['tanya' => 'Tanda layar (bendera) di atas huruf menandakan bacaan Mad sepanjang...', 'opsi' => ['2 Harakat', '4-5 Harakat', 'Tidak panjang'], 'benar' => 1],
                    ['tanya' => 'Mad Thabi\'i terjadi jika ada Alif setelah huruf berharakat...', 'opsi' => ['Fathah', 'Kasrah', 'Dammah'], 'benar' => 0],
                    ['tanya' => 'Mad Thabi\'i terjadi jika ada Wau sukun setelah huruf berharakat...', 'opsi' => ['Fathah', 'Kasrah', 'Dammah'], 'benar' => 2],
                    ['tanya' => 'Berapa harakat panjang bacaan Mad Lazim (seperti dalam Dhallin)?', 'opsi' => ['2 Harakat', '4 Harakat', '6 Harakat'], 'benar' => 2],
                ]
            ],
            'Iqra 6' => [
                'deskripsi' => 'Evaluasi tanda Waqaf dan simbol khusus Al-Qur\'an.',
                'soal' => [
                    ['tanya' => 'Tanda Waqaf Lazim (Mim kecil) artinya kita...', 'opsi' => ['Boleh lanjut', 'Harus berhenti', 'Dilarang berhenti'], 'benar' => 1],
                    ['tanya' => 'Tanda titik tiga (Mu\'anaqah) berarti kita harus berhenti di...', 'opsi' => ['Kedua tanda', 'Salah satu tanda saja', 'Tidak berhenti'], 'benar' => 1],
                    ['tanya' => 'Tanda Waqaf "La" (Lam Alif) artinya...', 'opsi' => ['Dilarang berhenti', 'Utamakan berhenti', 'Boleh berhenti'], 'benar' => 0],
                    ['tanya' => 'Simbol lingkaran kecil (Sifir) di atas huruf Alif berarti Alif tersebut...', 'opsi' => ['Dibaca panjang', 'Tidak dianggap/tidak dibaca', 'Ditekan'], 'benar' => 1],
                    ['tanya' => 'Huruf-huruf di awal surat seperti "Alif Lam Mim" disebut sebagai huruf...', 'opsi' => ['Waqaf', 'Muqatta\'ah', 'Sifir'], 'benar' => 1],
                ]
            ],
        ];

        $this->command->info('>>> Memulai Proses Seeding Kuis Lengkap <<<');

        foreach ($dataKuis as $namaModul => $konten) {
            // Cari modul berdasarkan nama
            $module = Module::where('nama_modul', $namaModul)->first();

            if (!$module) {
                $this->command->error("Gagal: Modul '{$namaModul}' tidak ditemukan di database!");
                continue;
            }

            // Buat Kuis menggunakan modul_iqra_id sesuai Model kamu
            $quiz = Quiz::create([
                'modul_iqra_id' => $module->id,
                'user_id' => $adminId,
                'judul_kuis' => 'Kuis Evaluasi ' . $namaModul,
                'deskripsi' => $konten['deskripsi'],
            ]);

            $this->command->info("✓ Kuis Dibuat: {$quiz->judul_kuis}");

            foreach ($konten['soal'] as $index => $s) {
                // Buat Pertanyaan menggunakan kuis_id sesuai Model kamu
                $question = Question::create([
                    'kuis_id' => $quiz->id, 
                    'teks_pertanyaan' => $s['tanya'],
                    'tipe' => 'pilihan_ganda',
                ]);

                foreach ($s['opsi'] as $oIndex => $teksOpsi) {
                    // Buat Opsi menggunakan kuis_pertanyaan_id sesuai Model kamu
                    QuestionOption::create([
                        'kuis_pertanyaan_id' => $question->id,
                        'teks_opsi' => $teksOpsi,
                        'is_correct' => ($oIndex === $s['benar']),
                    ]);
                }
                
                $this->command->info("  -- Soal " . ($index + 1) . " berhasil ditambahkan.");
            }
        }

        $this->command->info('✅ SELESAI! Total 30 soal untuk 6 kuis berhasil dimasukkan.');
    }
}