<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'file_name',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the case that owns the file.
     */
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }
}
