<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';
    protected $primaryKey = 'jawaban_id';
    
    protected $fillable = [
        'kuis_id',
        'users_user_id',
        'pertanyaan_id',
        'jawaban_pilihan',
        'nilai',
        'waktu_dikerjakan',
    ];

    protected $casts = [
        'waktu_dikerjakan' => 'datetime',
        'nilai' => 'float',
    ];

    /**
     * Get the quiz
     */
    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id', 'kuis_id');
    }

    /**
     * Get the student
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }

    /**
     * Get the question
     */
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'pertanyaan_id');
    }
}
