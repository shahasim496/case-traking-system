<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\TaskLog;
use App\Models\Witness;
use App\Models\CaseType;
use App\Models\CaseUser;
use App\Models\Evidence;
use App\Models\Department;
use App\Models\Subdivision;
use Illuminate\Http\Request;
use App\Models\AccusedDetail;
use App\Models\PoliceStation;
use App\Models\CaseManagement;
use App\Models\CourtProceeding;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ComplainantDetail;
use App\Models\NewCaseManagement;
use App\Models\AdministrativeUnit;
use App\Models\InvestigationDocument;
use App\Notifications\UserNotification;

class CaseController extends Controller
{
    public function create()
    {
        // Fetch case types
        $caseTypes = CaseType::all(); // Assuming you have a `CaseType` model

        // Fetch officers
        $officers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Police Officer / Help Desk Officer'); // Assuming 'Officer' is the role name
        })->get();


        // Fetch departments
        $departments = Department::all();
        $administrativeUnits = AdministrativeUnit::all();


        return view('case-management.createcase', compact('caseTypes', 'officers', 'departments', 'administrativeUnits'));
    }

    public function index()
    {

     
      
        if (auth()->user()->hasRole('SuperAdmin')) {

          
            // Super Admin can see all cases
            $cases = NewCaseManagement::orderBy('created_at', 'desc')->get();
        } else {
           
            // Other users can only see cases where their ID is in the CaseUsers table
            $cases = NewCaseManagement::whereHas('caseUsers', function ($query) {
                $query->where('user_id', auth()->id());
            })->orderBy('created_at', 'desc')->get();
        }

     


        // Pass cases to the view
        return view('case-management.index', compact('cases'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'case_type' => 'required|string|max:50',
                'case_status' => 'required|string|max:50',
                'department' => 'required|integer|exists:departments,id',
                'case_description' => 'nullable|string',
                'administrative_unit' => 'required|integer|exists:administrative_units,id',
                'subdivision' => 'required|integer|exists:subdivisions,id',
                'police_station' => 'required|integer|exists:police_stations,id',
                'complainant_name' => 'required|string|max:50',
                'complainant_contact' => 'required|string|max:50',
                'complainant_id_number' => 'required|string|max:50',
                'complainant_dob' => 'required|date',
                'complainant_gender' => 'required|string|max:50',
                'complainant_address' => 'nullable|string|max:100',
                'complainant_relation' => 'nullable|string|max:50',
                'accused_name' => 'required|string|max:50',
                'accused_contact' => 'required|string|max:50',
                'accused_id_number' => 'required|string|max:50',
                'accused_dob' => 'required|date',
                'accused_gender' => 'required|string|max:50',
                'accused_address' => 'nullable|string|max:100',
                'accused_relation' => 'nullable|string|max:50',
            ]);

            // Debug relationships
            $department = Department::find($request->department);
            if (!$department) {

                return redirect()->route('casess.index')->with('false', 'Department not found.');
            }

            $officer = User::find($request->officer);
            if (!$officer) {

                return redirect()->route('casess.index')->with('false', 'Officer not found.');
            }

            // Save Case Management Data
            $case = NewCaseManagement::create([
                'CaseType' => $request->case_type,
                'CaseStatus' => $request->case_status,
                'CaseDescription' => $request->case_description,
                'CaseDepartmentID' => $request->department,
                'CaseDepartmentName' => $department->name,
                'OfficerID' => $request->officer,
                'OfficerName' => $officer->name,
                'OfficerRank' => $officer->designation_id,
                'administrative_unit_id' => $request->administrative_unit,
                'subdivision_id' => $request->subdivision,
                'police_station_id' => $request->police_station,
            ]);

            // Debug CaseID
            if (!$case->CaseID) {

                return redirect()->route('casess.index')->with('false', 'CaseID not generated.');
            }

            // Save Complainant Data
            ComplainantDetail::create([
                'CaseID' => $case->CaseID,
                'ComplainantName' => $request->complainant_name,
                'ComplainantContact' => $request->complainant_contact,
                'ComplainantID' => $request->complainant_id_number,
                'ComplainantDateOfBirth' => $request->complainant_dob,
                'ComplainantGenderType' => $request->complainant_gender,
                'ComplainantAddress' => $request->complainant_address,
                'ComplainantOtherDetails' => $request->complainant_relation,
            ]);

            // Save Accused Data
            AccusedDetail::create([
                'CaseID' => $case->CaseID,
                'AccusedName' => $request->accused_name,
                'AccusedContact' => $request->accused_contact,
                'AccusedID' => $request->accused_id_number,
                'AccusedDateOfBirth' => $request->accused_dob,
                'AccusedGenderType' => $request->accused_gender,
                'AccusedAddress' => $request->accused_address,
                'AccusedOtherDetails' => $request->accused_relation,
            ]);



            TaskLog::create([
                'case_id' => $case->CaseID,
                'officer_id' => auth()->id(), // Authenticated user's ID
                'officer_name' => auth()->user()->name, // Authenticated user's name
                'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
                'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
                'date' => now(), // Current date and time
                'description' => 'Case added by officer', // Static description
                'action_taken' => 'added', // Static action
            ]);


            CaseUser::create([
                'case_id' => $case->CaseID,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('casess.index')->with('success', 'Case added successfully.');
        } catch (\Exception $e) {

            return redirect()->route('casess.index')->with('false', $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Fetch the case details by ID
        $case = NewCaseManagement::findOrFail($id);
      


        // Fetch related complainant and accused details
        $complainant = ComplainantDetail::where('CaseID', $id)->first();
        $accused = AccusedDetail::where('CaseID', $id)->first();

        // Fetch dropdown data
        $caseTypes = CaseType::all(); // Fetch all case types
        $departments = Department::all(); // Fetch all departments
        $officers = User::all(); // Fetch all officers

        // Fetch documents related to the case
        $documents = InvestigationDocument::where('case_id', $id)->get();

        $courtProceedings = CourtProceeding::where('case_id', $id)->get();
        $evidences = Evidence::where('case_id', $id)->get();
        $witnesses = Witness::where('case_id', $id)->with('files')->get();

        $administrativeUnits = AdministrativeUnit::all();
        $subdivisions = Subdivision::where('administrative_unit_id', $case->administrative_unit_id)->get();
        $policeStations = PoliceStation::where('subdivision_id', $case->subdivision_id)->get();
        $taskLogs = TaskLog::where('case_id', $case->CaseID)->orderBy('date', 'desc')->paginate(5);


        // Pass data to the view
        return view('case-management.edit', compact(
            'case',
            'complainant',
            'accused',
            'caseTypes',
            'departments',
            'officers',
            'documents',
            'courtProceedings',
            'evidences',
            'witnesses',
            'administrativeUnits',
            'subdivisions',
            'policeStations',
            'taskLogs'
        ));
    }

    public function destroy($id)
    {
        // Fetch the case by ID
        $case = NewCaseManagement::findOrFail($id);

        // Delete the case
        $case->delete();



        // Redirect back with a success message
        return redirect()->route('casess.index')->with('success', 'Case deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $request->validate([
                'case_type' => 'required|string|max:50',
                'case_status' => 'required|string|max:50',
                'department' => 'required|integer|exists:departments,id',
                'case_description' => 'nullable|string',
                'administrative_unit' => 'required|integer|exists:administrative_units,id',
                'subdivision' => 'required|integer|exists:subdivisions,id',
                'police_station' => 'required|integer|exists:police_stations,id',
                'complainant_name' => 'required|string|max:50',
                'complainant_contact' => 'required|string|max:50',
                'complainant_id_number' => 'required|string|max:50',
                'complainant_dob' => 'required|date',
                'complainant_gender' => 'required|string|max:50',
                'complainant_address' => 'nullable|string|max:100',
                'complainant_relation' => 'nullable|string|max:50',
                'accused_name' => 'required|string|max:50',
                'accused_contact' => 'required|string|max:50',
                'accused_id_number' => 'required|string|max:50',
                'accused_dob' => 'required|date',
                'accused_gender' => 'required|string|max:50',
                'accused_address' => 'nullable|string|max:100',
                'accused_relation' => 'nullable|string|max:50',
            ]);

            // Fetch the case by ID
            $case = NewCaseManagement::findOrFail($id);

            // Update the case details
            $department = Department::findOrFail($request->department);
            $officer = User::findOrFail($request->officer);

            $case->update([
                'CaseType' => $request->case_type,
                'CaseStatus' => $request->case_status,
                'CaseDescription' => $request->case_description,
                'CaseDepartmentID' => $request->department,
                'CaseDepartmentName' => $department->name,
                'OfficerID' => $request->officer,
                'OfficerName' => $officer->name,
                'OfficerRank' => $officer->designation_id,
                'administrative_unit_id' => $request->administrative_unit,
                'subdivision_id' => $request->subdivision,
                'police_station_id' => $request->police_station,
            ]);

            // Update complainant details
            $complainant = ComplainantDetail::where('CaseID', $id)->first();
            if ($complainant) {
                $complainant->update([
                    'ComplainantName' => $request->complainant_name,
                    'ComplainantContact' => $request->complainant_contact,
                    'ComplainantID' => $request->complainant_id_number,
                    'ComplainantDateOfBirth' => $request->complainant_dob,
                    'ComplainantGenderType' => $request->complainant_gender,
                    'ComplainantAddress' => $request->complainant_address,
                    'ComplainantOtherDetails' => $request->complainant_relation,
                ]);
            }

            // Update accused details
            $accused = AccusedDetail::where('CaseID', $id)->first();
            if ($accused) {
                $accused->update([
                    'AccusedName' => $request->accused_name,
                    'AccusedContact' => $request->accused_contact,
                    'AccusedID' => $request->accused_id_number,
                    'AccusedDateOfBirth' => $request->accused_dob,
                    'AccusedGenderType' => $request->accused_gender,
                    'AccusedAddress' => $request->accused_address,
                    'AccusedOtherDetails' => $request->accused_relation,
                ]);
            }

            // Log the update action
            TaskLog::create([
                'case_id' => $case->CaseID,
                'officer_id' => auth()->id(), // Authenticated user's ID
                'officer_name' => auth()->user()->name, // Authenticated user's name
                'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
                'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
                'date' => now(), // Current date and time
                'description' => 'Case updated by officer', // Static description
                'action_taken' => 'updated', // Static action
            ]);

            // Redirect back with a success message
            return redirect()->route('casess.index')->with('success', 'Case updated successfully.');
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->route('casess.index')->with('error', $e->getMessage());
        }
    }


    public function getCaseOfficers(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('Case Officer'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }

    public function getinvestigationOfficers(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('Investigation Officer'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }

    public function getseniorinvestigationOfficers(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('Senior Investigation Officer / Inspector'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }

    public function getStationSergeants(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('Station Sergeant'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }

    public function getSubdivisionalOfficer(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('Sub-Divisional Officer'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }


    public function getCommanders(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('Commander of Division'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }

    public function getDppPca(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('DPP / PCA'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }

    public function getLegalTeamOfficers(Request $request)
    {
        $administrativeUnitId = $request->administrative_unit_id;
        $subdivisionId = $request->subdivision_id;
        $policeStationId = $request->police_station_id;

        // Fetch officers based on the hierarchy
        $query = User::role('Legal Team Officer'); // Assuming 'Case Officer' is the role name

        if (!empty($policeStationId) && !empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Police Station, Subdivision, and Administrative Unit
            $query->where('police_station_id', $policeStationId)
                ->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($subdivisionId) && !empty($administrativeUnitId)) {
            // Match by Subdivision and Administrative Unit
            $query->where('subdivision_id', $subdivisionId)
                ->where('administrative_unit_id', $administrativeUnitId);
        } elseif (!empty($administrativeUnitId)) {
            // Match by Administrative Unit only
            $query->where('administrative_unit_id', $administrativeUnitId);
        }

        // Fetch the officers
        $officers = $query->get(['id', 'name']);

        return response()->json($officers);
    }

    public function getHelpDeskUsers($caseId)
    {
        // Fetch users from case_users table where role is 'Help Desk'
        $helpDeskUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Police Officer / Help Desk Officer'); // Role name
        })
        ->whereHas('caseUsers', function ($query) use ($caseId) {
            $query->where('case_id', $caseId);
        })
        ->get(['id', 'name']);

       
    
        return response()->json($helpDeskUsers);
    }

    public function takeAction(Request $request, $id)
    {

        if ($request->change_status == 'closed' ||
         $request->change_status == 'Case Resolved – Released'||
         $request->change_status == 'Case Resolved - Convicted' ||
         $request->change_status == 'Case Closed on Court Order'  ) {

 
            try {
                
                // Validate the request
                $request->validate([
                    'change_status' => 'required',
                    'case_description_action' => 'nullable|string',
                ]);
                // Fetch the case by ID
                $case = NewCaseManagement::findOrFail($id);

                // Update the case status and description
                $case->CaseStatus = $request->change_status;
                $case->CaseDescription = $request->case_description_action;

                // Save the changes
                $case->save();


                // Log the update action
                TaskLog::create([
                    'case_id' => $case->CaseID,
                    'officer_id' => auth()->id(), // Authenticated user's ID
                    'officer_name' => auth()->user()->name, // Authenticated user's name
                    'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
                    'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
                    'date' => now(), // Current date and time
                    'description' => 'Case status updated by officer', // Static description
                    'action_taken' => 'updated', // Static action
                ]);


                // Redirect back with a success message
                return redirect()->back()->with('success', 'Case status updated successfully.');
            } catch (\Exception $e) {
                // Redirect back with an error message
                return redirect()->back()->with('error', 'Failed to update case status: ' . $e->getMessage());
            }
        } else if($request->change_status=='open' ||$request->change_status=='Approved'  ||$request->change_status=='CaseApproved - Charged'   ) {

     
            try {
                // Validate the request
                $request->validate([
                    'forward_to' => 'required|integer|exists:users,id',
                    'case_description_action' => 'nullable|string',
                ]);

                // Fetch the case by ID
                $case = NewCaseManagement::findOrFail($id);
                $case->CaseStatus = $request->change_status;
                // Update the LastOfficerID with the current OfficerID
                $case->LastOfficerID = $case->OfficerID;
                $forwardedOfficer = User::findOrFail($request->forward_to);

                // Update the OfficerID with the selected Forward To officer
                $case->OfficerID = $forwardedOfficer->id; // Update OfficerID with the forwarded officer's ID
                $case->OfficerName = $forwardedOfficer->name;
                $case->CaseDescription = $request->case_description_action;

                $user=User::findOrFail($case->OfficerID);
            $message = "Dear {$case->OfficerName}, case {$case->CaseID}, is assinged to you successfully.";
            $user->notify(new UserNotification($message));

                // Save the changes
                $case->save();
                CaseUser::firstOrCreate(
                    [
                        'case_id' => $case->CaseID,
                        'user_id' => $request->forward_to,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Log the update action
                TaskLog::create([
                    'case_id' => $case->CaseID,
                    'officer_id' => auth()->id(), // Authenticated user's ID
                    'officer_name' => auth()->user()->name, // Authenticated user's name
                    'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
                    'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
                    'date' => now(), // Current date and time
                    'description' => 'Case forwarded by officer', // Static description
                    'action_taken' => 'updated', // Static action
                ]);


                // Redirect back with a success message
                return redirect()->back()->with('success', 'Case forwarded successfully.');
            } catch (\Exception $e) {
                // Redirect back with an error message
                return redirect()->back()->with('error', 'Failed to forward case: ' . $e->getMessage());
            }

        }else{
            try {
                // Validate the request
                $request->validate([
                    'forward_to' => 'required|integer|exists:users,id',
                    'case_description_action' => 'nullable|string',
                ]);

                // Fetch the case by ID
                $case = NewCaseManagement::findOrFail($id);

                // Update the LastOfficerID with the current OfficerID
                $case->LastOfficerID = $case->OfficerID;
                $forwardedOfficer = User::findOrFail($request->forward_to);

                // Update the OfficerID with the selected Forward To officer
                $case->OfficerID = $forwardedOfficer->id; // Update OfficerID with the forwarded officer's ID
                $case->OfficerName = $forwardedOfficer->name;
                $case->CaseDescription = $request->case_description_action;

                $user=User::findOrFail($case->OfficerID);

                // Save the changes
                $case->save();

                 $message = "Dear {$case->OfficerName}, case {$case->CaseID}, is assinged to you successfully.";
                 $user->notify(new UserNotification($message));

                CaseUser::firstOrCreate(
                    [
                        'case_id' => $case->CaseID,
                        'user_id' => $request->forward_to,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );


                // Log the update action
                TaskLog::create([
                    'case_id' => $case->CaseID,
                    'officer_id' => auth()->id(), // Authenticated user's ID
                    'officer_name' => auth()->user()->name, // Authenticated user's name
                    'officer_rank' => auth()->user()->designation->name ?? 'N/A', // Assuming a relationship exists
                    'department' => auth()->user()->department->name ?? 'N/A', // Assuming a relationship exists
                    'date' => now(), // Current date and time
                    'description' => 'Case forwarded by officer', // Static description
                    'action_taken' => 'updated', // Static action
                ]);


                // Redirect back with a success message
                return redirect()->back()->with('success', 'Case forwarded successfully.');
            } catch (\Exception $e) {
                // Redirect back with an error message
                return redirect()->back()->with('error', 'Failed to forward case: ' . $e->getMessage());
            }
        }
    }



    public function generatePdf($caseId)
    {
        // Fetch the case details
        $case =NewCaseManagement ::with(['officer', 'department', 'administrativeUnit', 'subdivision', 'policeStation'])->findOrFail($caseId);

        // Pass the case details to the PDF view
        $pdf = Pdf::loadView('pdf.case-details', compact('case'));

        // Return the generated PDF
        return $pdf->download('case-details.pdf');
    }
}
