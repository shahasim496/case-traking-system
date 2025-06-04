<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name', 'user_id', 'is_training_enabled', 'is_show', 'description'
    ];

    public function group_service()
    {
        return $this->hasMany(Role_Group_Service::class,'role_id');
    }

    public function training_types()
    {
        return $this->hasMany(Role_Training_Type::class,'role_id');
    }
}
