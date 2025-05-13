<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToxicologyEvidence extends Model
{
    use HasFactory;
 protected $table = 'toxicology_evidences';
    protected $fillable = [
        'evidence_id',   // Foreign key to the evidence table
        'item_id',       // Item ID
        'sample_type',   // Type of sample (e.g., blood, urine, etc.)
        'quantity',      // Quantity of the sample
        'collection',    // Collection details
        'description',   // Description of the sample
        'examination',   // JSON field for examination details (e.g., alcohol, drugs, etc.)
    ];
}
