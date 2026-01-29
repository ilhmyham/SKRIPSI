<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';
    protected $primaryKey = 'pengumpulan_id';
    
    protected $fillable = [
        'users_user_id',
        'tugas_id',
        'file_jawaban',
        'nilai',
        'tanggal_kumpul',
    ];

    protected $casts = [
        'tanggal_kumpul' => 'datetime',
        'nilai' => 'float',
    ];

    /**
     * Get the student
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }

    /**
     * Get the assignment
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id', 'tugas_id');
    }
}
