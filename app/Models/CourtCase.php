<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtCase extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'court_type',
        'case_title',
        'entity_id',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the notices for the case.
     */
    public function notices()
    {
        return $this->hasMany(Notice::class, 'case_id');
    }

    /**
     * Get the hearings for the case.
     */
    public function hearings()
    {
        return $this->hasMany(Hearing::class, 'case_id');
    }

    /**
     * Get the parties for the case.
     */
    public function parties()
    {
        return $this->hasMany(Party::class, 'case_id');
    }

    /**
     * Get the user who created the case.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the case.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the entity for the case.
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    /**
     * Get the forwards for the case.
     */
    public function forwards()
    {
        return $this->hasMany(CaseForward::class, 'case_id');
    }

    /**
     * Get the comments for the case.
     */
    public function comments()
    {
        return $this->hasMany(CaseComment::class, 'case_id')->orderBy('created_at', 'DESC');
    }

    /**
     * Get the task logs for the case.
     */
    public function taskLogs()
    {
        return $this->hasMany(TaskLog::class, 'case_id')->orderBy('created_at', 'DESC');
    }
}

