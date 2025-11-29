<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // Exclude the SuperAdmin role
        $roles = Role::where('name', '!=', 'SuperAdmin')->orderBy('name', 'ASC')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'description' => 'nullable|string'
        ]);

        Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('roles')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'description' => 'nullable|string'
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('roles')->with('success', 'Role updated successfully.');
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles')->with('success', 'Role deleted successfully.');
    }

    public function managePermissions(Request $request)
    {
        // Exclude the SuperAdmin role
        $roles = Role::where('name', '!=', 'SuperAdmin')->get(); // Fetch all roles except SuperAdmin
        $permissions = [];
        $assignedPermissions = [];

        if ($request->role_id) {
            $role = Role::findById($request->role_id); // Use Spatie's findById method
            $permissions = Permission::all(); // Fetch all permissions
            $assignedPermissions = $role->permissions->pluck('id')->toArray(); // Get assigned permissions
        }

        return view('roles.manage_permissions', compact('roles', 'permissions', 'assignedPermissions', 'request'));
    }

    public function storeAssignedPermissions(Request $request)
    {
        $role = Role::findById($request->role_id); // Use Spatie's findById method

        // Sync permissions using Spatie's syncPermissions method
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.managePermissions')->with('success', 'Permissions assigned successfully.');
    }
}
