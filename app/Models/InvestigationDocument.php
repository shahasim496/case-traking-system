<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'name',
        'description',
        'file_path',
    ];

    public function case()
    {
        return $this->belongsTo(NewCaseManagement::class, 'CaseID', 'case_id');
    }
}
