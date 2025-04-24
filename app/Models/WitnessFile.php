<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WitnessFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'witness_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    public function witness()
    {
        return $this->belongsTo(Witness::class, 'witness_id');
    }
}
