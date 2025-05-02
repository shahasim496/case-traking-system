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

    
        // Pass the data to the view
        return view('dashboard.admin', compact('totalCases', 'pendingCases', 'resolvedCases', 'totalUsers', 'pendingPercentage', 'resolvedPercentage', 'case_to_court', 'case_to_court_percentage','ClosedCases','Cases'));
    }

    public function getGroupWiseReport($group_id, $user_id = -1)
    {

        // $group_service = Group_Service::where('id',$group_id)->first();
// return $group_services[0]->group_grade;

        $reports = DB::select(
            'CALL group_wise_report_report_views("' . $group_id . '","' . $user_id . '")'
        );
        return $reports[0];

    }//end of function

    public function getTotalCadreReport()
    {

        $reports = DB::select(
            'CALL total_cadre_view()'
        );
        return $reports[0];

    }//end of function

}
