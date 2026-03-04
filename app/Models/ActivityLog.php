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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    public function scopeOfSubject($query, string $subjectType)
    {
        return $query->where('subject_type', $subjectType);
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function getBadgeColorAttribute(): string
    {
        return match($this->activity_type) {
            'created' => 'bg-green-100 text-green-700',
            'updated' => 'bg-blue-100 text-blue-700',
            'deleted' => 'bg-red-100 text-red-700',
            'graded'  => 'bg-purple-100 text-purple-700',
            'reset'   => 'bg-yellow-100 text-yellow-700',
            default   => 'bg-gray-100 text-gray-700',
        };
    }

    public function getIconAttribute(): string
    {
        return match($this->activity_type) {
            'created' => 'plus',
            'updated' => 'edit',
            'deleted' => 'trash',
            'graded'  => 'check',
            'reset'   => 'refresh',
            default   => 'info',
        };
    }

    public function getRelativeTimeAttribute(): string
    {
        return $this->created_at->locale('id')->diffForHumans();
    }
}
