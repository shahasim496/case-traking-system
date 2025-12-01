<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkBench extends Model
{
    use HasFactory;
   
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'work_benches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'court_id', 'description'];

    /**
     * Get the court that owns the work bench.
     */
    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }
}

