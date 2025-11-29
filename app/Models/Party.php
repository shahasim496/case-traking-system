<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'party_name',
        'party_details',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the case that owns the party.
     */
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the user who created the party.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

