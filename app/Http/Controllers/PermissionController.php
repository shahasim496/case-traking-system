<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permissions.index');
    }

    public function getPermissions(Request $request)
    {
        $permissions = Permission::select('id', 'name', 'description')->orderBy('id', 'DESC')->get();

        return DataTables::of($permissions)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route('permissions.edit', $row->id) . '" class="btn edit_icon" data-toggle="tooltip" title="Edit">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                              </a>
                              <button class="btn delete_icon" data-id="' . $row->id . '" data-toggle="modal" data-target="#deleteModal" title="Delete">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                              </button>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        Permission::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permissions')->with('success', 'Permission created successfully.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permissions')->with('success', 'Permission updated successfully.');
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions')->with('success', 'Permission deleted successfully.');
    }
}