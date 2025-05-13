<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnaDonor extends Model
{
    use HasFactory;

    protected $fillable = [
        'evidence_id',           // Foreign key to the evidence table
        'last_name',             // Donor's last name
        'first_name',            // Donor's first name
        'middle_initial',        // Donor's middle initial
        'phone',                 // Donor's phone number
        'dob',                   // Donor's date of birth
        'gender',                // Donor's gender
        'address',               // Donor's address
        'collection_datetime',   // Date and time of collection
        'id_number',             // Donor's identification number
    ];
}
