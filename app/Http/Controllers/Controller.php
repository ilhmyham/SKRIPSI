<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

abstract class Controller
{
    protected function logActivity(
        string $type,
        string $subjectType,
        $subjectId,
        string $description,
        array $properties = []
    ): void {
        ActivityLog::create([
            'user_id'       => auth()->id(),
            'activity_type' => $type,
            'subject_type'  => $subjectType,
            'subject_id'    => $subjectId,
            'description'   => $description,
            'properties'    => $properties,
        ]);
    }
}
