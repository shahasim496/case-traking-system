<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainantDetail extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'complainant_details';

    // Primary key
    protected $primaryKey = 'ComplainantID';

    // Fillable fields
    protected $fillable = [
        'ComplainantName',
        'CaseID',
        'ComplainantDateOfBirth',
        'ComplainantContact',
        'ComplainantGenderType',
        'ComplainantOtherDetails',
        'ComplainantAddress',
    ];
}
