<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpseclib3\System\SSH\Agent;

class JobPosting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_postings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'department_id',
        'designation_id',
        'pay_scale',
        'job_type',
        'gender',
        'job_advertisement',
        'description',
        'requirements',
        'positions',
        'age_limit',
        'deadline',
        'status',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'deadline' => 'date',
        'positions' => 'integer',
        'age_limit' => 'integer',
    ];

    /**
     * Get the department that owns the job posting.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the designation that belongs to the job posting.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Get the user who created the job posting.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
