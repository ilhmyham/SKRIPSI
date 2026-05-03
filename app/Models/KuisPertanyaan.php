<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisPertanyaan extends Model
{
    protected $table = 'kuis_pertanyaan';

    protected $fillable = [
        'kuis_id',
        'teks_pertanyaan',
        'gambar_pertanyaan',
        'tipe',
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function opsiJawaban()
    {
        return $this->hasMany(KuisOpsiJawaban::class, 'kuis_pertanyaan_id');
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(KuisJawabanSiswa::class, 'kuis_pertanyaan_id');
    }
}
