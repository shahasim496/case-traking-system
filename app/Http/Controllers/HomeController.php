<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CourtCase;
use App\Models\Notice;
use App\Models\Hearing;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Group_Service;
use Carbon\Carbon;

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
        // Total Statistics
        $totalCases = CourtCase::count();
        $openCases = CourtCase::where('status', 'Open')->count();
        $closedCases = CourtCase::where('status', 'Closed')->count();
        $totalNotices = Notice::count();
        $totalHearings = Hearing::count();
        
        // Recent Cases (last 10)
        $recentCases = CourtCase::with('department')->orderBy('created_at', 'DESC')->limit(10)->get();
        
        // Upcoming Hearings (next 7 days)
        $upcomingHearings = Hearing::with('courtCase')
            ->where(function($query) {
                $query->where('next_hearing_date', '>=', now())
                      ->orWhere('hearing_date', '>=', now());
            })
            ->where(function($query) {
                $query->where('next_hearing_date', '<=', now()->addDays(7))
                      ->orWhere('hearing_date', '<=', now()->addDays(7));
            })
            ->orderBy('hearing_date', 'ASC')
            ->limit(10)
            ->get();
        
        // Recent Notices (last 10)
        $recentNotices = Notice::with('courtCase')
            ->orderBy('notice_date', 'DESC')
            ->limit(10)
            ->get();
        
        // Case Status Distribution
        $statusDistribution = [
            'Open' => CourtCase::where('status', 'Open')->count(),
            'Closed' => CourtCase::where('status', 'Closed')->count(),
        ];
        
        // Court Type Distribution
        $courtTypeDistribution = CourtCase::select('court_type', DB::raw('count(*) as total'))
            ->groupBy('court_type')
            ->pluck('total', 'court_type')
            ->toArray();
        
        // Department-wise Cases
        $departmentCases = CourtCase::select('department_id', DB::raw('count(*) as total'))
            ->whereNotNull('department_id')
            ->with('department')
            ->groupBy('department_id')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->department->name ?? 'Unknown',
                    'total' => $item->total
                ];
            });
        
        // Monthly Case Trends (last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'count' => CourtCase::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }
        
        // Cases by Status (for pie chart)
        $casesByStatus = [
            'Open Cases' => $openCases,
            'Closed Cases' => $closedCases,
        ];
        
        // Cases added this month
        $casesThisMonth = CourtCase::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Cases added last month
        $casesLastMonth = CourtCase::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        // Calculate percentage change
        $monthlyChange = $casesLastMonth > 0 
            ? round((($casesThisMonth - $casesLastMonth) / $casesLastMonth) * 100, 1)
            : ($casesThisMonth > 0 ? 100 : 0);

        return view('dashboard.admin', compact(
            'totalCases',
            'openCases',
            'closedCases',
            'totalNotices',
            'totalHearings',
            'recentCases',
            'upcomingHearings',
            'recentNotices',
            'statusDistribution',
            'courtTypeDistribution',
            'departmentCases',
            'monthlyTrends',
            'casesByStatus',
            'casesThisMonth',
            'casesLastMonth',
            'monthlyChange'
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
