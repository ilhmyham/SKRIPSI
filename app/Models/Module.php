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

    /**
     * Get all categories in this module
     */
    public function kategoriMateri(): HasMany
    {
        return $this->hasMany(MaterialCategory::class, 'modul_iqra_id');
    }

    /**
     * Get all materials in this module
     */
    public function materi(): HasMany
    {
        return $this->hasMany(Material::class, 'modul_iqra_id');
    }

    /**
     * Get all quizzes for this module
     */
    public function kuis(): HasMany
    {
        return $this->hasMany(Quiz::class, 'modul_iqra_id');
    }
}
