<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $table = 'kuis_jawaban_siswa';

    protected $fillable = [
        'kuis_id',
        'user_id',
        'kuis_pertanyaan_id',
        'kuis_opsi_jawaban_id',
    ];

    public function kuis(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'kuis_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kuisPertanyaan(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'kuis_pertanyaan_id');
    }

    public function opsiJawaban(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'kuis_opsi_jawaban_id');
    }
}
