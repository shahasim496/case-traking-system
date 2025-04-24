<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtProceeding extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'name',
        'description',
        'file_path',
    ];

    // Define the relationship with the Case model
    public function case()
    {
        return $this->belongsTo(NewCaseManagement::class, 'case_id');
    }
}
