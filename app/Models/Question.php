<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'text_pertanyaan',
        'gambar_pertanyaan',
        'tipe',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function correctAnswer(): HasOne
    {
        return $this->hasOne(QuestionOption::class)
                    ->where('is_correct', true);
    }

    public function hasImage(): bool
    {
        return !empty($this->gambar_pertanyaan);
    }
}
