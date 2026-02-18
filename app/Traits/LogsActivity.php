<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('created', $model);
        });

        static::updated(function ($model) {
            self::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'subject_type' => get_class($model),
                'subject_id' => $model->id,
                'description' => ucfirst($action) . ' ' . class_basename($model) . ' #' . $model->id,
                'changes' => $action === 'updated' ? json_encode($model->getChanges()) : null,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
