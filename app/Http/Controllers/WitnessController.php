<?php

namespace App\Http\Controllers;

use App\Models\TaskLog;
use App\Models\Witness;
use App\Models\WitnessFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WitnessController extends Controller
{
    public function index($case_id)
    {
        $witnesses = Witness::where('case_id', $case_id)->with('files')->get();

        return view('case-management.edit', compact('witnesses'));
    }

    public function store(Request $request, $case_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'national_id' => 'required|string|max:255',
            'files.*' => 'required|file|mimes:pdf,doc,docx,jpg,png,mp4,mkv|max:5120', // Allow video files
        ]);

        $witness = Witness::create([
            'case_id' => $case_id,
            'name' => $request->name,
            'address' => $request->address,
            'national_id' => $request->national_id,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('witness-files', 'public');
                $fileType = $file->getClientOriginalExtension() === 'mp4' || $file->getClientOriginalExtension() === 'mkv' ? 'video' : 'document';

                WitnessFile::create([
                    'witness_id' => $witness->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                ]);
            }
        }


        TaskLog::create([
            'case_id' => $case_id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'witness doc added ', // Static description
            'action_taken' => 'added', // Static action
        ]);

        return redirect()->back()->with('success', 'Witness added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'national_id' => 'required|string|max:255',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,mp4,mkv|max:5120', // Allow documents and videos
        ]);

        $witness = Witness::findOrFail($id);

        // Update witness details
        $witness->update([
            'name' => $request->name,
            'address' => $request->address,
            'national_id' => $request->national_id,
        ]);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('witness-files', 'public');
                $fileType = in_array($file->getClientOriginalExtension(), ['mp4', 'mkv']) ? 'video' : 'document';

                WitnessFile::create([
                    'witness_id' => $witness->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                ]);
            }
        }

        
        TaskLog::create([
            'case_id' => $witness->case_id,
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'witness doc updated ', // Static description
            'action_taken' => 'updated', // Static action
        ]);



        return redirect()->back()->with('success', 'Witness updated successfully.');
    }

    public function destroy($id)
{
    $witness = Witness::findOrFail($id);


    TaskLog::create([
        'case_id' => $witness->case_id,
        'officer_id' => auth()->id(), // Authenticated user's ID
        'officer_name' => auth()->user()->name, // Authenticated user's name
        'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
        'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
        'date' => now(), // Current date and time
        'description' => 'witness doc deleted ', // Static description
        'action_taken' => 'deleted', // Static action
    ]);

    // Delete associated files
    foreach ($witness->files as $file) {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();
    }

    // Delete the witness
    $witness->delete();

    return redirect()->back()->with('success', 'Witness deleted successfully.');
}
}
