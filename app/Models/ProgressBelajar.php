<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressBelajar extends Model
{
    protected $table = 'progress_belajar';
    protected $primaryKey = 'progress_id';
    
    protected $fillable = [
        'materi_id',
        'users_user_id',
        'status_2',
        'progress_value',
        'tanggal_update',
    ];

    protected $casts = [
        'tanggal_update' => 'datetime',
        'progress_value' => 'float',
    ];

    /**
     * Get the material
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }

    /**
     * Get the student
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }
}
