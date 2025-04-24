<?php

namespace App\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use App\Models\User;
use App\Models\Role;
use Auth;

class GroupScope implements Scope

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

        if (Auth::User()->hasRole('Cadre')) {
            $user = Auth::user();
            $allow_caders = $user->profile->allow_caders ?? array();
            $builder->whereIn('id', $allow_caders);
        }elseif(Auth::User()->hasRole('Officer')){
            $group_service_id = auth()->user()->officer->group_service_id ?? '';
            $builder->where('id', $group_service_id);
        }else{
            if(!Auth::User()->hasRole('Admin') && !Auth::User()->hasRole('SuperAdmin')) {

                $role = Role::find(Auth::user()->roles->first()->id);
                $group_service_ids = $role->group_service()->get(['group_service_id'])->toArray();

                // $user_id = Auth::user()->user_id;
                // $user = User::find($user_id);
                // $allow_caders = $user->profile->allow_caders ?? array();

                $allow_caders = $group_service_ids ?? array();
                $builder->whereIn('id', $allow_caders);

            }//end of if
        }//end of if
    }

}
