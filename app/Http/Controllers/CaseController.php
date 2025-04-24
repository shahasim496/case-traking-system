<?php
namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\TaskLog;
use App\Models\Witness;
use App\Models\CaseType;
use App\Models\Evidence;
use App\Models\Department;
use App\Models\Subdivision;
use Illuminate\Http\Request;
use App\Models\AccusedDetail;
use App\Models\PoliceStation;
use App\Models\CaseManagement;
use App\Models\CourtProceeding;
use App\Models\ComplainantDetail;
use App\Models\NewCaseManagement;
use App\Models\AdministrativeUnit;
use App\Models\InvestigationDocument;

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
      

        return view('case-management.createcase', compact('caseTypes', 'officers', 'departments','administrativeUnits'));
    }

    public function index()
    {
        // Fetch all cases ordered by the latest created_at
        $cases = NewCaseManagement::orderBy('created_at', 'desc')->get();
        

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
    $taskLogs = TaskLog::where('case_id', $case->CaseID)->orderBy('date', 'desc')->paginate(10);


        // Pass data to the view
        return view('case-management.edit', compact('case', 'complainant', 'accused', 'caseTypes', 'departments', 
        'officers', 'documents', 'courtProceedings','evidences','witnesses','administrativeUnits','subdivisions','policeStations','taskLogs'));
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

    public function storeTaskLog(Request $request, $caseId)
    {
        $request->validate([
            'officer_id' => 'required|exists:users,id',
            'officer_name' => 'required|string|max:255',
            'officer_rank' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date' => 'required|date',
            'forwarded_to' => 'nullable|string|max:255',
            'action_taken' => 'required|string',
        ]);

        TaskLog::create([
            'case_id' => $caseId,
            'officer_id' => $request->officer_id,
            'officer_name' => $request->officer_name,
            'officer_rank' => $request->officer_rank,
            'department' => $request->department,
            'date' => $request->date,
            'forwarded_to' => $request->forwarded_to,
            'action_taken' => $request->action_taken,
        ]);

        return redirect()->back()->with('success', 'Task log added successfully.');
    }
}