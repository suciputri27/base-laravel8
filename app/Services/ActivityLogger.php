<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $description, $subject = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject->id,
        ]);
    }
}