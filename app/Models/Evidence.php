<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table ='evidences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'type',          // Type of evidence (e.g., dna, ballistics, currency, etc.)
        'officer_id',    // ID of the officer submitting the evidence
        'officer_name',  // Name of the officer
        'designation',   // Officer's designation
        'g_officer_id',
        'g_officer_name',// Name of the government officer
        'g_designation', // Government officer's designation
    ];

    // Define the relationship with the Case model
    public function case()
    {
        return $this->belongsTo(NewCaseManagement::class, 'case_id');
    }

    // Relationships
    public function chainOfCustodies()
    {
        return $this->hasMany(ChainOfCustody::class);
    }

    public function dnaDonors()
    {
        return $this->hasMany(DnaDonor::class);
    }

    public function ballisticsEvidence()
    {
        return $this->hasOne(BallisticsEvidence::class);
    }

    public function currencyEvidence()
    {
        return $this->hasOne(CurrencyEvidence::class);
    }

    public function toxicologyEvidence()
    {
        return $this->hasOne(ToxicologyEvidence::class);
    }

    public function videoEvidence()
    {
        return $this->hasOne(VideoEvidence::class);
    }

    public function questionedDocumentEvidence()
    {
        return $this->hasOne(QuestionedDocumentEvidence::class);
    }
}
