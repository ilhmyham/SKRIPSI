<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    protected $table = 'opsi_jawaban';
    protected $primaryKey = 'opsi_id';
    
    protected $fillable = [
        'pertanyaan_id',
        'teks_opsi',
        'gambar_opsi',
        'is_benar',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    /**
     * Get the question
     */
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'pertanyaan_id');
    }

    /**
     * Check if option has image
     */
    public function hasImage(): bool
    {
        return !empty($this->gambar_opsi);
    }
}
