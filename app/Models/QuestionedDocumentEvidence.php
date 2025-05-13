<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionedDocumentEvidence extends Model
{
    use HasFactory;
    protected $table = 'questioned_document_evidences'; 

    protected $fillable = [
        'evidence_id',           // Foreign key to the evidence table
        'item_id',               // Item ID
        'description',           // Description of the document
        'item_submitted',        // JSON field for items submitted (e.g., questioned document, reference)
        'examination_requested', // Examination requested
    ];
}
