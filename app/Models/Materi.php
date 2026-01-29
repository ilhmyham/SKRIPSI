<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Materi extends Model
{
    protected $table = 'materi';
    protected $primaryKey = 'materi_id';
    
    protected $fillable = [
        'modul_iqra_modul_id',
        'users_user_id',
        'judul_materi',
        'deskripsi',
        'file_video',
        'huruf_hijaiyah',
        'file_path',
    ];

    /**
     * Get the module that owns the material
     */
    public function modulIqra()
    {
        return $this->belongsTo(ModulIqra::class, 'modul_iqra_modul_id', 'modul_id');
    }

    /**
     * Get the teacher who created the material
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }

    /**
     * Get progress records for this material
     */
    public function progress()
    {
        return $this->hasMany(ProgressBelajar::class, 'materi_id', 'materi_id');
    }

    /**
     * Get quizzes for this material
     */
    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'materi_id', 'materi_id');
    }

    /**
     * Get Google Drive embed URL from video ID
     */
    protected function videoEmbedUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->file_video 
                ? "https://drive.google.com/file/d/{$this->file_video}/preview"
                : null,
        );
    }
}
