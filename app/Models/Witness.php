<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Witness extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'name',
        'address',
        'national_id',
    ];

    // Relationship with WitnessFile
    public function files()
    {
        return $this->hasMany(WitnessFile::class, 'witness_id');
    }
}
