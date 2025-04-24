<?php

namespace App\Http\Controllers;

use App\Models\Subdivision;
use App\Models\AdministrativeUnit;
use Illuminate\Http\Request;

class SubdivisionController extends Controller
{
    public function index()
    {
        $subdivisions = Subdivision::with('administrativeUnit')->get();
        return view('subdivisions.index', compact('subdivisions'));
    }

    public function create()
    {
        $units = AdministrativeUnit::all();
        return view('subdivisions.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'administrative_unit_id' => 'required|exists:administrative_units,id',
            'name' => 'required|string',
        ]);

        Subdivision::create($request->only('administrative_unit_id', 'name'));

        return redirect()->route('subdivisions.index')->with('success', 'Subdivision created successfully.');
    }

    public function edit($id)
    {
        $subdivision = Subdivision::findOrFail($id);
        $units = AdministrativeUnit::all();
        return view('subdivisions.edit', compact('subdivision', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'administrative_unit_id' => 'required|exists:administrative_units,id',
            'name' => 'required|string',
        ]);

        $subdivision = Subdivision::findOrFail($id);
        $subdivision->update($request->only('administrative_unit_id', 'name'));

        return redirect()->route('subdivisions.index')->with('success', 'Subdivision updated successfully.');
    }

    public function destroy($id)
    {
        $subdivision = Subdivision::findOrFail($id);
        $subdivision->delete();

        return redirect()->route('subdivisions.index')->with('success', 'Subdivision deleted successfully.');
    }
}
