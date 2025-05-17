<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Group_Service;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('banned');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            return redirect()->route('admin_dashboard');
      
    }

    public function user_dashboard()
    {
        return view('home');
    }//end of function

    public function admin_dashboard()
    {
        // Fetch total cases
        $totalCases = \App\Models\NewCaseManagement::count();
    
        // Fetch pending cases
        $pendingCases = \App\Models\NewCaseManagement::where('CaseStatus', 'Awating Verification')->count();
        $ClosedCases = \App\Models\NewCaseManagement::where('CaseStatus', 'Closed')->count();
    
        // Fetch resolved cases
        $resolvedCases = \App\Models\NewCaseManagement::where('CaseStatus', 'Case Resolved – Released')->count();
        $case_to_court = \App\Models\NewCaseManagement::where('CaseStatus', 'CaseApproved - Charged ')->count();
        // Fetch total users
        $totalUsers = \App\Models\User::count();
        $Cases = \App\Models\NewCaseManagement::all();
    
        // Calculate percentages
        $pendingPercentage = $totalCases > 0 ? round(($pendingCases / $totalCases) * 100, 2) : 0;
        $resolvedPercentage = $totalCases > 0 ? round(($resolvedCases / $totalCases) * 100, 2) : 0;
        $case_to_court_percentage = $totalCases > 0 ? round(($case_to_court / $totalCases) * 100, 2) : 0;

        // Evidence Statistics
        $totalEvidence = \App\Models\Evidence::count();
        $pendingEvidence = \App\Models\Evidence::where('status', 'pending')->count();
        $verifiedEvidence = \App\Models\Evidence::where('status', 'verified')->count();
        $completedEvidence = \App\Models\Evidence::where('status', 'completed')->count();
        
        // Evidence by types
        $dnaEvidence = \App\Models\Evidence::where('type', 'dna')->count();
        $ballisticsEvidence = \App\Models\Evidence::where('type', 'Ballistics')->count();
        $currencyEvidence = \App\Models\Evidence::where('type', 'Currency')->count();
        $toxicologyEvidence = \App\Models\Evidence::where('type', 'Toxicology')->count();
        $videoEvidence = \App\Models\Evidence::where('type', 'Video Evidence')->count();
        $questionedEvidence = \App\Models\Evidence::where('type', 'questioned')->count();
        $generalEvidence = \App\Models\Evidence::where('type', 'general')->count();
        
        // Get recent evidence records
        $recentEvidence = \App\Models\Evidence::with(['evoOfficer'])->orderBy('created_at', 'desc')->take(5)->get();

        // Pass the data to the view
        return view('dashboard.admin', compact(
            'totalCases', 'pendingCases', 'resolvedCases', 'totalUsers', 
            'pendingPercentage', 'resolvedPercentage', 'case_to_court', 
            'case_to_court_percentage', 'ClosedCases', 'Cases',
            'totalEvidence', 'pendingEvidence', 'verifiedEvidence', 'completedEvidence',
            'dnaEvidence', 'ballisticsEvidence', 'currencyEvidence', 'toxicologyEvidence',
            'videoEvidence', 'questionedEvidence', 'generalEvidence', 'recentEvidence'
        ));
    }

 

    public function getTotalCadreReport()
    {

        $reports = DB::select(
            'CALL total_cadre_view()'
        );
        return $reports[0];

    }//end of function

}
