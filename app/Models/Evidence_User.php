<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence_User extends Model
{
    use HasFactory;
    protected $fillable = ['evidence_id', 'user_id'];
}
