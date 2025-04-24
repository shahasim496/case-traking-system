<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCaseManagement extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'new_case_management';

    // Primary key
    protected $primaryKey = 'CaseID'; // Set the correct primary key

    // Auto-incrementing primary key
    public $incrementing = true;

    // Primary key type
    protected $keyType = 'int';


    // Fillable fields
    protected $fillable = [
        'CaseID',
        'CaseDepartmentName',
        'CaseDepartmentID',  
        'CaseType',
        'CaseCreationDate',
        'CaseStatus',
        'CaseDescription',
        'OfficerID',
        'OfficerName',
        'OfficerRank',

        'administrative_unit_id',
        'subdivision_id',
        'police_station_id',
    ];
}
