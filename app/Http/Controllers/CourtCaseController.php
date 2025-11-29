<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CourtCase;
use App\Models\Notice;
use App\Models\Hearing;
use App\Models\Party;
use App\Models\Department;

class CourtCaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the cases.
     */
    public function index(Request $request)
    {
        $query = CourtCase::query();

        // Filter by department - users can only see their department cases, SuperAdmin sees all
        $user = Auth::user();
        if (!$user->hasRole('SuperAdmin')) {
            // Regular users can only see cases from their department
            if ($user->department_id) {
                $query->where('department_id', $user->department_id);
            } else {
                // If user has no department, show no cases
                $query->whereRaw('1 = 0');
            }
        }
        // SuperAdmin can see all cases, so no filter applied

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('case_title', 'like', "%{$search}%")
                  ->orWhereHas('parties', function($partyQuery) use ($search) {
                      $partyQuery->where('party_name', 'like', "%{$search}%")
                                 ->orWhere('party_details', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by court type
        if ($request->has('court_type') && $request->court_type) {
            $query->where('court_type', $request->court_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by department (only for SuperAdmin or if they want to filter further)
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $cases = $query->with(['department', 'notices', 'parties'])->orderBy('created_at', 'DESC')->paginate(10);

        return view('cases.index', compact('cases'));
    }

    /**
     * Show the form for creating a new case.
     */
    public function create()
    {
        $departments = Department::all();
        return view('cases.create', compact('departments'));
    }

    /**
     * Store a newly created case in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'case_number' => 'required|string|max:100|unique:cases,case_number',
            'court_type' => 'required|in:High Court,Supreme Court,Session Court',
            'case_title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:Open,Closed',
            'parties' => 'required|array|min:1',
            'parties.*.party_name' => 'required|string|max:255',
            'parties.*.party_details' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['created_by'] = auth()->id();
            $validatedData['updated_by'] = auth()->id();
            
            // Remove parties from validated data before creating case
            $parties = $validatedData['parties'];
            unset($validatedData['parties']);

            $case = CourtCase::create($validatedData);

            // Create parties
            foreach ($parties as $partyData) {
                Party::create([
                    'case_id' => $case->id,
                    'party_name' => $partyData['party_name'],
                    'party_details' => $partyData['party_details'] ?? null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('cases.index')->with('success', 'Case created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified case.
     */
    public function show($id)
    {
        $case = CourtCase::with(['notices', 'hearings', 'department', 'parties'])->findOrFail($id);
        
        // Check if user has access to this case
        $user = Auth::user();
        if (!$user->hasRole('SuperAdmin')) {
            // Regular users can only view cases from their department
            if ($user->department_id && $case->department_id != $user->department_id) {
                abort(403, 'You do not have permission to view this case.');
            } elseif (!$user->department_id) {
                abort(403, 'You do not have permission to view this case.');
            }
        }
        
        // Get upcoming hearings
        $upcomingHearings = Hearing::where('case_id', $id)
            ->where(function($query) {
                $query->where('next_hearing_date', '>=', now())
                      ->orWhere('hearing_date', '>=', now());
            })
            ->orderBy('hearing_date', 'ASC')
            ->get();

        // Get recent notices
        $recentNotices = Notice::where('case_id', $id)
            ->orderBy('notice_date', 'DESC')
            ->limit(5)
            ->get();

        return view('cases.show', compact('case', 'upcomingHearings', 'recentNotices'));
    }

    /**
     * Show the form for editing the specified case.
     */
    public function edit($id)
    {
        $case = CourtCase::with('parties')->findOrFail($id);
        
        // Check if user has access to this case
        $user = Auth::user();
        if (!$user->hasRole('SuperAdmin')) {
            // Regular users can only edit cases from their department
            if ($user->department_id && $case->department_id != $user->department_id) {
                abort(403, 'You do not have permission to edit this case.');
            } elseif (!$user->department_id) {
                abort(403, 'You do not have permission to edit this case.');
            }
        }
        
        $departments = Department::all();
        return view('cases.edit', compact('case', 'departments'));
    }

    /**
     * Update the specified case in storage.
     */
    public function update(Request $request, $id)
    {
        $case = CourtCase::findOrFail($id);
        
        // Check if user has access to this case
        $user = Auth::user();
        if (!$user->hasRole('SuperAdmin')) {
            // Regular users can only update cases from their department
            if ($user->department_id && $case->department_id != $user->department_id) {
                abort(403, 'You do not have permission to update this case.');
            } elseif (!$user->department_id) {
                abort(403, 'You do not have permission to update this case.');
            }
        }

        $validatedData = $request->validate([
            'case_number' => 'required|string|max:100|unique:cases,case_number,' . $id,
            'court_type' => 'required|in:High Court,Supreme Court,Session Court',
            'case_title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:Open,Closed',
            'parties' => 'required|array|min:1',
            'parties.*.party_name' => 'required|string|max:255',
            'parties.*.party_details' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['updated_by'] = auth()->id();
            
            // Remove parties from validated data before updating case
            $parties = $validatedData['parties'];
            unset($validatedData['parties']);

            $case->update($validatedData);

            // Delete existing parties
            $case->parties()->delete();

            // Create new parties
            foreach ($parties as $partyData) {
                Party::create([
                    'case_id' => $case->id,
                    'party_name' => $partyData['party_name'],
                    'party_details' => $partyData['party_details'] ?? null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('cases.index')->with('success', 'Case updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified case from storage.
     */
    public function destroy($id)
    {
        $case = CourtCase::findOrFail($id);
        
        // Check if user has access to this case
        $user = Auth::user();
        if (!$user->hasRole('SuperAdmin')) {
            // Regular users can only delete cases from their department
            if ($user->department_id && $case->department_id != $user->department_id) {
                abort(403, 'You do not have permission to delete this case.');
            } elseif (!$user->department_id) {
                abort(403, 'You do not have permission to delete this case.');
            }
        }

        DB::beginTransaction();

        try {
            // Delete related notices attachments
            foreach ($case->notices as $notice) {
                if ($notice->attachment) {
                    Storage::disk('public')->delete($notice->attachment);
                }
            }

            // Delete related hearings court orders
            foreach ($case->hearings as $hearing) {
                if ($hearing->court_order) {
                    Storage::disk('public')->delete($hearing->court_order);
                }
            }

            $case->delete();

            DB::commit();

            return redirect()->route('cases.index')->with('success', 'Case deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a notice for a case.
     */
    public function storeNotice(Request $request, $caseId)
    {
        $case = CourtCase::findOrFail($caseId);

        // Check if case is closed
        if ($case->status == 'Closed') {
            return redirect()->back()->with('error', 'Cannot add notice to a closed case.');
        }

        $validatedData = $request->validate([
            'notice_date' => 'required|date',
            'notice_details' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['case_id'] = $caseId;
            $validatedData['created_by'] = auth()->id();
            $validatedData['updated_by'] = auth()->id();

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('notices', $filename, 'public');
                $validatedData['attachment'] = $path;
            }

            Notice::create($validatedData);

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Notice added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Store a hearing for a case.
     */
    public function storeHearing(Request $request, $caseId)
    {
        $case = CourtCase::findOrFail($caseId);

        // Check if case is closed
        if ($case->status == 'Closed') {
            return redirect()->back()->with('error', 'Cannot add hearing to a closed case.');
        }

        $validatedData = $request->validate([
            'hearing_date' => 'required|date',
            'purpose' => 'nullable|string',
            'person_appearing' => 'nullable|string|max:255',
            'outcome' => 'nullable|string',
            'court_order' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'next_hearing_date' => 'nullable|date|after:hearing_date',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['case_id'] = $caseId;
            $validatedData['created_by'] = auth()->id();
            $validatedData['updated_by'] = auth()->id();

            // Handle file upload
            if ($request->hasFile('court_order')) {
                $file = $request->file('court_order');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('court_orders', $filename, 'public');
                $validatedData['court_order'] = $path;
            }

            Hearing::create($validatedData);

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Hearing added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}

