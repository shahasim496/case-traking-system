<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseForward extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'forwarded_by',
        'forwarded_to',
        'message',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the case that was forwarded.
     */
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the user who forwarded the case.
     */
    public function forwardedBy()
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    /**
     * Get the user to whom the case was forwarded.
     */
    public function forwardedTo()
    {
        return $this->belongsTo(User::class, 'forwarded_to');
    }
}
