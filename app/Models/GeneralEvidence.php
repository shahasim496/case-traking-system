<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralEvidence extends Model
{
    use HasFactory;
protected $table = 'general_evidences';
     protected $fillable = [
        'evidence_id',   // Foreign key to the evidence table
        'item_id',       // Item ID
        'description',   // Description of the evidence
    ];
}
