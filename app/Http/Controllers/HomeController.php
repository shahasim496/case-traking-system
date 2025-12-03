<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $resolvedCases = (clone $query)->where('status', 'Resolved')->count();
        $favourCases = (clone $query)->where('status', 'Resolved')->where('resolution_outcome', 'Favour')->count();
        $againstCases = (clone $query)->where('status', 'Resolved')->where('resolution_outcome', 'Against')->count();
        $closedCases = $resolvedCases; // For backward compatibility
        
        // Cases with hearings in next 7 days
        $nextWeekStart = now()->startOfDay();
        $nextWeekEnd = now()->addDays(7)->endOfDay();
        
        $nextWeekCases = (clone $query)->whereHas('hearings', function($q) use ($nextWeekStart, $nextWeekEnd) {
            $q->where(function($query) use ($nextWeekStart, $nextWeekEnd) {
                $query->whereBetween('hearing_date', [$nextWeekStart, $nextWeekEnd])
                      ->orWhereBetween('next_hearing_date', [$nextWeekStart, $nextWeekEnd]);
            });
        })->distinct()->count();
        
        $totalNotices = $noticeQuery->count();
        $totalHearings = $hearingQuery->count();
        
        // Calculate percentages
        $pendingCases = $openCases; // Open cases are pending
        $pendingPercentage = $totalCases > 0 ? round(($pendingCases / $totalCases) * 100, 1) : 0;
        $resolvedPercentage = $totalCases > 0 ? round(($resolvedCases / $totalCases) * 100, 1) : 0;
        $case_to_court = $totalCases; // All cases are court cases
        $case_to_court_percentage = 100;
        $ClosedCases = $resolvedCases;
        
        // Recent Cases for table (paginated, 5 per page)
        $Cases = (clone $query)->with(['entity', 'caseType'])->orderBy('created_at', 'DESC')->paginate(5);
        
        // Cases by Entity/Department (for doughnut chart)
        if ($user->hasRole('SuperAdmin')) {
            // First, get all distinct entity IDs from cases
            $entityIdsInCases = CourtCase::whereNotNull('entity_id')
                ->distinct()
                ->pluck('entity_id')
                ->toArray();
            
            // Use raw query to get accurate entity counts - ensure we get ALL entities with cases
            $casesByTypeRaw = DB::table('cases')
                ->select('entities.id as entity_id', 'entities.name as entity_name', DB::raw('count(cases.id) as total'))
                ->join('entities', 'cases.entity_id', '=', 'entities.id')
                ->whereNotNull('cases.entity_id')
                ->groupBy('entities.id', 'entities.name')
                ->orderBy('entities.name')
                ->get();
            
            // Convert to array format - ensure we preserve all entities
            $casesByType = [];
            foreach ($casesByTypeRaw as $row) {
                $entityName = trim($row->entity_name ?? 'Unknown');
                // Use entity_id as key to prevent duplicates from same name
                if (!isset($casesByType[$entityName])) {
                    $casesByType[$entityName] = 0;
                }
                $casesByType[$entityName] += (int)$row->total;
            }
            
            // Verify: Check if we're missing any entities
            $allCases = CourtCase::with('entity')
                ->whereNotNull('entity_id')
                ->get();
            
            $entityBreakdown = $allCases->groupBy('entity_id')->map(function($cases) {
                $firstCase = $cases->first();
                return [
                    'entity_id' => $firstCase->entity_id,
                    'entity_name' => $firstCase->entity ? trim($firstCase->entity->name) : 'Unknown',
                    'case_count' => $cases->count()
                ];
            })->values();
            
            // Debug: Log detailed results
            Log::info('Cases by Entity - Detailed Debug', [
                'raw_query_results' => $casesByTypeRaw->toArray(),
                'final_cases_by_type' => $casesByType,
                'entity_ids_found' => $entityIdsInCases,
                'total_cases_in_db' => $allCases->count(),
                'entity_breakdown' => $entityBreakdown->toArray(),
                'entity_count_in_final_array' => count($casesByType)
            ]);
            
            // Additional verification: if entity breakdown shows more entities than final array, log warning
            if ($entityBreakdown->count() > count($casesByType)) {
                Log::warning('Entity count mismatch detected', [
                    'breakdown_count' => $entityBreakdown->count(),
                    'final_array_count' => count($casesByType),
                    'missing_entities' => $entityBreakdown->pluck('entity_name')->diff(array_keys($casesByType))->toArray()
                ]);
            }
        } else {
            // For Legal Officers: show cases they created or are assigned to, grouped by entity
            if ($user->hasRole('Legal Officer')) {
                // Get cases the Legal Officer can see (already filtered in $query)
                $legalOfficerCases = (clone $query)
                    ->with('entity')
                    ->whereNotNull('entity_id')
                    ->get();
                
                // Group by entity and count
                $casesByType = $legalOfficerCases->groupBy(function($case) {
                    return $case->entity ? trim($case->entity->name) : 'Unknown';
                })->map(function($cases) {
                    return $cases->count();
                })->toArray();
                
                // Debug: Log for Legal Officers
                Log::info('Cases by Entity - Legal Officer', [
                    'user_id' => $user->id,
                    'total_cases' => $legalOfficerCases->count(),
                    'cases_by_type' => $casesByType,
                    'entity_ids' => $legalOfficerCases->pluck('entity_id')->unique()->values()->toArray()
                ]);
            } elseif ($user->entity_id) {
                // For regular users: show cases from their entity only
                $entityTotal = (clone $query)->count();
                $casesByType = [$user->entity->name ?? 'Unknown' => $entityTotal];
            } else {
                // User has no entity
                $casesByType = [];
            }
        }
        
        // Cases by Court (for bar chart) - use court name from relationship
        $casesByCourtForChart = (clone $query)->select('court_id', DB::raw('count(*) as total'))
            ->whereNotNull('court_id')
            ->with('court')
            ->groupBy('court_id')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->court->name ?? 'Unknown' => $item->total];
            })
            ->toArray();
        
        // If no court data, provide empty array
        if (empty($casesByCourtForChart)) {
            $casesByCourtForChart = [];
        }
        
        // All Courts with case counts for stat card - use court name from relationship
        $casesByCourtForStat = (clone $query)->select('court_id', DB::raw('count(*) as total'))
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
        foreach ($casesByCourtForStat as $court) {
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
        
        // For chart, ensure all courts are included even with 0 cases
        $casesByCourt = [];
        foreach ($allCourts as $court) {
            $casesByCourt[$court->name] = $casesByCourtForChart[$court->name] ?? 0;
        }
        
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
            $monthResolved = (clone $query)->where('status', 'Resolved')
                ->whereBetween('created_at', [$monthStart, $monthEnd])->count();
            
            $monthlyCases[] = [
                'month' => $date->format('Y-m'),
                'total_cases' => $monthTotal,
                'pending_cases' => $monthOpen,
                'resolved_cases' => $monthResolved,
                'court_cases' => $monthTotal, // All are court cases
            ];
        }

        // Cases by Status (for bar chart)
        $casesByStatus = [
            'Open' => $openCases,
            'Resolved' => $resolvedCases,
        ];
        
        return view('dashboard.admin', compact(
            'totalUsers',
            'totalCases',
            'openCases',
            'closedCases',
            'pendingCases',
            'pendingPercentage',
            'resolvedCases',
            'resolvedPercentage',
            'favourCases',
            'againstCases',
            'case_to_court',
            'case_to_court_percentage',
            'ClosedCases',
            'nextWeekCases',
            'totalNotices',
            'totalHearings',
            'Cases',
            'casesByCourt',
            'topCourts',
            'casesByType',
            'casesByStatus',
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

