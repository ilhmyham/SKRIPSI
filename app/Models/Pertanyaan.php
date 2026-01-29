<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $table = 'pertanyaan';
    protected $primaryKey = 'pertanyaan_id';
    
    protected $fillable = [
        'kuis_id',
        'text_pertanyaan',
        'gambar_pertanyaan',
        'tipe',
    ];

    /**
     * Get the quiz
     */
    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id', 'kuis_id');
    }

    /**
     * Get answer options
     */
    public function opsiJawaban()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id', 'pertanyaan_id');
    }

    /**
     * Get the correct answer
     */
    public function correctAnswer()
    {
        return $this->hasOne(OpsiJawaban::class, 'pertanyaan_id', 'pertanyaan_id')
                    ->where('is_benar', true);
    }

    /**
     * Check if question has image
     */
    public function hasImage(): bool
    {
        return !empty($this->gambar_pertanyaan);
    }
}
