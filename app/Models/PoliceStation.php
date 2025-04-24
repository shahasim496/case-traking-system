<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliceStation extends Model
{
    use HasFactory;

    protected $fillable = ['subdivision_id', 'name', 'address', 'contact_number'];

    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }
}
