<?php

namespace App\Http\Controllers;

use App\Models\WorkBench;
use App\Models\Court;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WorkBenchController extends Controller
{
    public function index()
    {
        $workBenches = WorkBench::with('court')->orderBy('id', 'DESC')->paginate(10);
        return view('work_benches.index', compact('workBenches'));
    }

  
    public function create()
    {
        $courts = Court::orderBy('name', 'ASC')->get();
        return view('work_benches.create', compact('courts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'court_id' => 'required|exists:courts,id',
            'description' => 'nullable|string',
        ]);

        WorkBench::create($request->all());

        return redirect()->route('work_benches')->with('success', 'Work Bench created successfully.');
    }

    public function edit($id)
    {
        $workBench = WorkBench::findOrFail($id);
        $courts = Court::orderBy('name', 'ASC')->get();
        return view('work_benches.edit', compact('workBench', 'courts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'court_id' => 'required|exists:courts,id',
            'description' => 'nullable|string',
        ]);

        $workBench = WorkBench::findOrFail($id);
        $workBench->update($request->all());

        return redirect()->route('work_benches')->with('success', 'Work Bench updated successfully.');
    }

    public function delete($id)
    {
        $workBench = WorkBench::findOrFail($id);
        $workBench->delete();

        return redirect()->route('work_benches')->with('success', 'Work Bench deleted successfully.');
    }
}

