<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TaskLog;
use App\Models\DnaDonor;
use App\Models\Evidence;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\VideoEvidence;
use App\Models\ChainOfCustody;
use App\Models\GeneralEvidence;
use App\Models\CurrencyEvidence;
use App\Models\BallisticsEvidence;
use App\Models\ToxicologyEvidence;
use App\Models\QuestionedDocumentEvidence;

class EvidenceController extends Controller
{

    public function create()
{
    $departments = Department::all();
    $designations = Designation::all();
       
    return view('evidences.verify_officer', compact('departments','designations'));
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




public function verifyPoliceOfficer(Request $request)
{
   
    try {
        // Validate the form inputs
        $request->validate([
            'id' => 'required',
            'agency' => 'required',
            'designation' => 'required',
            'contact' => 'required',
            'address' => 'required',
        ]);

        // Check if the user exists in the database
        $user = User::where('id', $request->id)
                    ->where('designation_id', $request->designation)
                    ->first();

       

        if (!$user) {
            // If user does not exist, redirect back with an error message
            return redirect()->back()->with('error', 'User not found in the database.');
        }

        // If user exists, proceed with storing evidence
        // Add your evidence storing logic here

     
        return view('evidences.add_evidene', ['success' => 'User Verified successfully.']);
    } catch (\Exception $e) {
        // Log the exception for debugging
        \Log::error('Error verifying police officer: ' . $e->getMessage());

        // Redirect back with a generic error message
        return redirect()->back()->with('error', 'An error occurred while verifying the officer. Please try again.');
    }
}

public function store(Request $request)
{

    
    // Identify the form type (add a hidden input in each form: <input type="hidden" name="form_type" value="dna">)
    $formType = $request->type;


    // Common fields for all forms
    $evidence = Evidence::create([
        'type' => $formType,
        'officer_id' => $request->officer_id,
        'officer_name' => $request->officer_name,
        'designation' => $request->designation,
        'g_officer_id' => $request->g_officer_id,
        'g_officer_name' => $request->g_officer_name,
        'g_designation' => $request->g_designation,
    ]);

      // Handle Chain of Custody (common to all forms)
    ChainOfCustody::create([
        'evidence_id' => $evidence->id,
        'date' => $request->chain_date,
        'time' => $request->chain_time,
        'delivered_by' => $request->delivered_by,
        'received_by' => $request->received_by,
        'comments' => $request->chain_comments,
    ]);

   

    // Handle specific forms
    switch ($formType) {
        case 'dna':
            // Save donors
            for ($i = 1; $i <= 10; $i++) {
                if ($request->has("donor{$i}_last_name")) {
                    DnaDonor::create([
                        'evidence_id' => $evidence->id,
                        'last_name' => $request->input("donor{$i}_last_name"),
                        'first_name' => $request->input("donor{$i}_first_name"),
                        'middle_initial' => $request->input("donor{$i}_middle_initial"),
                        'phone' => $request->input("donor{$i}_phone"),
                        'dob' => $request->input("donor{$i}_dob"),
                        'gender' => $request->input("donor{$i}_gender"),
                        'address' => $request->input("donor{$i}_address"),
                        'collection_datetime' => $request->input("donor{$i}_collection_datetime"),
                        'id_number' => $request->input("donor{$i}_id_number"),
                    ]);
                }
            }
            break;

              case 'general':
            // Save ballistics-specific data
            GeneralEvidence::create([
            'evidence_id' => $evidence->id,
            'item_id' => $request->id,
            'description' => $request->evidence_description,
        ]);
            break;




        case 'Ballistics':
            // Save ballistics-specific data
            BallisticsEvidence::create([
                'evidence_id' => $evidence->id,
                'item_id' => $request->item_id,
                'description' => $request->item_desc_,
                'firearms' => $request->firearms_,
                'ammo' => $request->ammo_,
                'casings' => $request->casings_,
                'bullets' => $request->bullets_,
                'examination_requested' => $request->examination_requested_,
            ]);
            break;

        case 'Currency':
            // Save currency-specific data
            CurrencyEvidence::create([
                'evidence_id' => $evidence->id,
                'item_id' => $request->item_id,
                'description' => $request->item_desc_,
                'denomination' => $request->denomination_,
                'quantity' => $request->quantity_,
                'subtotal' => $request->subtotal_,
                'total_value' => $request->total_value,
            ]);
            break;

        case 'Toxicology':
            // Save toxicology-specific data
            ToxicologyEvidence::create([
                'evidence_id' => $evidence->id,
                'item_id' => $request->item_id,
                'sample_type' => $request->sample_type,
                'quantity' => $request->quantity,
                'collection' => $request->collection,
                'description' => $request->description,
                'examination' => json_encode([
                    'alcohol_volatile' => $request->examination_1,
                    'therapeutic_drug' => $request->examination_2,
                    'heavy_metals' => $request->examination_3,
                    'other' => $request->examination_4,
                ]),
            ]);
            break;

        case 'Video Evidence':
            // Save video-specific data
            VideoEvidence::create([
                'evidence_id' => $evidence->id,
                'extraction_date' => $request->extraction_date_,
                'extracted_from' => $request->extracted_from_,
                'extraction_method' => $request->extraction_method_,
                'storage_media' => $request->storage_media_,
                'retrieved_by' => $request->retrieved_by_,
                'contact' => $request->contact_,
                'num_cameras' => $request->num_cameras_,
                'num_videos' => $request->num_videos_,
                'total_length' => $request->total_length_,
            ]);
            break;

        case 'questioned':
            // Save questioned document-specific data
            QuestionedDocumentEvidence::create([
                'evidence_id' => $evidence->id,
                'item_id' => $request->item_id_,
                'description' => $request->item_desc_,
                'item_submitted' => json_encode($request->item_submitted), // Handle multiple checkboxes
                'examination_requested' => $request->examination_requested,
            ]);
            break;

        default:
            return back()->with('error', 'Unknown form type.');
    }

   



    return redirect()->route('evidences.index')->with('success', 'Evidence submitted successfully!');
}

public function index()
{
     $evidences = Evidence::all(); // Fetch all evidence records
    return view('evidences.index', compact('evidences'));
}

public function show($id)
{
    $evidence = Evidence::findOrFail($id);
    return view('evidences.show', compact('evidence'));


}

}
