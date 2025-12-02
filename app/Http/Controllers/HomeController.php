<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CourtCase;
use App\Models\Notice;
use App\Models\Hearing;
use App\Models\Entity;
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
        $user = Auth::user();
        $query = CourtCase::query();
        $hearingQuery = Hearing::query();
        $noticeQuery = Notice::query();
        $userQuery = User::query();

        // Filter by entity - users can only see their entity cases, SuperAdmin sees all
        if (!$user->hasRole('SuperAdmin')) {
            // Legal Officers can only see cases they created or are assigned to
            if ($user->hasRole('Legal Officer')) {
                $query->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhere('assigned_officer_id', $user->id);
                });
            } else {
                // Regular users can only see cases from their entity
                if ($user->entity_id) {
                    $query->where('entity_id', $user->entity_id);
                    
                    // Filter hearings and notices by case entity
                    $hearingQuery->whereHas('courtCase', function($q) use ($user) {
                        $q->where('entity_id', $user->entity_id);
                    });
                    
                    $noticeQuery->whereHas('courtCase', function($q) use ($user) {
                        $q->where('entity_id', $user->entity_id);
                    });
                    
                    // Filter users by entity
                    $userQuery->where('entity_id', $user->entity_id);
                } else {
                    // If user has no entity, show no data
                    $query->whereRaw('1 = 0');
                    $hearingQuery->whereRaw('1 = 0');
                    $noticeQuery->whereRaw('1 = 0');
                    $userQuery->whereRaw('1 = 0');
                }
            }
        }
        // SuperAdmin can see all cases, so no filter applied

        // Total Statistics
        $totalUsers = $userQuery->count();
        $totalCases = $query->count();
        $openCases = (clone $query)->where('status', 'Open')->count();
        $closedCases = (clone $query)->where('status', 'Closed')->count();
        $totalNotices = $noticeQuery->count();
        $totalHearings = $hearingQuery->count();
        
        // Calculate percentages
        $pendingCases = $openCases; // Open cases are pending
        $pendingPercentage = $totalCases > 0 ? round(($pendingCases / $totalCases) * 100, 1) : 0;
        $resolvedCases = 0; // No resolved status in court system, using closed as resolved
        $resolvedPercentage = $totalCases > 0 ? round(($closedCases / $totalCases) * 100, 1) : 0;
        $case_to_court = $totalCases; // All cases are court cases
        $case_to_court_percentage = 100;
        $ClosedCases = $closedCases;
        
        // Recent Cases for table (paginated, 10 per page)
        $Cases = (clone $query)->with(['entity', 'caseType'])->orderBy('created_at', 'DESC')->paginate(10);
        
        // Cases by Entity/Department (for doughnut chart)
        if ($user->hasRole('SuperAdmin')) {
            $casesByType = CourtCase::select('entity_id', DB::raw('count(*) as total'))
                ->whereNotNull('entity_id')
                ->with('entity')
                ->groupBy('entity_id')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->entity->name ?? 'Unknown' => $item->total];
                })
                ->toArray();
        } else {
            if ($user->entity_id) {
                $entityTotal = (clone $query)->count();
                $casesByType = [$user->entity->name ?? 'Unknown' => $entityTotal];
            } else {
                $casesByType = [];
            }
        }
        
        // Cases by Court (for bar chart) - use the same filtered query
        $casesByCourt = (clone $query)->select('court_id', DB::raw('count(*) as total'))
            ->whereNotNull('court_id')
            ->with('court')
            ->groupBy('court_id')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->court->name ?? 'Unknown' => $item->total];
            })
            ->toArray();
        
        // If no court data, provide empty array
        if (empty($casesByCourt)) {
            $casesByCourt = [];
        }
        
        // All Courts with case counts for stat card - use court name from relationship
        $casesByCourt = (clone $query)->select('court_id', DB::raw('count(*) as total'))
            ->whereNotNull('court_id')
            ->with('court')
            ->groupBy('court_id')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->court->name ?? 'Unknown',
                    'total' => $item->total
                ];
            })
            ->toArray();
        
        // Get all courts to ensure all are shown even if 0 cases
        $allCourts = \App\Models\Court::all()->keyBy('id');
        $topCourts = [];
        
        // First, add courts that have cases
        foreach ($casesByCourt as $court) {
            $topCourts[$court['name']] = $court['total'];
        }
        
        // Then, add courts with 0 cases
        foreach ($allCourts as $court) {
            if (!isset($topCourts[$court->name])) {
                $topCourts[$court->name] = 0;
            }
        }
        
        // Convert to array format and sort by total descending
        $topCourts = collect($topCourts)->map(function($total, $name) {
            return ['name' => $name, 'total' => $total];
        })->sortByDesc('total')->values()->toArray();
        
        // Monthly Case Trends (last 6 months) - formatted for chart
        $monthlyCases = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthQuery = clone $query;
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $monthTotal = $monthQuery->whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthOpen = (clone $query)->where('status', 'Open')
                ->whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthClosed = (clone $query)->where('status', 'Closed')
                ->whereBetween('created_at', [$monthStart, $monthEnd])->count();
            
            $monthlyCases[] = [
                'month' => $date->format('Y-m'),
                'total_cases' => $monthTotal,
                'pending_cases' => $monthOpen,
                'resolved_cases' => $monthClosed,
                'court_cases' => $monthTotal, // All are court cases
            ];
        }

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalCases',
            'openCases',
            'closedCases',
            'pendingCases',
            'pendingPercentage',
            'resolvedCases',
            'resolvedPercentage',
            'case_to_court',
            'case_to_court_percentage',
            'ClosedCases',
            'totalNotices',
            'totalHearings',
            'Cases',
            'casesByCourt',
            'topCourts',
            'casesByType',
            'monthlyCases'
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

