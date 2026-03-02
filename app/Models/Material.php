<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'modul_iqra_id',
        'user_id',
        'kategori_materi_id',
        'judul_materi',
        'deskripsi',
        'file_video',
        'huruf_hijaiyah',
        'path_file',
        'urutan',
    ];

    protected $appends = [
        'video_embed_url',
    ];

    public function modulIqra(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'modul_iqra_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategoriMateri(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'kategori_materi_id');
    }

    public function progressBelajar(): HasMany
    {
        return $this->hasMany(LearningProgress::class, 'materi_id');
    }

    protected function videoEmbedUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->file_video) {
                    return null;
                }

                $url = $this->file_video;

                if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                    return $this->getYouTubeEmbedUrl($url);
                }

                if (str_contains($url, 'drive.google.com')) {
                    return $this->getGoogleDriveEmbedUrl($url);
                }

                return "https://drive.google.com/file/d/{$url}/preview";
            },
        );
    }

    private function getYouTubeEmbedUrl(string $url): string
    {
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtu\.be\/([^?]+)/',
            '/youtube\.com\/embed\/([^?]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return "https://www.youtube.com/embed/{$matches[1]}";
            }
        }

        return $url;
    }

    private function getGoogleDriveEmbedUrl(string $url): string
    {
        if (preg_match('/\/d\/([^\/]+)/', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }

        return $url;
    }
}
