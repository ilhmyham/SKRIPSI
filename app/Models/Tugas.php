<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'tugas_id';
    
    protected $fillable = [
        'users_user_id',
        'judul_tugas',
        'deskripsi_tugas',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    /**
     * Get the teacher who created the assignment
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }

    /**
     * Get all submissions
     */
    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id', 'tugas_id');
    }
}
