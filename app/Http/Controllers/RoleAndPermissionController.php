<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Group_Service;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Grade;
use App\Models\Role_Group_Service;
use App\Models\Role_Training_Type;
use App\Models\Training_Type;

use Illuminate\Http\Request;
use MongoDB\BSON\Persistable;

class RoleAndPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $caderServiceGroups = array();
        if (auth()->user()->hasRole(['SuperAdmin','Admin'])) {

            $roles = Role::where('is_show',1)->get()->except([1,2,3]);
            $groupServicePermissions = Permission::where('module','Group Services')->get();
        }
        elseif (auth()->user()->hasRole('Cadre')){
            $roles = Role::where('user_id',auth()->user()->id)->get();
            $serviceGroups = Group_Service::whereIn('id', [])->get(['name'])->toArray();
            $groupServicePermissions = Permission::whereIn('sub_module',array_column($serviceGroups, 'name'))->get();
        }

        $modulePermissions = Permission::where('module','Officer')->get();
        $rerpotPermissions = Permission::where('sub_module','Report')->get();

        $caderServiceGroups = Group_Service::all(['id','name']);
        $training_types = Training_Type::where('is_show',1)->get(['id','name']);
        $grades = Grade::all();

        return view('roles-and-permissions.index', compact('roles', 'modulePermissions',
        'groupServicePermissions','caderServiceGroups','grades','training_types','rerpotPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('roles-and-permissions.index',compact(['groupServices']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request, Role $role)
    {
        $role->create(array_merge($request->validated(),['user_id' => auth()->user()->id]));
        return redirect()->back()->with('roleSuccess', 'Role created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $permissions = $role->permissions->toArray();
        $role_group_servies = $role->group_service->toArray();
        $role_training_types = $role->training_types->toArray();

        return ["status" => true, "data" => $permissions,'role'=>$role,
        "role_group_servies"=>$role_group_servies,'role_training_types'=>$role_training_types];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = $role->permissions->toArray();
        return ["status" => true, "data" => $permissions];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {

// return $request->input('permissions');
        if ($request->input('type') == "assigned") {
            $role->givePermissionTo($request->input('permissions'));
        } else {

            if($request->input('module') == 'Reporting'){
                $permissions = $role->permissions
                ->where('module','Reporting');
                $role->revokePermissionTo($permissions);
            }else{
                $role->revokePermissionTo($request->input('permissions'));
            }
        }
        return ["status" => true, "message" => "Success"];
    }

    public function updateServices(Request $request, Role $role)
    {

        $role_group = Role_Group_Service::where('group_service_id',$request->group_service_id)
        ->where('role_id',$request->role)->first();
        if ($request->input('type') == "assigned" || $request->input('select_type') == "select_grade") {

            if(empty($role_group)){
                $role_group = Role_Group_Service::updateOrCreate([
                    'role_id'=>$request->role,
                    'grades'=>$request->permissions,
                    'group_service_id'=>$request->group_service_id,
                ]);
            }else{
                $role_group->grades = $request->permissions;
                $role_group->save();
            }

        } else {
            $role_group->delete();
        }

        return ["status" => true, "message" => "Success"];
    }//end of function

    public function updateRoleTraining(Request $request,$role_id)
    {

        $role= Role::find($request->role);
        if ($request->input('type') == "assigned") {
            $role->is_training_enabled = 1;
        } else {
            $role->is_training_enabled = 0;
        }
        $role->save();

        return ["status" => true, "message" => "Success"];
    }

    public function updateRoleReporting(Request $request, Role $role)
    {

        if ($request->input('type') == "assigned") {
            $role->givePermissionTo($request->input('permissions'));
        } else {
            $role->revokePermissionTo($request->input('permissions'));
        }

        return ["status" => true, "message" => "Success"];
    }//end of function

    public function updateTrainingTypes(Request $request, Role $role)
    {

        $role_training = Role_Training_Type::where('training_type_id',$request->training_type_id)
        ->where('role_id',$request->role)->first();

        if(empty($role_training) && $request->input('type') == "assigned"){

            $role_group = Role_Training_Type::updateOrCreate([
                'role_id'=>$request->role,
                'training_type_id'=>$request->training_type_id,
            ]);

        } else {
            $role_training->delete();
        }

        return ["status" => true, "message" => "Success"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
