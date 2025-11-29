<?php

namespace App\Traits;

use App\Models\TaskLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Log an activity
     *
     * @param int $caseId
     * @param string $category (case, notice, hearing, forwarding, comment)
     * @param string $activityType (created, updated, deleted, forwarded, commented)
     * @param string $description
     * @param string|null $entityType (Case, Notice, Hearing, CaseForward, CaseComment)
     * @param int|null $entityId
     * @param array|null $oldData
     * @param array|null $newData
     * @return TaskLog
     */
    protected function logActivity(
        $caseId,
        $category,
        $activityType,
        $description,
        $entityType = null,
        $entityId = null,
        $oldData = null,
        $newData = null
    ) {
        $user = Auth::user();
        
        return TaskLog::create([
            'case_id' => $caseId,
            'user_id' => $user ? $user->id : null,
            'category' => $category,
            'activity_type' => $activityType,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}

