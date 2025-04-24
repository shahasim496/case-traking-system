<?php

namespace App\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use App\Models\User;
use App\Models\Role;
use Auth;

class TrainingTypeScope implements Scope

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

            if(Auth::User()->hasRole('Officer')){
                $group_service_id = auth()->user()->officer->group_service_id ?? '';
                $builder->where('id', $group_service_id);
            }else{

                $role = Role::find(Auth::user()->roles->first()->id);
                $training_type_ids = $role->training_types()->get(['training_type_id'])->toArray();

                $allow_trainings = $training_type_ids ?? array();
                $builder->whereIn('id', $allow_trainings);

            }

        }//end of if
    }

}
