<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'user_id',
        'comment',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the case that owns the comment.
     */
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the user who created the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
