<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'user_id',
        'tugas_id',
        'file_jawaban',
        'nilai',
        'catatan_guru',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'tugas_id');
    }
}
