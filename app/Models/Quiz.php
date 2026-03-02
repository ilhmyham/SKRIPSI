<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $table = 'kuis';

    protected $fillable = [
        'modul_iqra_id',
        'user_id',
        'judul_kuis',
        'deskripsi',
    ];

    public function modulIqra(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'modul_iqra_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kuisPertanyaan(): HasMany
    {
        return $this->hasMany(Question::class, 'kuis_id');
    }

    public function jawabanSiswa(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'kuis_id');
    }
}
