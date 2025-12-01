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
        'case_type_id',
        'court_id',
        'work_bench_id',
        'court_type',
        'case_title',
        'case_description',
        'petitioner_name',
        'petitioner_id_number',
        'petitioner_gender',
        'petitioner_contact_number',
        'petitioner_date_of_birth',
        'petitioner_address',
        'remarks',
        'judge_name',
        'entity_id',
        'assigned_officer_id',
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

    /**
     * Get the case type for the case.
     */
    public function caseType()
    {
        return $this->belongsTo(CaseType::class, 'case_type_id');
    }

    /**
     * Get the court for the case.
     */
    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    /**
     * Get the work bench for the case.
     */
    public function workBench()
    {
        return $this->belongsTo(WorkBench::class, 'work_bench_id');
    }

    /**
     * Get the files for the case.
     */
    public function files()
    {
        return $this->hasMany(CaseFile::class, 'case_id');
    }

    /**
     * Get the case files for the case.
     */
    public function caseFiles()
    {
        return $this->hasMany(CaseFile::class, 'case_id');
    }

    /**
     * Get the assigned officer for the case.
     */
    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }
}

