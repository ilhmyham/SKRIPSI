<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    protected $table = 'kuis_opsi_jawaban';

    protected $fillable = [
        'kuis_pertanyaan_id',
        'teks_opsi',
        'gambar_opsi',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function kuisPertanyaan(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'kuis_pertanyaan_id');
    }

    public function hasImage(): bool
    {
        return !empty($this->gambar_opsi);
    }
}
