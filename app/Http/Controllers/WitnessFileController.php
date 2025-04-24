<?php

namespace App\Http\Controllers;

use App\Models\WitnessFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TaskLog;

class WitnessFileController extends Controller
{
    public function destroy($id)
    {
        $file = WitnessFile::findOrFail($id);
        $caseId = $file->witness->case_id ?? null;


     
        // Delete the file from storage
        if (Storage::exists('public/' . $file->file_path)) {
            Storage::delete('public/' . $file->file_path);
        }

        // Log the deletion in TaskLog
        TaskLog::create([
            'case_id' => $caseId, // Assuming the file is linked to a case
            'officer_id' => auth()->id(), // Authenticated user's ID
            'officer_name' => auth()->user()->name, // Authenticated user's name
            'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
            'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
            'date' => now(), // Current date and time
            'description' => 'Witness document deleted: ' . $file->file_name, // Dynamic description
            'action_taken' => 'deleted', // Static action
        ]);

        // Delete the file record from the database
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully and logged.');
    }
}
