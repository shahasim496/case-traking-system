<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::orderBy('id', 'DESC')->paginate(10);
        return view('courts.index', compact('courts'));
    }

  
    public function create()
    {
        return view('courts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:courts,name',
            'description' => 'nullable|string',
        ]);

        Court::create($request->all());

        return redirect()->route('courts')->with('success', 'Court created successfully.');
    }

    public function edit($id)
    {
        $court = Court::findOrFail($id);
        return view('courts.edit', compact('court'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:courts,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $court = Court::findOrFail($id);
        $court->update($request->all());

        return redirect()->route('courts')->with('success', 'Court updated successfully.');
    }

    public function delete($id)
    {
        $court = Court::findOrFail($id);
        $court->delete();

        return redirect()->route('courts')->with('success', 'Court deleted successfully.');
    }
}

