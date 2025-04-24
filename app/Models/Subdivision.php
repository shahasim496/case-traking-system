<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model
{
    use HasFactory;

    protected $fillable = ['administrative_unit_id', 'name'];

    public function administrativeUnit()
    {
        return $this->belongsTo(AdministrativeUnit::class);
    }

    public function policeStations()
    {
        return $this->hasMany(PoliceStation::class);
    }
}
