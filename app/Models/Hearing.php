<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hearing extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'hearing_date',
        'purpose',
        'person_appearing',
        'outcome',
        'court_order',
        'next_hearing_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'hearing_date' => 'date',
        'next_hearing_date' => 'date',
    ];

    /**
     * Get the case that owns the hearing.
     */
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the user who created the hearing.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

