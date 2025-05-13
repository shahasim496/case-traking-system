<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChainOfCustody extends Model
{
    use HasFactory;

    protected $fillable = [
        'evidence_id',   // Foreign key to the evidence table
        'date',          // Date of custody
        'time',          // Time of custody
        'delivered_by',  // Person who delivered the evidence
        'received_by',   // Person who received the evidence
        'comments',      // Additional comments
    ];
}
