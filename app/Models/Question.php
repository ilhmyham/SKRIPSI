<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    protected $table = 'kuis_pertanyaan';

    protected $fillable = [
        'kuis_id',
        'teks_pertanyaan',
        'gambar_pertanyaan',
        'tipe',
    ];

    public function kuis(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'kuis_id');
    }

    public function opsiJawaban(): HasMany
    {
        return $this->hasMany(QuestionOption::class, 'kuis_pertanyaan_id');
    }

    public function jawabanBenar(): HasOne
    {
        return $this->hasOne(QuestionOption::class, 'kuis_pertanyaan_id')
                    ->where('is_correct', true);
    }

    public function hasImage(): bool
    {
        return !empty($this->gambar_pertanyaan);
    }
}
