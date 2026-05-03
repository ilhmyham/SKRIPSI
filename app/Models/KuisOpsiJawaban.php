<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisOpsiJawaban extends Model
{
    protected $table = 'kuis_opsi_jawaban';

    protected $fillable = [
        'kuis_pertanyaan_id',
        'teks_opsi',
        'gambar_opsi',
        'is_correct',
    ];

    public function kuisPertanyaan()
    {
        return $this->belongsTo(KuisPertanyaan::class, 'kuis_pertanyaan_id');
    }
}
