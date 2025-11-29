<?php

namespace App\Http\Controllers;

use App\Models\TaskLog;
use App\Models\CourtCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskLogController extends Controller
{
    public function index(Request $request, $case_id)
    {
        $case = CourtCase::findOrFail($case_id);
        
        // Check if user has access to this case
        $user = Auth::user();
       
        $query = TaskLog::where('case_id', $case_id)
            ->with('user')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by category if provided
        if ($request->has('category') && $request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by activity type if provided
        if ($request->has('activity_type') && $request->activity_type && $request->activity_type !== 'all') {
            $query->where('activity_type', $request->activity_type);
        }

        $taskLogs = $query->paginate(10)->withQueryString();

        // Get available categories and activity types for filter dropdowns
        $categories = TaskLog::where('case_id', $case_id)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $activityTypes = TaskLog::where('case_id', $case_id)
            ->distinct()
            ->pluck('activity_type')
            ->filter()
            ->sort()
            ->values();

        return view('task-logs.index', compact('taskLogs', 'case', 'categories', 'activityTypes'));
    }
}
