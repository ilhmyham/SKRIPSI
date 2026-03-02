<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningProgress extends Model
{
    protected $table = 'progress_belajar';
    
    protected $fillable = [
        'materi_id',
        'user_id',
        'status',
        'nilai_progress',
    ];

    protected $casts = [
        'nilai_progress' => 'float',
    ];

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'materi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
