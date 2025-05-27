<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TaskLog;
use App\Models\DnaDonor;
use App\Models\Evidence;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\Evidence_User;
use App\Models\VideoEvidence;
use App\Models\ChainOfCustody;
use App\Models\GeneralEvidence;
use App\Models\CurrencyEvidence;
use App\Models\BallisticsEvidence;
use App\Models\ToxicologyEvidence;
use App\Mail\EvidenceSubmittedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EvidenceReportReadyMail;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
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

      
     
        try {
            $request->validate([
                'status' => 'required',
                'notes' => 'nullable|string|max:1000',
                'report' => 'nullable|file|mimes:pdf,doc,docx' // Max 10MB
            ]);

            $evidence = Evidence::findOrFail($id);
        
            $updateData = [
                'status' => $request->status,
                'notes' => $request->notes,
                'status_updated_at' => now(),
                'status_updated_by' => auth()->id(),
            ];

            // If status is rejected, clear the EVO officer
            if ($request->status === 'rejected') {
                $updateData['evo_officer_id'] = null;
            } else {

                if($request->evo_officer_id1){
                $updateData['evo_officer_id'] = $request->evo_officer_id1;
                $userid=$updateData['evo_officer_id'];
                }elseif($request->evo_officer_id2){
                    $updateData['evo_officer_id'] = $request->evo_officer_id2;
                    $userid=$updateData['evo_officer_id'];
                }elseif($request->evo_officer_id3){
                    $updateData['evo_officer_id'] = $request->evo_officer_id3;
                    $userid=$updateData['evo_officer_id'];
                }elseif($request->evo_officer_id){
                    $updateData['evo_officer_id'] = $request->evo_officer_id;
                    $userid=$updateData['evo_officer_id'];
                }
            }

            if ($request->hasFile('report')) {
                // Delete old file if exists
                if ($evidence->report_path && Storage::exists('public/' . $evidence->report_path)) {
                    Storage::delete('public/' . $evidence->report_path);
                }
    
                // Store new file with proper directory name
                $file = $request->file('report');
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Store in storage/app/public
                $filePath = $file->storeAs('evidence_reports', $fileName, 'public');
                
                // Create symbolic link if it doesn't exist
                if (!file_exists(public_path('storage'))) {
                    \Artisan::call('storage:link');
                }
                
                $updateData['report_path'] = $filePath;


                
            }

            $evidence->update($updateData);

            // Send email notification if status is completed
            if ($request->status === 'completed') {
                Mail::to($evidence->officer_email)->send(new EvidenceReportReadyMail($evidence));
            }

            if($request->evo_officer_id1 || $request->evo_officer_id2 || $request->evo_officer_id3 || $request->evo_officer_id){    
                try {
                    Evidence_User::firstOrCreate(
                        [
                           'evidence_id' => $evidence->id,
                           'user_id' => $userid,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    // Send notification to the assigned EVO officer
                    $user = User::findOrFail($userid);
                    $message = sprintf(
                        "Evidence #%s (%s) has been assigned to you. Status: %s",
                        $evidence->id,
                        ucfirst($evidence->type),
                        ucfirst($request->status)
                    );
                    $user->notify(new UserNotification($message));
                } catch (\Exception $e) {
                    // Log the error but continue with the update
                    Log::error('Error creating evidence user relationship: ' . $e->getMessage());
                }
            }

            return redirect()->back()->with('success', 'Evidence updated successfully.');
        } catch (\Exception $e) {
          
            return redirect()->back()->with('error', 'Error updating evidence: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $evidence = Evidence::findOrFail($id);

        if ($evidence->file_path && Storage::exists('public/' . $evidence->file_path)) {
            Storage::delete('public/' . $evidence->file_path);
        }

        $evidence->delete();
       

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
        $user = User::where('id', $request->id)->first();

       

        if (!$user) {
            // If user does not exist, redirect back with an error message
            return redirect()->back()->with('error', 'User not found in the database.');
        }

        // If user exists, proceed with storing evidence
        // Add your evidence storing logic here

     
        return view('evidences.add_evidene', ['success' => 'User Verified successfully.']);
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error('Error verifying police officer: ' . $e->getMessage());

        // Redirect back with a generic error message
        return redirect()->back()->with('error', 'An error occurred while verifying the officer. Please try again.');
    }
}

public function receipts(Request $request)
{
    // Get the perPage value from the request, default to 10
    $perPage = $request->get('perPage', 10);

    // Get the type filter from the request
    $type = $request->get('type', '');

    // Fetch evidence records with optional filtering by type
    $query = Evidence::query()->orderBy('created_at', 'desc');

    if (!empty($type)) {
        $query->where('type', $type);
    }

    $evidences = $query->paginate($perPage);

    return view('evidences.receipts', compact('evidences'));
}

public function receipt($id)
{
    $evidence = Evidence::with('chainOfCustodies','dnaDonors','ballisticsEvidence','currencyEvidence','toxicologyEvidence',
    'videoEvidence','questionedDocumentEvidence','generalEvidence')->findOrFail($id);
    
    return view('evidences.receipt', compact('evidence'));
}

public function store(Request $request)
{

    
    // Identify the form type (add a hidden input in each form: <input type="hidden" name="form_type" value="dna">)
    $formType = $request->type;


    // Common fields for all forms
    $evidence = Evidence::create([
        'type' => $formType,
        'officer_id' => $request->officer_id,
        'officer_email' => $request->officer_email,
        'officer_name' => $request->officer_name,
        'designation' => $request->designation,
        'g_officer_id' => $request->g_officer_id,
        'g_officer_name' => $request->g_officer_name,
        'g_designation' => $request->g_designation,
        'case_id' => $request->case_id,
        'case_description' => $request->case_description,
    ]);

    Mail::to($evidence->officer_email)->send(new EvidenceSubmittedMail($evidence));

      // Handle Chain of Custody (common to all forms)
    ChainOfCustody::create([
        'evidence_id' => $evidence->id,
        'date' => $request->chain_date,
        'time' => $request->chain_time,
        'delivered_by' => $request->delivered_by,
        'received_by' => $request->received_by,
        'comments' => $request->chain_comments,
    ]);


    Evidence_User::firstOrCreate(
        [
           'evidence_id' => $evidence->id,
         'user_id' => auth()->id(),
        ],
        [
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );

   

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

    return redirect()->route('evidence.receipt', $evidence->id)->with('success', 'Evidence submitted successfully!');
}

public function index(Request $request)
{
    $query = Evidence::query();

    // Check if the user is a SuperAdmin
    if (auth()->user()->hasRole('SuperAdmin')) {
        // SuperAdmin can see all cases
        $query = $query->orderBy('created_at', 'desc');
    } else {
        // Other users can only see cases where their ID is in the CaseUsers table
        $query = $query->whereHas('EvidenceUsers', function ($subQuery) {
            $subQuery->where('user_id', auth()->id());
        })->orderBy('created_at', 'desc');
    }

    // Get the perPage value from the request, default to 10
    $perPage = $request->get('perPage', 10);

    // Get the type filter from the request
    $type = $request->get('type', '');

    if (!empty($type)) {
        $query->where('type', $type);
    }

    $evidences = $query->paginate($perPage);

    return view('evidences.index', compact('evidences'));
}
public function show($id)
{
    $evidence = Evidence::with('chainOfCustodies','dnaDonors','ballisticsEvidence','currencyEvidence','toxicologyEvidence'
    ,'videoEvidence','questionedDocumentEvidence','generalEvidence')->findOrFail($id);

    // Get only users with EVO role
    $officers = User::role('EVO')->get();

    return view('evidences.show', compact('evidence', 'officers'));
}

public function verifyOfficer(Request $request)
{
    try {
        // Get the officer ID from the request
        $officerId = $request->input('officer_id');
        $evidenceId = $request->input('evidence_id');

        // Try to find the officer in the database
        $officer = User::where('id', $officerId)->first();

        if ($officer) {
            // Officer found in the database
            return redirect()->back()->with('success', 'Officer verification successful! Officer found in the system.');

           
        } else {
            // Officer not found
            return redirect()->back()->with('error', 'Officer verification failed! Officer not found in the system.');
        }
    } catch (\Exception $e) {
        Log::error('Error verifying officer: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while verifying the officer: ' . $e->getMessage());
    }
}

public function getEvoAnalysts()
{
    $evoAnalysts = User::role('EVO Analyst')->get(['id', 'name']);
    return response()->json($evoAnalysts);
}

public function getUsersByRoles()
{
    $usersByRole = [
        'EVO' => User::role('EVO')->with('designation')->get(['id', 'name', 'designation_id']),
        'EVO Analyst' => User::role('EVO Analyst')->with('designation')->get(['id', 'name', 'designation_id']),
        'GFSL Security Officer' => User::role('GFSL Security Officer')->with('designation')->get(['id', 'name', 'designation_id']),
    ];

    return response()->json($usersByRole);
}

}
