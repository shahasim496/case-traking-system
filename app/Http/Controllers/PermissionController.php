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
                $actionBtn = '<div class="d-flex gap-1">';
                
                // Edit button
                if (auth()->user()->can('edit permission')) {
                    $actionBtn .= '<a href="' . route('permissions.edit', $row->id) . '" class="btn d-flex align-items-center justify-content-center" style="width: 80px; background-color: #00349C; color: white;" title="Edit">
                                    <i class="fa fa-edit mr-1"></i>Edit
                                  </a>';
                }
                
                // Delete button
                if (auth()->user()->can('delete permission')) {
                    $actionBtn .= '<button class="btn btn-danger d-flex align-items-center justify-content-center delete-btn" 
                                            style="width: 80px;"
                                            data-id="' . $row->id . '" 
                                            data-name="' . $row->name . '"
                                            data-toggle="modal" 
                                            data-target="#deleteModal" 
                                            title="Delete">
                                    <i class="fa fa-trash mr-1"></i>Delete
                                  </button>';
                }
                
                $actionBtn .= '</div>';
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