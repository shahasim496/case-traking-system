<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    public function index()
    {
        return view('designations.index');
    }

    public function getDesignations(Request $request)
    {
   
        $designation= Designation::orderBy('id', 'DESC')->get();

        return DataTables::of($designation)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route('designations.edit', $row->id) . '" class="btn edit_icon" data-toggle="tooltip" title="Edit">
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
        return view('designations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
            'description' => 'nullable|string',
        ]);

        Designation::create($request->all());

        return redirect()->route('designations')->with('success', 'Designation created successfully.');
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        return view('designations.edit', compact('designation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $designation = Designation::findOrFail($id);
        $designation->update($request->all());

        return redirect()->route('designations')->with('success', 'Designation updated successfully.');
    }

    public function delete($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return redirect()->route('designations')->with('success', 'Designation deleted successfully.');
    }
}
