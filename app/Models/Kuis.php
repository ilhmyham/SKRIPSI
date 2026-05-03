<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = 'kuis';

    protected $fillable = [
        'modul_iqra_id',
        'user_id',
        'judul_kuis',
        'deskripsi',
    ];

    public function modulIqra()
    {
        return $this->belongsTo(ModulIqra::class, 'modul_iqra_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kuisPertanyaan()
    {
        return $this->hasMany(KuisPertanyaan::class, 'kuis_id');
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(KuisJawabanSiswa::class, 'kuis_id');
    }
}
