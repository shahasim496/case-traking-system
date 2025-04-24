<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccusedDetail extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'accused_details';

    // Primary key
    protected $primaryKey = 'AccusedID';

    // Fillable fields
    protected $fillable = [
        'AccusedName',
        'CaseID',
        'AccusedDateOfBirth',
        'AccusedContact',
        'AccusedGenderType',
        'AccusedOtherDetails',
        'AccusedAddress',
    ];
}
