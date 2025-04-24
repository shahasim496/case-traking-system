<?php

namespace App\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use App\Models\User;
use App\Models\Role;
use Auth;

class GradeScope implements Scope

{

    /**

     * Apply the scope to a given Eloquent query builder.

     *

     * @param  \Illuminate\Database\Eloquent\Builder  $builder

     * @param  \Illuminate\Database\Eloquent\Model  $model

     * @return void

     */

    public function apply(Builder $builder, Model $model)

    {

        if(!Auth::User()->hasRole('Admin') && !Auth::User()->hasRole('SuperAdmin') &&
            !Auth::User()->hasRole('Cadre')) {

            $role = Role::find(Auth::user()->roles->first()->id);
            $group_service_ids = $role->group_service()->get(['group_service_id'])->toArray();

            $allow_caders = $group_service_ids ?? array();
            $builder->whereIn('id', $allow_caders);

        }//end of if
    }

}
