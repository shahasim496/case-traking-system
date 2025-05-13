<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyEvidence extends Model
{
    use HasFactory;
 protected $table = 'currency_evidences';
    protected $fillable = [
        'evidence_id',   // Foreign key to the evidence table
        'item_id',       // Item ID
        'description',   // Description of the package and type of currency
        'denomination',  // Denomination of the currency
        'quantity',      // Quantity of the currency
        'subtotal',      // Subtotal for the currency
        'total_value',   // Total value of the currency
    ];
}
