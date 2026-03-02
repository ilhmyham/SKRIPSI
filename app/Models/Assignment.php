<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'user_id',
        'modul_iqra_id',
        'judul_tugas',
        'deskripsi_tugas',
        'tenggat_waktu',
    ];

    protected $casts = [
        'tenggat_waktu' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function modulIqra(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'modul_iqra_id');
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(Submission::class, 'tugas_id');
    }
}
