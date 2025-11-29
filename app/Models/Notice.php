<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'notice_date',
        'notice_details',
        'attachment',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'notice_date' => 'date',
    ];

    /**
     * Get the case that owns the notice.
     */
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the user who created the notice.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

