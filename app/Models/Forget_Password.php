<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forget_Password extends Model
{
    use HasFactory;

    protected $table = 'forget_passwords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'verification_code','user_id',
    ];
}
