<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulIqra extends Model
{
    protected $table = 'modul_iqra';
    protected $primaryKey = 'modul_id';
    
    protected $fillable = [
        'nama_modul',
        'deskripsi',
    ];

    /**
     * Get all materials in this module
     */
    public function materi()
    {
        return $this->hasMany(Materi::class, 'modul_iqra_modul_id', 'modul_id');
    }

    /**
     * Get all kuis for this module
     */
    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'modul_iqra_modul_id', 'modul_id');
    }
}
