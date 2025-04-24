<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'officer_id',
        'officer_name',
        'officer_rank',
        'department',
        'date',
        'description',
        'action_taken',
    ];

    public function case()
    {
        return $this->belongsTo(NewCaseManagement::class, 'case_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
