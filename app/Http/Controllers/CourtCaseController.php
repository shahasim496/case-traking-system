<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\CourtCase;
use App\Models\Notice;
use App\Models\Hearing;
use App\Models\Party;
use App\Models\Department;
use App\Models\CaseForward;
use App\Models\CaseComment;
use App\Models\User;
use App\Models\TaskLog;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Mail;
use App\Traits\LogsActivity;

class CourtCaseController extends Controller
{
    use LogsActivity;
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

            // Log activity
            $this->logActivity(
                $case->id,
                'case',
                'created',
                "Case '{$case->case_number}' ({$case->case_title}) was created",
                'CourtCase',
                $case->id,
                null,
                $case->toArray()
            );

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
        $case = CourtCase::with(['notices', 'hearings', 'department', 'parties', 'comments.user'])->findOrFail($id);
        
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

        // Get forwardable users based on role
        $forwardableUsers = $this->getForwardableUsers($user, $case);
        
        // Debug: Log forwardable users count
        Log::info('Forwardable users count: ' . $forwardableUsers->count(), [
            'user_id' => $user->id,
            'user_roles' => $user->roles->pluck('name')->toArray(),
            'case_department_id' => $case->department_id
        ]);

        // Get task logs for forwarding (last 5)
        $forwardingLogs = TaskLog::where('case_id', $id)
            ->where('category', 'forwarding')
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        // Get task logs for comments (last 5)
        $commentLogs = TaskLog::where('case_id', $id)
            ->where('category', 'comment')
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('cases.show', compact('case', 'upcomingHearings', 'recentNotices', 'forwardableUsers', 'forwardingLogs', 'commentLogs'));
    }

    /**
     * Get forwardable users based on user's permissions and case department.
     * Users can only forward to roles they have permission for, and must be in the same department as the case.
     */
    private function getForwardableUsers($user, $case)
    {
        $forwardableUsers = collect();

        // Load user roles and permissions
        $user->load('roles', 'permissions');

        // Check if user's department matches case's department (required for non-SuperAdmin)
        if (!$user->hasPermissionTo('forward to any role')) {
            if (!$case->department_id || $user->department_id != $case->department_id) {
                // User's department must match case's department
                Log::info('User department does not match case department', [
                    'user_department_id' => $user->department_id,
                    'case_department_id' => $case->department_id
                ]);
                return $forwardableUsers; // Return empty collection
            }
        }

        // Define allowed roles from RoleSeeder
        $allowedRoles = ['SuperAdmin', 'Legal Officer', 'Joint Secretary', 'Permanent Secretary', 'Secretary'];
        
        // Get role IDs for allowed roles
        $allowedRoleIds = \Spatie\Permission\Models\Role::whereIn('name', $allowedRoles)
            ->where('guard_name', 'web')
            ->pluck('id')
            ->toArray();

        // Build list of target roles based on user's permissions
        $targetRoleNames = [];

        if ($user->hasPermissionTo('forward to any role')) {
            // SuperAdmin can forward to any role of case department user
            $targetRoleNames = $allowedRoles;
        } else {
            // Check specific forwarding permissions
            if ($user->hasPermissionTo('forward to joint secretary')) {
                $targetRoleNames[] = 'Joint Secretary';
            }
            if ($user->hasPermissionTo('forward to permanent secretary')) {
                $targetRoleNames[] = 'Permanent Secretary';
            }
            if ($user->hasPermissionTo('forward to secretary')) {
                $targetRoleNames[] = 'Secretary';
            }
            if ($user->hasPermissionTo('forward to legal officer')) {
                $targetRoleNames[] = 'Legal Officer';
            }
        }

        // If no permissions found, return empty collection
        if (empty($targetRoleNames)) {
            Log::info('User has no forwarding permissions', [
                'user_id' => $user->id,
                'user_permissions' => $user->permissions->pluck('name')->toArray()
            ]);
            return $forwardableUsers;
        }

        // Get role IDs for target roles
        $targetRoleIds = \Spatie\Permission\Models\Role::whereIn('name', $targetRoleNames)
            ->where('guard_name', 'web')
            ->pluck('id')
            ->toArray();

        if (empty($targetRoleIds)) {
            return $forwardableUsers;
        }

        // Build query for forwardable users
        $query = User::where('id', '!=', $user->id)
            ->whereHas('roles', function($q) use ($targetRoleIds) {
                $q->whereIn('roles.id', $targetRoleIds)
                  ->where('roles.guard_name', 'web');
            });

        // If user has "forward to any role" permission (SuperAdmin), they can forward to any department
        // Otherwise, must match case's department
        if (!$user->hasPermissionTo('forward to any role')) {
            if ($case->department_id) {
                $query->where('department_id', $case->department_id);
            } else {
                // If case has no department and user doesn't have "forward to any role", return empty
                return $forwardableUsers;
            }
        } else {
            // SuperAdmin can forward to case department if case has department
            if ($case->department_id) {
                $query->where('department_id', $case->department_id);
            }
        }

        $forwardableUsers = $query->with(['roles' => function($query) {
            $query->where('guard_name', 'web');
        }, 'department'])
        ->get();

        Log::info('Forwardable users found', [
            'count' => $forwardableUsers->count(),
            'user_ids' => $forwardableUsers->pluck('id')->toArray(),
            'user_permissions' => $user->permissions->pluck('name')->toArray(),
            'target_roles' => $targetRoleNames,
            'case_department_id' => $case->department_id,
            'user_department_id' => $user->department_id
        ]);

        return $forwardableUsers;
    }

    /**
     * Forward a case to another user.
     */
    public function forward(Request $request, $caseId)
    {
        $case = CourtCase::findOrFail($caseId);
        $user = Auth::user();

        // Check if user has any forwarding permission
        if (!$user->hasAnyPermission(['forward to any role', 'forward to joint secretary', 'forward to permanent secretary', 'forward to secretary', 'forward to legal officer'])) {
            abort(403, 'You do not have permission to forward cases.');
        }

        // Check if user's department matches case's department (required for non-SuperAdmin)
        if (!$user->hasPermissionTo('forward to any role')) {
            if ($user->department_id && $case->department_id != $user->department_id) {
                abort(403, 'You can only forward cases from your department.');
            } elseif (!$user->department_id) {
                abort(403, 'You do not have permission to forward this case.');
            }
        }

        // Get forwardable users
        $forwardableUsers = $this->getForwardableUsers($user, $case);
        $forwardableUserIds = $forwardableUsers->pluck('id')->toArray();

        $validatedData = $request->validate([
            'forwarded_to' => 'required|exists:users,id|in:' . implode(',', $forwardableUserIds),
            'message' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Create case forward record
            $caseForward = CaseForward::create([
                'case_id' => $caseId,
                'forwarded_by' => $user->id,
                'forwarded_to' => $validatedData['forwarded_to'],
                'message' => $validatedData['message'] ?? null,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Get the receiver user
            $receiver = User::findOrFail($validatedData['forwarded_to']);
            
            // Track email sending status
            $emailSent = false;
            $emailError = null;

            // Send email notification if receiver has email and mail is configured
            if ($receiver->email && !empty($receiver->email) && filter_var($receiver->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $fromEmail = env('MAIL_FROM_ADDRESS', config('mail.from.address'));
                    $fromName = env('MAIL_FROM_NAME', config('mail.from.name', 'Court System'));
                    
                    // Only send email if MAIL_FROM_ADDRESS is configured
                    if ($fromEmail && !empty($fromEmail) && $fromEmail !== 'hello@example.com') {
                        $emailData = [
                            'to_email' => $receiver->email,
                            'subject' => 'Case Forwarded: ' . $case->case_number,
                            'senderName' => $user->name,
                            'receiverName' => $receiver->name,
                            'case' => $case,
                            'message' => $validatedData['message'] ?? null,
                            'from_email' => $fromEmail,
                        ];

                        Mail::send('emails.case_forwarded', ['data' => $emailData], function($message) use ($emailData, $fromEmail, $fromName) {
                            $message->from($fromEmail, $fromName);
                            $message->to($emailData['to_email']);
                            $message->subject($emailData['subject']);
                        });

                        // Check for mail failures
                        if (Mail::failures()) {
                            $failures = Mail::failures();
                            $emailError = 'Email sending failed: ' . implode(', ', $failures);
                            Log::error('Email sending failed', [
                                'receiver_id' => $receiver->id,
                                'receiver_email' => $receiver->email,
                                'failures' => $failures
                            ]);
                        } else {
                            $emailSent = true;
                            Log::info('Email sent successfully', [
                                'receiver_id' => $receiver->id,
                                'receiver_email' => $receiver->email,
                                'case_id' => $case->id
                            ]);
                        }
                    } else {
                        $emailError = 'MAIL_FROM_ADDRESS not configured';
                        Log::warning('Email not sent: MAIL_FROM_ADDRESS not configured or using default', [
                            'receiver_id' => $receiver->id,
                            'receiver_email' => $receiver->email,
                            'from_email' => $fromEmail
                        ]);
                    }
                } catch (\Exception $emailException) {
                    $emailError = $emailException->getMessage();
                    // Log email error but don't fail the transaction
                    Log::error('Failed to send forward email', [
                        'error' => $emailException->getMessage(),
                        'trace' => $emailException->getTraceAsString(),
                        'receiver_id' => $receiver->id,
                        'receiver_email' => $receiver->email
                    ]);
                }
            } else {
                $emailError = 'Invalid or missing receiver email';
                Log::warning('Email not sent: Invalid or missing receiver email', [
                    'receiver_id' => $receiver->id,
                    'receiver_email' => $receiver->email ?? 'null'
                ]);
            }

            // Create in-app notification
            $receiver->notify(new AppNotification(
                $user->id,
                $receiver->id,
                'Case Forwarded',
                'Case ' . $case->case_number . ' has been forwarded to you by ' . $user->name . ($validatedData['message'] ? '. Message: ' . Str::limit($validatedData['message'], 100) : ''),
                'case_forwarded',
                $case->id
            ));

            // Log activity
            $this->logActivity(
                $caseId,
                'forwarding',
                'forwarded',
                "Case '{$case->case_number}' was forwarded from {$user->name} to {$receiver->name}",
                'CaseForward',
                $caseForward->id,
                null,
                $caseForward->toArray()
            );

            DB::commit();

            // Prepare success message with email status
            $successMessage = 'Case forwarded successfully.';
            if ($emailSent) {
                $successMessage .= ' Email notification sent to ' . $receiver->email . '.';
            } else {
                $successMessage .= ' (Email notification could not be sent';
                if ($emailError) {
                    $successMessage .= ': ' . $emailError;
                }
                $successMessage .= ' - please check mail configuration and logs)';
            }

            return redirect()->route('cases.show', $caseId)->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to forward case: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store a comment for a case.
     */
    public function storeComment(Request $request, $caseId)
    {
        $case = CourtCase::findOrFail($caseId);
        $user = Auth::user();

        // Check if user has access to this case (must be from same department or SuperAdmin)
        if (!$user->hasRole('SuperAdmin')) {
            if ($user->department_id && $case->department_id != $user->department_id) {
                abort(403, 'You do not have permission to comment on this case.');
            } elseif (!$user->department_id) {
                abort(403, 'You do not have permission to comment on this case.');
            }
        }

        $validatedData = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        DB::beginTransaction();

        try {
            $comment = CaseComment::create([
                'case_id' => $caseId,
                'user_id' => $user->id,
                'comment' => $validatedData['comment'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Log activity
            $this->logActivity(
                $caseId,
                'comment',
                'created',
                "Comment added to case '{$case->case_number}' by {$user->name}",
                'CaseComment',
                $comment->id,
                null,
                $comment->toArray()
            );

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to add comment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update a comment for a case.
     */
    public function updateComment(Request $request, $caseId, $commentId)
    {
        $case = CourtCase::findOrFail($caseId);
        $comment = CaseComment::findOrFail($commentId);
        $user = Auth::user();

        // Check if user owns this comment
        if ($comment->user_id != $user->id) {
            abort(403, 'You can only edit your own comments.');
        }

        // Check if user has access to this case
        if (!$user->hasRole('SuperAdmin')) {
            if ($user->department_id && $case->department_id != $user->department_id) {
                abort(403, 'You do not have permission to edit comments on this case.');
            } elseif (!$user->department_id) {
                abort(403, 'You do not have permission to edit comments on this case.');
            }
        }

        $validatedData = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        DB::beginTransaction();

        try {
            $oldData = $comment->toArray();
            $comment->update([
                'comment' => $validatedData['comment'],
                'updated_by' => $user->id,
            ]);

            // Log activity
            $this->logActivity(
                $caseId,
                'comment',
                'updated',
                "Comment updated on case '{$case->case_number}' by {$user->name}",
                'CaseComment',
                $comment->id,
                $oldData,
                $comment->fresh()->toArray()
            );

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Comment updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update comment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a comment for a case.
     */
    public function deleteComment($caseId, $commentId)
    {
        $case = CourtCase::findOrFail($caseId);
        $comment = CaseComment::findOrFail($commentId);
        $user = Auth::user();

        // Check if user owns this comment
        if ($comment->user_id != $user->id && !$user->hasRole('SuperAdmin')) {
            abort(403, 'You can only delete your own comments.');
        }

        DB::beginTransaction();

        try {
            $comment->delete();

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete comment: ' . $e->getMessage());
        }
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
            $oldData = $case->toArray();
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

            // Log activity
            $this->logActivity(
                $case->id,
                'case',
                'updated',
                "Case '{$case->case_number}' ({$case->case_title}) was updated",
                'CourtCase',
                $case->id,
                $oldData,
                $case->fresh()->toArray()
            );

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
            $caseData = $case->toArray();
            
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

            // Log activity before deletion
            $this->logActivity(
                $case->id,
                'case',
                'deleted',
                "Case '{$case->case_number}' ({$case->case_title}) was deleted",
                'CourtCase',
                $case->id,
                $caseData,
                null
            );

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

            $notice = Notice::create($validatedData);

            // Log activity
            $this->logActivity(
                $caseId,
                'notice',
                'created',
                "Notice added to case '{$case->case_number}' on " . date('d M Y', strtotime($notice->notice_date)),
                'Notice',
                $notice->id,
                null,
                $notice->toArray()
            );

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

            $hearing = Hearing::create($validatedData);

            // Log activity
            $this->logActivity(
                $caseId,
                'hearing',
                'created',
                "Hearing added to case '{$case->case_number}' scheduled for " . date('d M Y', strtotime($hearing->hearing_date)),
                'Hearing',
                $hearing->id,
                null,
                $hearing->toArray()
            );

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Hearing added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}

