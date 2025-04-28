<?php

namespace App\Http\Controllers;

use App\Models\TaskLog;
use Illuminate\Http\Request;

class TaskLogController extends Controller
{
    public function index($case_id)
    {
        // Fetch task logs for the specified case with pagination
        $taskLogs = TaskLog::where('case_id', $case_id)
            ->orderBy('date', 'desc')
            ->paginate(10); // Adjust the number of items per page as needed

        return view('task-logs.index', compact('taskLogs', 'case_id'));
    }
}
