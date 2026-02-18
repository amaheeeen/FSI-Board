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
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        return view('activity_logs.index', compact('logs'));
    }
}
