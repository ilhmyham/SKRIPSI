<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialCategory extends Model
{
    protected $table = 'kategori_materi';

    protected $fillable = [
        'modul_iqra_id',
        'nama',
        'urutan',
    ];

    public function modulIqra(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'modul_iqra_id');
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Material::class, 'kategori_materi_id')->orderBy('urutan');
    }
}
