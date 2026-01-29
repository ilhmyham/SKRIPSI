<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = 'kuis';
    protected $primaryKey = 'kuis_id';
    
    protected $fillable = [
        'modul_iqra_modul_id',
        'users_user_id',
        'judul_kuis',
        'deskripsi',
    ];

    /**
     * Get the module
     */
    public function modulIqra()
    {
        return $this->belongsTo(ModulIqra::class, 'modul_iqra_modul_id', 'modul_id');
    }

    /**
     * Get the user who created this quiz
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }

    /**
     * Get all questions
     */
    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'kuis_id', 'kuis_id');
    }

    /**
     * Get student answers
     */
    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'kuis_id', 'kuis_id');
    }
}
