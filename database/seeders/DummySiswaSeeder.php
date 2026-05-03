<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Materi;
use App\Models\ProgressBelajar;
use App\Models\Kuis;
use App\Models\KuisPertanyaan;
use App\Models\KuisJawabanSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummySiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswaRole = DB::table('roles')->where('nama_role', 'siswa')->first();
        if (!$siswaRole) {
            $this->command->error("Role 'siswa' tidak ditemukan. Pastikan RoleSeeder sudah dijalankan.");
            return;
        }

        $password = Hash::make('password'); 
        
        $this->command->info('Membuat 13 Akun Siswa dengan Nama Islami Modern...');

        $namaSiswa = [
            'Muhammad Arkanza', 'Aisyah Az-Zahra', 'dzaki Al-Farabi', 'Khaira Wilda', 'Zaidan Al-Khair',
            'Naira Shaqueena', 'Fathan Al-ghifari', 'Hafizah Syahla', 'Rayyan Al-fatih', 'Luthfi Hamizan',
            'Najwa Shihabuddin', 'Arjuna Al-rasyid', 'Humaira Yasmin'
        ];

        $users = [];

        foreach ($namaSiswa as $index => $nama) {
            $parts = explode(' ', strtolower(str_replace(['-', "'"], '', $nama)));
            $namaDepan = $parts[0];
            
            $singkatan = '';
            for ($i = 1; $i < count($parts); $i++) {
                $singkatan .= substr($parts[$i], 0, 1); 
            }
            
            $nomorAcak = ($index + 1) . rand(10, 99);
            $email = $namaDepan . ($singkatan ? '.' . $singkatan : '') . '.' . $nomorAcak . '@gmail.com';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'role_id' => $siswaRole->id,
                    'name' => $nama,
                    'password' => $password,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            
            $users[] = $user;
            $this->command->info("✓ Dibuat: {$nama} | Email: {$email}");
        }

        $this->command->info('Memproses Progress Belajar & Riwayat Kuis...');

        $materials = Materi::all();
        $quizzes = Kuis::with('kuisPertanyaan.opsiJawaban')->get();

        foreach ($users as $user) {
            $totalMateri = $materials->count();
            if ($totalMateri > 0) {
                $materiDiselesaikan = rand(ceil($totalMateri * 0.4), ceil($totalMateri * 0.9));
                $randomMaterials = $materials->random($materiDiselesaikan);

                foreach ($randomMaterials as $materi) {
                    $randomDate = Carbon::now()->subDays(rand(1, 45));

                    ProgressBelajar::updateOrCreate(
                        ['user_id' => $user->id, 'materi_id' => $materi->id],
                        [
                            'status' => 'selesai',
                            'nilai_progress' => 100.0,
                            'created_at' => $randomDate,
                            'updated_at' => $randomDate,
                        ]
                    );
                }
            }

            if ($quizzes->count() > 0) {
                $ujianDiikuti = rand(3, $quizzes->count());
                $randomQuizzes = $quizzes->random($ujianDiikuti);

                foreach ($randomQuizzes as $quiz) {
                    $tanggalKuis = Carbon::now()->subDays(rand(1, 20));

                    foreach ($quiz->kuisPertanyaan as $pertanyaan) {
                        $opsi = $pertanyaan->opsiJawaban;
                        if ($opsi->count() > 0) {
                            $opsiBenar = $opsi->where('is_correct', true)->first();
                            $opsiSalah = $opsi->where('is_correct', false);

                            $pilihBenar = rand(1, 100) <= 85; 
                            
                            $jawabanTerpilih = ($pilihBenar && $opsiBenar) 
                                ? $opsiBenar->id 
                                : ($opsiSalah->count() > 0 ? $opsiSalah->random()->id : $opsi->first()->id);

                            KuisJawabanSiswa::updateOrCreate(
                                [
                                    'user_id' => $user->id,
                                    'kuis_pertanyaan_id' => $pertanyaan->id,
                                ],
                                [
                                    'kuis_id' => $quiz->id,
                                    'kuis_opsi_jawaban_id' => $jawabanTerpilih,
                                    'created_at' => $tanggalKuis,
                                    'updated_at' => $tanggalKuis,
                                ]
                            );
                        }
                    }
                }
            }
        }

        $this->command->info('✅ SELESAI! Data 13 Siswa Islami Modern berhasil di-generate.');
    }
}