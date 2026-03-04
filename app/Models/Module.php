<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $table = 'modul_iqra';

    protected $fillable = [
        'nama_modul',
        'deskripsi',
    ];

    public function kategoriMateri(): HasMany
    {
        return $this->hasMany(MaterialCategory::class, 'modul_iqra_id');
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Material::class, 'modul_iqra_id');
    }

    public function kuis(): HasMany
    {
        return $this->hasMany(Quiz::class, 'modul_iqra_id');
    }
}
