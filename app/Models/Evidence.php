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
        'officer_email', 
        'designation',   // Officer's designation
        'g_officer_id',
        'g_officer_name',// Name of the government officer
        'g_designation', // Government officer's designation
        'status',
        'evo_officer_id',
        'notes',
        'status_updated_at',
        'status_updated_by',
        'officer_verified',
        'officer_verified_at',
        'officer_verified_by',
        'report_path',
        'case_id',
        'case_description',
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


     public function generalEvidence()
    {
        return $this->hasOne(GeneralEvidence::class);
    }
    public function evoOfficer()
    {
        return $this->belongsTo(User::class, 'evo_officer_id');
    }
    
    public function statusUpdatedBy()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }


    public function EvidenceUsers()
    {
        return $this->hasMany(Evidence_User::class, 'evidence_id', 'id');
    }
}
