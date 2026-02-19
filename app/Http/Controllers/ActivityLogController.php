<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('admin_id')) {
            $query->where('user_id', $request->admin_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action', $request->action_type);
        }

        $logs = $query->latest()->paginate(20);
        $users = \App\Models\User::all(); // For filter dropdown
        $actions = ['created', 'updated', 'deleted', 'login', 'logout']; // Common actions

        return view('activity_logs.index', compact('logs', 'users', 'actions'));
    }
}
