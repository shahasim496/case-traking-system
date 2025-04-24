<?php

namespace App\Http\Controllers;

use App\Models\TaskLog;
use Illuminate\Http\Request;
use App\Models\CourtProceeding;

class CourtProceedingController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'proceeding_name' => 'required|string|max:255',
            'proceeding_description' => 'required|string',
            'proceeding_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $proceeding = CourtProceeding::findOrFail($id);

        $proceeding->name = $request->proceeding_name;
        $proceeding->description = $request->proceeding_description;

        if ($request->hasFile('proceeding_file')) {
            if ($proceeding->file_path && \Storage::exists('public/' . $proceeding->file_path)) {
                \Storage::delete('public/' . $proceeding->file_path);
            }

            $filePath = $request->file('proceeding_file')->store('court-proceedings', 'public');
            $proceeding->file_path = $filePath;
        }

        $proceeding->save();

           
        TaskLog::create([
            'case_id' => $proceeding->case_id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'court proceeding updated ', // Static description
            'action_taken' => 'updated', // Static action
        ]);

        

        return redirect()->back()->with('success', 'Court proceeding updated successfully.');
    }

    public function store(Request $request, $case_id)
    {
        $request->validate([
            'proceeding_name' => 'required|string|max:255',
            'proceeding_description' => 'required|string',
            'proceeding_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $filePath = $request->file('proceeding_file')->store('court-proceedings', 'public');

        CourtProceeding::create([
            'case_id' => $case_id,
            'name' => $request->proceeding_name,
            'description' => $request->proceeding_description,
            'file_path' => $filePath,
        ]);

        
        TaskLog::create([
            'case_id' => $case_id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'court proceeding  added ', // Static description
            'action_taken' => 'added', // Static action
        ]);

        return redirect()->back()->with('success', 'Court proceeding added successfully.');
    }

    public function destroy($id)
    {
        $proceeding = CourtProceeding::findOrFail($id);

        TaskLog::create([
            'case_id' => $proceeding->id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'court proceeding doc deleted ', // Static description
            'action_taken' => 'deleted', // Static action
        ]);

        if ($proceeding->file_path && \Storage::exists('public/' . $proceeding->file_path)) {
            \Storage::delete('public/' . $proceeding->file_path);
        }

        $proceeding->delete();


       

        return redirect()->back()->with('success', 'Court proceeding deleted successfully.');
    }
}
