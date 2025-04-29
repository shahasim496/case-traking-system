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
        'case_id',
        'type',
        'date',
        'collected_by',
        'file_path',
    ];

    // Define the relationship with the Case model
    public function case()
    {
        return $this->belongsTo(NewCaseManagement::class, 'case_id');
    }
}
