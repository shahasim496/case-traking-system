<?php

namespace App\Http\Controllers;

use App\Models\CaseType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CaseTypeController extends Controller
{
    public function index()
    {
        $caseTypes = CaseType::orderBy('id', 'DESC')->paginate(10);
        return view('case_types.index', compact('caseTypes'));
    }

  
    public function create()
    {
        return view('case_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:case_types,name',
            'description' => 'nullable|string',
        ]);

        CaseType::create($request->all());

        return redirect()->route('case_types')->with('success', 'Case Type created successfully.');
    }

    public function edit($id)
    {
        $caseType = CaseType::findOrFail($id);
        return view('case_types.edit', compact('caseType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:case_types,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $caseType = CaseType::findOrFail($id);
        $caseType->update($request->all());

        return redirect()->route('case_types')->with('success', 'Case Type updated successfully.');
    }

    public function delete($id)
    {
        $caseType = CaseType::findOrFail($id);
        $caseType->delete();

        return redirect()->route('case_types')->with('success', 'Case Type deleted successfully.');
    }
}

