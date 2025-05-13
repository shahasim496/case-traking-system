<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BallisticsEvidence extends Model
{
    use HasFactory;
      protected $table = 'ballistics_evidences';

    protected $fillable = [
        'evidence_id',           // Foreign key to the evidence table
        'item_id',               // Item ID
        'description',           // Description of the evidence
        'firearms',              // Number of firearms
        'ammo',                  // Number of ammunition
        'casings',               // Number of casings
        'bullets',               // Number of bullets
        'examination_requested', // Examination requested
    ];
}
