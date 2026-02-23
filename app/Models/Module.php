<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'nama_modul',
        'deskripsi',
    ];

    /**
     * Get all categories in this module
     */
    public function categories(): HasMany
    {
        return $this->hasMany(MaterialCategory::class);
    }

    /**
     * Get all materials in this module
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get all quizzes for this module
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
