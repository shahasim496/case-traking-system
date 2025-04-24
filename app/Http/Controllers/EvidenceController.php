<?php

namespace App\Http\Controllers;

use App\Models\TaskLog;
use App\Models\Evidence;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
    public function store(Request $request, $case_id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'collected_by' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $filePath = $request->file('file')->store('evidences', 'public');

        Evidence::create([
            'case_id' => $case_id,
            'type' => $request->type,
            'date' => $request->date,
            'collected_by' => $request->collected_by,
            'file_path' => $filePath,
        ]);

        TaskLog::create([
            'case_id' => $case_id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'Evidence doc added ', // Static description
            'action_taken' => 'added', // Static action
        ]);

        return redirect()->back()->with('success', 'Evidence added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'collected_by' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $evidence = Evidence::findOrFail($id);

        $evidence->type = $request->type;
        $evidence->date = $request->date;
        $evidence->collected_by = $request->collected_by;

        if ($request->hasFile('file')) {
            if ($evidence->file_path && \Storage::exists('public/' . $evidence->file_path)) {
                \Storage::delete('public/' . $evidence->file_path);
            }

            $filePath = $request->file('file')->store('evidences', 'public');
            $evidence->file_path = $filePath;
        }

        $evidence->save();

        TaskLog::create([
            'case_id' => $evidence->case_id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'Evidence doc updated ', // Static description
            'action_taken' => 'updated', // Static action
        ]);

        return redirect()->back()->with('success', 'Evidence updated successfully.');
    }

    public function destroy($id)
    {
        $evidence = Evidence::findOrFail($id);

        if ($evidence->file_path && \Storage::exists('public/' . $evidence->file_path)) {
            \Storage::delete('public/' . $evidence->file_path);
        }

        $evidence->delete();
        TaskLog::create([
            'case_id' => $evidence->case_id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'Evidence doc deleted ', // Static description
            'action_taken' => 'deleted', // Static action
        ]);

        return redirect()->back()->with('success', 'Evidence deleted successfully.');
    }
}
