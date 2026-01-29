<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type',
        'subject_type',
        'subject_id',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by activity type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope to filter by subject type
     */
    public function scopeOfSubject($query, string $subjectType)
    {
        return $query->where('subject_type', $subjectType);
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Get badge color based on activity type
     */
    public function getBadgeColorAttribute(): string
    {
        return match($this->activity_type) {
            'created' => 'bg-green-100 text-green-700',
            'updated' => 'bg-blue-100 text-blue-700',
            'deleted' => 'bg-red-100 text-red-700',
            'graded' => 'bg-purple-100 text-purple-700',
            'reset' => 'bg-yellow-100 text-yellow-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Get icon based on activity type
     */
    public function getIconAttribute(): string
    {
        return match($this->activity_type) {
            'created' => 'plus',
            'updated' => 'edit',
            'deleted' => 'trash',
            'graded' => 'check',
            'reset' => 'refresh',
            default => 'info',
        };
    }

    /**
     * Get relative time (e.g., "5 menit yang lalu")
     */
    public function getRelativeTimeAttribute(): string
    {
        $diff = $this->created_at->diffForHumans();
        
        // Translate to Indonesian
        $translations = [
            'seconds ago' => 'detik yang lalu',
            'second ago' => 'detik yang lalu',
            'minutes ago' => 'menit yang lalu',
            'minute ago' => 'menit yang lalu',
            'hours ago' => 'jam yang lalu',
            'hour ago' => 'jam yang lalu',
            'days ago' => 'hari yang lalu',
            'day ago' => 'hari yang lalu',
            'weeks ago' => 'minggu yang lalu',
            'week ago' => 'minggu yang lalu',
            'months ago' => 'bulan yang lalu',
            'month ago' => 'bulan yang lalu',
        ];

        foreach ($translations as $en => $id) {
            $diff = str_replace($en, $id, $diff);
        }

        return $diff;
    }
}
