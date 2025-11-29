<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'user_id',
        'category',
        'activity_type',
        'entity_type',
        'entity_id',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the related entity (Notice, Hearing, etc.)
     */
    public function entity()
    {
        if (!$this->entity_type || !$this->entity_id) {
            return null;
        }

        $modelClass = 'App\\Models\\' . $this->entity_type;
        if (class_exists($modelClass)) {
            return $modelClass::find($this->entity_id);
        }

        return null;
    }
}
