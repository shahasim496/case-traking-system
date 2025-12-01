<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;
   
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'court_type'];

    /**
     * Get the work benches for the court.
     */
    public function workBenches()
    {
        return $this->hasMany(WorkBench::class, 'court_id');
    }
}

