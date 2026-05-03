<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'modul_iqra_id',
        'user_id',
        'kategori_materi',
        'judul_materi',
        'deskripsi',
        'file_video',
        'huruf_hijaiyah',
        'path_file',
        'urutan',
    ];

    public function modulIqra()
    {
        return $this->belongsTo(ModulIqra::class, 'modul_iqra_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function progressBelajar()
    {
        return $this->hasMany(ProgressBelajar::class, 'materi_id');
    }
}
