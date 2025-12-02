@extends('layouts.main')
@section('title', 'Case Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Case Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-gavel mr-2"></i>Case Details
                    </h4>
                    <div>
                        @if(auth()->user()->can('view task logs'))
                        <a href="{{ route('cases.logs', $case->id) }}" class="btn btn-light btn-sm mr-2">
                            <i class="fa fa-history mr-1"></i>View Activity Logs
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @include('components.toaster')
                    
                    <!-- Case Information Section -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                            <i class="fa fa-info-circle mr-2"></i>Case Information
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Case Type:</strong>
                                <p class="mb-0">{{ $case->caseType->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Case Number:</strong>
                                <p class="mb-0">{{ $case->case_number }}</p>
                            </div>
                        </div>
                        
                        @if($case->assignedOfficer)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Assigned Officer:</strong>
                                <p class="mb-0">{{ $case->assignedOfficer->name }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Case Title:</strong>
                                <p class="mb-0">{{ $case->case_title }}</p>
                            </div>
                        </div>
                        
                        @if($case->case_description)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Case Description:</strong>
                                <p class="mb-0">{{ $case->case_description }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Entity:</strong>
                                <p class="mb-0">{{ $case->entity->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p class="mb-0">
                                    @if($case->status == 'Open')
                                        <span class="badge badge-success">Open</span>
                                    @elseif($case->status == 'Resolved')
                                        <span class="badge badge-info">Resolved</span>
                                        @if($case->resolution_outcome)
                                            <br><small class="text-muted">Outcome: <strong>{{ $case->resolution_outcome }}</strong></small>
                                        @endif
                                    @else
                                        <span class="badge badge-danger">{{ $case->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Created At:</strong>
                                <p class="mb-0">{{ $case->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Updated At:</strong>
                                <p class="mb-0">{{ $case->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Petitioner Information Section -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                            <i class="fa fa-user mr-2"></i>Petitioner Information
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Petitioner Name:</strong>
                                <p class="mb-0">{{ $case->petitioner_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Contact Number:</strong>
                                <p class="mb-0">{{ $case->petitioner_contact_number ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>ID Number:</strong>
                                <p class="mb-0">{{ $case->petitioner_id_number ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Date of Birth:</strong>
                                <p class="mb-0">{{ $case->petitioner_date_of_birth ? \Carbon\Carbon::parse($case->petitioner_date_of_birth)->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Gender:</strong>
                                <p class="mb-0">{{ $case->petitioner_gender ?? '-' }}</p>
                            </div>
                            @if($case->petitioner_address)
                            <div class="col-md-6">
                                <strong>Address:</strong>
                                <p class="mb-0">{{ $case->petitioner_address }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Court Information Section -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                            <i class="fa fa-balance-scale mr-2"></i>Court Information
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Court:</strong>
                                <p class="mb-0">{{ $case->court->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                @if($case->court && in_array($case->court->court_type, ['High Court', 'Supreme Court']))
                                    <strong>Bench:</strong>
                                    <p class="mb-0">{{ $case->workBench->name ?? '-' }}</p>
                                @elseif($case->court && $case->court->court_type == 'Session Court')
                                    <strong>Judge Name:</strong>
                                    <p class="mb-0">{{ $case->judge_name ?? '-' }}</p>
                                @endif
                            </div>
                        </div>
                        
                        @if($case->remarks)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Remarks:</strong>
                                <p class="mb-0">{{ $case->remarks }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Case Files Section -->
            @if($case->caseFiles->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-file mr-2"></i>Case Files
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Original Name</th>
                                    <th>File Type</th>
                                    <th>Size</th>
                                    <th>Uploaded At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($case->caseFiles as $file)
                                <tr>
                                    <td>{{ $file->file_name }}</td>
                                    <td>{{ $file->original_name }}</td>
                                    <td>{{ $file->file_type }}</td>
                                    <td>{{ number_format($file->file_size / 1024, 2) }} KB</td>
                                    <td>{{ $file->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm" style="background-color: #00349C; color: white;">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Notices Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-file-text mr-2"></i>Notices 
                    </h5>
                    @if($case->status == 'Open')
                    @if(auth()->user()->can('add notice'))
                        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#addNoticeModal">
                            <i class="fa fa-plus mr-1"></i>Add Notice
                        </button>
                        @endif
                    @else
                        <span class="badge badge-secondary">Case Closed - Cannot Add Notice</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($case->notices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Attachment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($case->notices as $notice)
                                        <tr>
                                            <td>{{ $notice->notice_date->format('d M Y') }}</td>
                                            <td>
                                                @if($notice->attachment)
                                                    <a href="{{ Storage::url($notice->attachment) }}" target="_blank" class="btn btn-sm " style="background-color: #00349C; color: white;">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(auth()->user()->can('view notice'))
                                                <a href="{{ route('notices.show', $notice->id) }}" class="btn" title="View"   style="background-color: #17a2b8; color: white;">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                @endif
                                                @if(auth()->user()->can('edit notice'))
                                                <a href="{{ route('notices.edit', $notice->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                @endif
                                                @if(auth()->user()->can('delete notice'))
                                                        <button type="button" class="btn btn-sm btn-danger delete-notice-btn" data-id="{{ $notice->id }}" data-details="{{ Str::limit($notice->notice_details, 30) }}" title="Delete">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No notices found for this case.</p>
                    @endif
                </div>
            </div>
            
            <!-- Hearings Section -->
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-calendar mr-2"></i>Hearings 
                    </h5>
                    @if($case->status == 'Open')

                    @if(auth()->user()->can('add hearing'))
                        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#addHearingModal">
                            <i class="fa fa-plus mr-1"></i>Add Hearing
                        </button>
                        @endif
                    @else
                        <span class="badge badge-secondary">Case Closed - Cannot Add Hearing</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($case->hearings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Person Appearing</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($case->hearings->sortByDesc('hearing_date') as $hearing)
                                        <tr>
                                            <td>{{ $hearing->hearing_date->format('d M Y') }}</td>
                                            <td>{{ $hearing->person_appearing ?? '-' }}</td>
                                            <td>
                                                @if(auth()->user()->can('view hearing'))
                                                <a href="{{ route('hearings.show', $hearing->id) }}" class="btn" title="View" style="background-color: #17a2b8; color: white;">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                @endif
                                                @if(auth()->user()->can('edit hearing'))
                                                <a href="{{ route('hearings.edit', $hearing->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                @endif
                                                @if(auth()->user()->can('delete hearing'))
                                                <button type="button" class="btn btn-sm btn-danger delete-hearing-btn" data-id="{{ $hearing->id }}" data-date="{{ $hearing->hearing_date->format('d M Y') }}" title="Delete">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No hearings found for this case.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Upcoming Hearings -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-calendar-check-o mr-2"></i>Upcoming Hearings
                    </h5>
                </div>
                <div class="card-body">
                    @if($upcomingHearings->count() > 0)
                        @foreach($upcomingHearings as $hearing)
                            <div class="mb-3 pb-3 border-bottom">
                                <strong>{{ $hearing->hearing_date->format('d M Y') }}</strong>
                                <p class="mb-1 small">{{ Str::limit($hearing->purpose, 50) }}</p>
                                @if($hearing->person_appearing)
                                    <p class="mb-0 small text-muted">
                                        <i class="fa fa-user"></i> {{ $hearing->person_appearing }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No upcoming hearings.</p>
                    @endif
                </div>
            </div>
            
            <!-- Recent Notices -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-bell mr-2"></i>Recent Notices
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentNotices->count() > 0)
                        @foreach($recentNotices as $notice)
                            <div class="mb-3 pb-3 border-bottom">
                                <strong>{{ $notice->notice_date->format('d M Y') }}</strong>
                                <p class="mb-0 small">{{ Str::limit($notice->notice_details, 80) }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No recent notices.</p>
                    @endif
                </div>
            </div>
            
            <!-- Forward To Section -->
            @if(isset($forwardableUsers) && $forwardableUsers->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-share mr-2"></i>Forward To
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cases.forward', $case->id) }}" id="forwardForm">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Select User <span class="text-danger">*</span></label>
                            <select name="forwarded_to" id="forwarded_to" class="form-control @error('forwarded_to') is-invalid @enderror" required>
                                <option value="">-- Select User --</option>
                                @foreach($forwardableUsers as $forwardableUser)
                                    <option value="{{ $forwardableUser->id }}" {{ old('forwarded_to') == $forwardableUser->id ? 'selected' : '' }}>
                                        {{ $forwardableUser->name }} 
                                        @if($forwardableUser->roles && $forwardableUser->roles->count() > 0)
                                            ({{ $forwardableUser->roles->pluck('name')->implode(', ') }})
                                        @endif
                                        @if($forwardableUser->entity)
                                            - {{ $forwardableUser->entity->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('forwarded_to')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">Message</label>
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" 
                                      rows="3" placeholder="Enter message (optional)...">{{ old('message') }}</textarea>
                            @error('message')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-block" style="background-color: #00349C; color: white;">
                            <i class="fa fa-paper-plane mr-1"></i>Forward Case
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-share mr-2"></i>Forward To
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center mb-0">
                        @if(auth()->user()->hasPermissionTo('forward to any role'))
                            No users available to forward in this case's entity.
                        @elseif(auth()->user()->hasPermissionTo('forward to joint secretary'))
                            No Joint Secretary users found in this case's entity.
                        @elseif(auth()->user()->hasAnyPermission(['forward to permanent secretary', 'forward to secretary']))
                            No Permanent Secretary or Secretary users found in this case's entity.
                        @else
                            You do not have permission to forward cases.
                        @endif
                    </p>
                </div>
            </div>
            @endif
            
            <!-- Comments Section -->
            @if($case->entity_id && (auth()->user()->entity_id == $case->entity_id || auth()->user()->hasRole('SuperAdmin')))
            <div class="card shadow-sm mt-4">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-comments mr-2"></i>Comments
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Add Comment Form -->
                    <form method="POST" action="{{ route('cases.comments.store', $case->id) }}" class="mb-4">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Add Comment</label>
                            <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" 
                                      rows="3" placeholder="Enter your comment..." required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-sm" style="background-color: #00349C; color: white;">
                            <i class="fa fa-paper-plane mr-1"></i>Post Comment
                        </button>
                    </form>

                    <!-- Comments List -->
                    <div class="comments-list">
                        @if($case->comments->count() > 0)
                            @foreach($case->comments as $comment)
                                <div class="comment-item mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ $comment->user->name }}</strong>
                                            <small class="text-muted ml-2">
                                                {{ $comment->created_at->format('d M Y, h:i A') }}
                                                @if($comment->updated_at != $comment->created_at)
                                                    <span class="text-info">(Edited)</span>
                                                @endif
                                            </small>
                                        </div>
                                        @if($comment->user_id == auth()->id())
                                            <div>
                                                <button type="button" class="btn btn-sm btn-warning edit-comment-btn" 
                                                        data-comment-id="{{ $comment->id }}"
                                                        data-comment-text="{{ $comment->comment }}"
                                                        title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="comment-content" id="comment-content-{{ $comment->id }}">
                                        <p class="mb-0">{{ $comment->comment }}</p>
                                    </div>
                                    
                                    <!-- Edit Comment Form (Hidden by default) -->
                                    <div class="edit-comment-form" id="edit-form-{{ $comment->id }}" style="display: none;">
                                        <form method="POST" action="{{ route('cases.comments.update', [$case->id, $comment->id]) }}" class="mt-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group mb-2">
                                                <textarea name="comment" class="form-control" rows="3" required>{{ $comment->comment }}</textarea>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-save mr-1"></i>Save
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn" data-comment-id="{{ $comment->id }}">
                                                    <i class="fa fa-times mr-1"></i>Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center mb-0">No comments yet. Be the first to comment!</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
        </div>
    </div>
</div>

<!-- Delete Notice Modal -->
<div class="modal fade" id="deleteNoticeModal" tabindex="-1" role="dialog" aria-labelledby="deleteNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #dc3545;">
                <h5 class="modal-title" id="deleteNoticeModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Delete Notice
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="" id="deleteNoticeForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this notice?</p>
                    <p class="mb-0"><strong>Notice Details:</strong> <span id="deleteNoticeDetails"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border-bottom: none;
}

.badge {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}
</style>

<!-- Add Notice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1" role="dialog" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #00349C;">
                <h5 class="modal-title" id="addNoticeModalLabel">
                    <i class="fa fa-file-text mr-2"></i>Add Notice
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('cases.notices.store', $case->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Notice Date <span class="text-danger">*</span></label>
                        <input type="date" name="notice_date" class="form-control @error('notice_date') is-invalid @enderror" 
                               value="{{ old('notice_date') }}" required>
                        @error('notice_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Notice Details</label>
                        <textarea name="notice_details" class="form-control @error('notice_details') is-invalid @enderror" 
                                  rows="4" placeholder="Enter notice details...">{{ old('notice_details') }}</textarea>
                        @error('notice_details')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Attachment</label>
                        <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                        @error('attachment')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #00349C; color: white;">
                        <i class="fa fa-save mr-1" style="color: white; background-color: #00349C;"></i>Save Notice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Hearing Modal -->
<div class="modal fade" id="addHearingModal" tabindex="-1" role="dialog" aria-labelledby="addHearingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #00349C;">
                <h5 class="modal-title" id="addHearingModalLabel">
                    <i class="fa fa-calendar mr-2"></i>Add Hearing
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('cases.hearings.store', $case->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Hearing Date <span class="text-danger">*</span></label>
                                <input type="date" name="hearing_date" class="form-control @error('hearing_date') is-invalid @enderror" 
                                       value="{{ old('hearing_date') }}" required>
                                @error('hearing_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Next Hearing Date</label>
                                <input type="date" name="next_hearing_date" class="form-control @error('next_hearing_date') is-invalid @enderror" 
                                       value="{{ old('next_hearing_date') }}">
                                @error('next_hearing_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Purpose</label>
                        <textarea name="purpose" class="form-control @error('purpose') is-invalid @enderror" 
                                  rows="3" placeholder="Enter hearing purpose...">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Person Appearing</label>
                        <input type="text" name="person_appearing" class="form-control @error('person_appearing') is-invalid @enderror" 
                               value="{{ old('person_appearing') }}" placeholder="Enter person appearing in court...">
                        @error('person_appearing')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Outcome</label>
                        <textarea name="outcome" class="form-control @error('outcome') is-invalid @enderror" 
                                  rows="3" placeholder="Enter hearing outcome...">{{ old('outcome') }}</textarea>
                        @error('outcome')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Court Order (Optional)</label>
                        <input type="file" name="court_order" class="form-control @error('court_order') is-invalid @enderror" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                        @error('court_order')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #00349C; color: white;">
                        <i class="fa fa-save mr-1" style="color: white; background-color: #00349C;"></i>Save Hearing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Hearing Modal -->
<div class="modal fade" id="deleteHearingModal" tabindex="-1" role="dialog" aria-labelledby="deleteHearingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #dc3545;">
                <h5 class="modal-title" id="deleteHearingModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Delete Hearing
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="" id="deleteHearingForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this hearing?</p>
                    <p class="mb-0"><strong>Hearing Date:</strong> <span id="deleteHearingDate"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Handle delete notice button click
    $('.delete-notice-btn').click(function() {
        var id = $(this).data('id');
        var details = $(this).data('details');
        var url = "{{ route('notices.destroy', ':id') }}".replace(':id', id);
        
        $('#deleteNoticeDetails').text(details || 'Notice');
        $('#deleteNoticeForm').attr('action', url);
        $('#deleteNoticeModal').modal('show');
    });
    
    // Handle delete hearing button click
    $('.delete-hearing-btn').click(function() {
        var id = $(this).data('id');
        var date = $(this).data('date');
        var url = "{{ route('hearings.destroy', ':id') }}".replace(':id', id);
        
        $('#deleteHearingDate').text(date || 'Hearing');
        $('#deleteHearingForm').attr('action', url);
        $('#deleteHearingModal').modal('show');
    });
    
    // Handle edit comment button click
    $('.edit-comment-btn').click(function() {
        var commentId = $(this).data('comment-id');
        $('#comment-content-' + commentId).hide();
        $('#edit-form-' + commentId).show();
        $(this).hide();
    });
    
    // Handle cancel edit comment button click
    $('.cancel-edit-btn').click(function() {
        var commentId = $(this).data('comment-id');
        $('#edit-form-' + commentId).hide();
        $('#comment-content-' + commentId).show();
        $('.edit-comment-btn[data-comment-id="' + commentId + '"]').show();
    });
});
</script>

<style>
.comment-item {
    transition: all 0.3s ease;
}

.comment-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.d-flex.gap-2 .btn {
    margin-right: 0.5rem;
}

.d-flex.gap-2 .btn:last-child {
    margin-right: 0;
}
</style>
@endsection

