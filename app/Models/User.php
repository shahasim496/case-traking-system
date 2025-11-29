<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    // public static $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email','is_blocked',
        'password','user_id',
        'deleted_by', 
        'cnic',
        'phone',
        'department_id',
        'designation_id',
        'administrative_unit_id',
        'subdivision_id',
        'police_station_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
        * Add a mutator to ensure hashed passwords
    */
    public function setPasswordAttribute($password)
    {
        if(!empty($password)){
            $this->attributes['password'] = bcrypt($password);
        }
    }


    
    //
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }





    public function officer()
    {
        return $this->hasOne('App\Models\OfficerUser', 'user_id', 'id')->where('is_active',1);
    }

    public function applications()
    {
        return $this->hasMany('App\Models\Application', 'from_user_id', 'id');
    }

    public function caseUsers()
    {
        return $this->hasMany(CaseUser::class, 'user_id');
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
