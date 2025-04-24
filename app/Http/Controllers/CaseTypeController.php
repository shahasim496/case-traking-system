<?php
namespace App\Http\Controllers;

use App\Models\CaseType;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CaseTypeController extends Controller
{
    public function index()
    {
        return view('cases.index');
    }

    public function getCases(Request $request)
    {
        $cases = CaseType::orderBy('id', 'DESC')->get();

        return DataTables::of($cases)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route('cases.edit', $row->id) . '" class="btn edit_icon" data-toggle="tooltip" title="Edit">
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
        return view('cases.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:case_types,name',
        ]);

        CaseType::create($request->only('name'));

        return redirect()->route('cases')->with('success', 'Case created successfully.');
    }

    public function edit($id)
    {
        $case = CaseType::findOrFail($id);
        return view('cases.edit', compact('case'));
    }

    public function update(Request $request, $id)
    {
      
        $request->validate([
            'name' => 'required|unique:case_types,name,' . $id,
        ]);
        

        $case = CaseType::findOrFail($id);
        $case->update($request->only('name'));

        return redirect()->route('cases')->with('success', 'Case updated successfully.');
    }

    public function delete($id)
    {
        $case = CaseType::findOrFail($id);
        $case->delete();

        return redirect()->route('cases')->with('success', 'Case deleted successfully.');
    }
}