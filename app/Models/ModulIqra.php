<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulIqra extends Model
{
    protected $table = 'modul_iqra';

    protected $fillable = [
        'nama_modul',
        'deskripsi',
    ];

    public function materi()
    {
        return $this->hasMany(Materi::class, 'modul_iqra_id');
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'modul_iqra_id');
    }
}
