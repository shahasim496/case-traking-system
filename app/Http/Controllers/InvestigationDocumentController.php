<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Storage;
use App\Models\User;
use App\Models\TaskLog;
use Illuminate\Http\Request;
use App\Models\Group_Service;
use Spatie\Permission\Models\Role;
use App\Models\InvestigationDocument;
use Spatie\Permission\Models\Permission;

class InvestigationDocumentController extends Controller
{

public function store(Request $request, $case_id)
{
    $request->validate([
        'doc_name' => 'required|string|max:255',
        'doc_description' => 'required|string',
        'doc_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

    // Save the uploaded file
    $filePath = $request->file('doc_file')->store('documents', 'public');

    // Save document details to the database
    InvestigationDocument::create([
        'name' => $request->doc_name,
        'description' => $request->doc_description,
        'file_path' => $filePath,
        'case_id' => $case_id,
    ]);


    TaskLog::create([
        'case_id' => $case_id,
        'officer_id' => auth()->id(), // Authenticated user's ID
        'officer_name' => auth()->user()->name, // Authenticated user's name
        'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
        'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
        'date' => now(), // Current date and time
        'description' => 'investigattion doc added ', // Static description
        'action_taken' => 'added', // Static action
    ]);


    return redirect()->back()->with('success', 'Document added successfully.');
}

public function destroy($id)
{
    $document = InvestigationDocument::findOrFail($id);

    TaskLog::create([
        'case_id' =>   $document->case_id,
        'officer_id' => auth()->id(), // Authenticated user's ID
        'officer_name' => auth()->user()->name, // Authenticated user's name
        'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
        'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
        'date' => now(), // Current date and time
        'description' => 'investigattion doc deleted ', // Static description
        'action_taken' => 'deleted', // Static action
    ]);

    // Delete the file from storage
    if ($document->file_path && \Storage::exists('public/' . $document->file_path)) {
        \Storage::delete('public/' . $document->file_path);
    }

    // Delete the document record
    $document->delete();

    
 

    return redirect()->back()->with('success', 'Document deleted successfully.');
}

public function update(Request $request, $id)
{
     
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

    $document = InvestigationDocument::findOrFail($id);

    // Update document details
    $document->name = $request->name;
    $document->description = $request->description;

    // If a new file is uploaded, replace the old file
    if ($request->hasFile('file')) {
        // Delete the old file
        if ($document->file_path && Storage::exists('public/' . $document->file_path)) {
            \Storage::delete('public/' . $document->file_path);
        }

        // Save the new file
        $filePath = $request->file('file')->store('documents', 'public');
        $document->file_path = $filePath;
    }

    $document->save();

    
    TaskLog::create([
        'case_id' => $document->case_id,
        'officer_id' => auth()->id(), // Authenticated user's ID
        'officer_name' => auth()->user()->name, // Authenticated user's name
        'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
        'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
        'date' => now(), // Current date and time
        'description' => 'investigattion doc updated ', // Static description
        'action_taken' => 'updated', // Static action
    ]);

    return redirect()->back()->with('success', 'Document updated successfully.');
}

}

