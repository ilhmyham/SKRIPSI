<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressBelajar extends Model
{
    protected $table = 'progress_belajar';

    protected $fillable = [
        'materi_id',
        'user_id',
        'status',
        'nilai_progress',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
