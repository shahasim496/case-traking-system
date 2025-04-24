<?php

namespace App\Http\Controllers;

use App\Models\PoliceStation;
use App\Models\Subdivision;
use Illuminate\Http\Request;

class PoliceStationController extends Controller
{
    public function index()
    {
        $stations = PoliceStation::with('subdivision')->get();
        return view('police-stations.index', compact('stations'));
    }

    public function create()
    {
        $subdivisions = Subdivision::all();
        return view('police-stations.create', compact('subdivisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subdivision_id' => 'required|exists:subdivisions,id',
            'name' => 'required|string',
        ]);

        PoliceStation::create($request->only('subdivision_id', 'name'));

        return redirect()->route('police-stations.index')->with('success', 'Police Station created successfully.');
    }

    public function edit($id)
    {
        $station = PoliceStation::findOrFail($id);
        $subdivisions = Subdivision::all();
        return view('police-stations.edit', compact('station', 'subdivisions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subdivision_id' => 'required|exists:subdivisions,id',
            'name' => 'required|string',
        ]);

        $station = PoliceStation::findOrFail($id);
        $station->update($request->only('subdivision_id', 'name'));

        return redirect()->route('police-stations.index')->with('success', 'Police Station updated successfully.');
    }

    public function destroy($id)
    {
        $station = PoliceStation::findOrFail($id);
        $station->delete();

        return redirect()->route('police-stations.index')->with('success', 'Police Station deleted successfully.');
    }
}
