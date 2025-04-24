<?php
namespace App\Http\Controllers;

use App\Models\AdministrativeUnit;
use App\Models\Subdivision;
use App\Models\PoliceStation;
use Illuminate\Http\Request;

class AdministrativeUnitController extends Controller
{
    public function index()
    {
        $units = AdministrativeUnit::all();
        return view('admin-units.index', compact('units'));
    }

    public function create()
    {
        return view('admin-units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:administrative_units,name',
        ]);

        AdministrativeUnit::create($request->only('name'));

        return redirect()->route('admin-units.index')->with('success', 'Administrative Unit created successfully.');
    }

    public function edit($id)
    {
        $unit = AdministrativeUnit::findOrFail($id);
        return view('admin-units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:administrative_units,name,' . $id,
        ]);

        $unit = AdministrativeUnit::findOrFail($id);
        $unit->update($request->only('name'));

        return redirect()->route('admin-units.index')->with('success', 'Administrative Unit updated successfully.');
    }

    public function destroy($id)
    {
        $unit = AdministrativeUnit::findOrFail($id);
        $unit->delete();

        return redirect()->route('admin-units.index')->with('success', 'Administrative Unit deleted successfully.');
    }

    public function getSubdivisions($unitId)
    {
        $subdivisions = Subdivision::where('administrative_unit_id', $unitId)->get();
        return response()->json($subdivisions);
    }

    public function getPoliceStations($subdivisionId)
    {
        $policeStations = PoliceStation::where('subdivision_id', $subdivisionId)->get();
        return response()->json($policeStations);
    }
}