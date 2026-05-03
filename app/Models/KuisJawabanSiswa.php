<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisJawabanSiswa extends Model
{
    protected $table = 'kuis_jawaban_siswa';

    protected $fillable = [
        'kuis_id',
        'user_id',
        'kuis_pertanyaan_id',
        'kuis_opsi_jawaban_id',
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kuisPertanyaan()
    {
        return $this->belongsTo(KuisPertanyaan::class, 'kuis_pertanyaan_id');
    }

    public function opsiJawaban()
    {
        return $this->belongsTo(KuisOpsiJawaban::class, 'kuis_opsi_jawaban_id');
    }
}
