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

        // Filter by entity - users can only see their entity cases, SuperAdmin sees all
        if (!$user->hasRole('SuperAdmin')) {
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
            } else {
                // If user has no entity, show no data
                $query->whereRaw('1 = 0');
                $hearingQuery->whereRaw('1 = 0');
                $noticeQuery->whereRaw('1 = 0');
            }
        }
        // SuperAdmin can see all cases, so no filter applied

        // Total Statistics
        $totalCases = $query->count();
        $openCases = (clone $query)->where('status', 'Open')->count();
        $closedCases = (clone $query)->where('status', 'Closed')->count();
        $totalNotices = $noticeQuery->count();
        $totalHearings = $hearingQuery->count();
        
        // Recent Cases (max 5)
        $recentCases = (clone $query)->with('entity')->orderBy('created_at', 'DESC')->limit(5)->get();
        
        // Upcoming Hearings (next 7 days, max 5)
        $upcomingHearings = (clone $hearingQuery)->with('courtCase')
            ->where(function($q) {
                $q->where('next_hearing_date', '>=', now())
                  ->orWhere('hearing_date', '>=', now());
            })
            ->where(function($q) {
                $q->where('next_hearing_date', '<=', now()->addDays(7))
                  ->orWhere('hearing_date', '<=', now()->addDays(7));
            })
            ->orderBy('hearing_date', 'ASC')
            ->limit(5)
            ->get();
        
        // Recent Notices (max 5)
        $recentNotices = (clone $noticeQuery)->with('courtCase')
            ->orderBy('notice_date', 'DESC')
            ->limit(5)
            ->get();
        
        // Case Status Distribution
        $statusDistribution = [
            'Open' => (clone $query)->where('status', 'Open')->count(),
            'Closed' => (clone $query)->where('status', 'Closed')->count(),
        ];
        
        // Court Type Distribution
        $courtTypeDistribution = (clone $query)->select('court_type', DB::raw('count(*) as total'))
            ->groupBy('court_type')
            ->pluck('total', 'court_type')
            ->toArray();
        
        // Entity-wise Cases (only show if SuperAdmin, otherwise show only user's entity)
        if ($user->hasRole('SuperAdmin')) {
            $entityCases = CourtCase::select('entity_id', DB::raw('count(*) as total'))
                ->whereNotNull('entity_id')
                ->with('entity')
                ->groupBy('entity_id')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->entity->name ?? 'Unknown',
                        'total' => $item->total
                    ];
                });
        } else {
            // For regular users, show only their entity
            if ($user->entity_id) {
                $entityTotal = (clone $query)->count();
                $entityCases = collect([[
                    'name' => $user->entity->name ?? 'Unknown',
                    'total' => $entityTotal
                ]]);
            } else {
                $entityCases = collect([]);
            }
        }
        
        // Monthly Case Trends (last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthQuery = clone $query;
            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'count' => $monthQuery->whereYear('created_at', $date->year)
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
        $casesThisMonth = (clone $query)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Cases added last month
        $casesLastMonth = (clone $query)->whereMonth('created_at', now()->subMonth()->month)
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
            'entityCases',
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
