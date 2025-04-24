<?php

namespace App\Scopes;

use App\Models\Grade;
use App\Models\Group_Service;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Auth;

class OfficerScope implements Scope

{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */

    public function apply(Builder $builder, Model $model)
    {
        if (Auth::hasUser()) {

           if (Auth::User()->hasRole('Cadre')) {
                $user = Auth::user();
                $allow_caders = $user->profile->allow_caders ?? array();

                $builder->whereIn('group_service_id', $allow_caders);

            }elseif (Auth::User()->hasRole('Officer')) {
                $officer_id = auth()->user()->officer->officer_id ?? '';
                $builder->where('id', $officer_id);
            }else {

               if (!Auth::User()->hasRole('SuperAdmin|Admin')) {

                   $permissions = [];
                   $role = Role::where('name', auth()->user()->getRoleNames())->first();
                   $permissions = $role->permissions()->where('module', 'Group Services')->get()->toArray();
                   $serviceGroups = collect($permissions)->unique('sub_module')->pluck('sub_module');
                   $serviceGroups = Group_Service::whereIn('name',$serviceGroups)->pluck('id');

                   $grades = collect($permissions)->map(function ($name) {
                       return strstr( $name['name'], 'BS');
                   })->unique();

                   $grades = Grade::whereIn('name',$grades)->pluck('id');

                //    $builder->whereIn('group_service_id', $serviceGroups);
                //    $builder->whereIn('current_grade_id', $grades);

                    $group_service_ids = $role->group_service()->get(['group_service_id'])->toArray();
                    $grades_ids = $role->group_service()->get(['grades'])->toArray();

                    $new_grade_ids = array();
                    foreach ($grades_ids as $idx => $grades_id) {
                        $new_grade_ids = array_merge($new_grade_ids, $grades_id['grades']);
                    }

                   $builder->whereIn('group_service_id', $group_service_ids);
                   $builder->whereIn('current_grade_id', $new_grade_ids);

               }

            }
        }
    }

}
