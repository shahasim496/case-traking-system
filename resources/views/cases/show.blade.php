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
                        <a href="{{ route('cases.edit', $case->id) }}" class="btn btn-light btn-sm">
                            <i class="fa fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('cases.index') }}" class="btn btn-light btn-sm">
                            <i class="fa fa-arrow-left mr-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @include('components.toaster')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Case Number:</strong>
                            <p class="mb-0">{{ $case->case_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Court Type:</strong>
                            <p class="mb-0">{{ $case->court_type }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Case Title:</strong>
                            <p class="mb-0">{{ $case->case_title }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Party Name:</strong>
                            <p class="mb-0">{{ $case->party_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Lawyer Name:</strong>
                            <p class="mb-0">{{ $case->lawyer_name ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Department:</strong>
                            <p class="mb-0">{{ $case->department->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p class="mb-0">
                                @if($case->status == 'Open')
                                    <span class="badge badge-success">Open</span>
                                @else
                                    <span class="badge badge-danger">Closed</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Created At:</strong>
                            <p class="mb-0">{{ $case->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notices Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-file-text mr-2"></i>Notices ({{ $case->notices->count() }})
                    </h5>
                    <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#addNoticeModal">
                        <i class="fa fa-plus mr-1"></i>Add Notice
                    </button>
                </div>
                <div class="card-body">
                    @if($case->notices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Details</th>
                                        <th>Attachment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($case->notices as $notice)
                                        <tr>
                                            <td>{{ $notice->notice_date->format('d M Y') }}</td>
                                            <td>{{ Str::limit($notice->notice_details, 50) }}</td>
                                            <td>
                                                @if($notice->attachment)
                                                    <a href="{{ Storage::url($notice->attachment) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-download"></i> View
                                                    </a>
                                                @else
                                                    -
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
                        <i class="fa fa-calendar mr-2"></i>Hearings ({{ $case->hearings->count() }})
                    </h5>
                    <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#addHearingModal">
                        <i class="fa fa-plus mr-1"></i>Add Hearing
                    </button>
                </div>
                <div class="card-body">
                    @if($case->hearings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Purpose</th>
                                        <th>Person Appearing</th>
                                        <th>Outcome</th>
                                        <th>Next Hearing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($case->hearings->sortByDesc('hearing_date') as $hearing)
                                        <tr>
                                            <td>{{ $hearing->hearing_date->format('d M Y') }}</td>
                                            <td>{{ Str::limit($hearing->purpose, 30) }}</td>
                                            <td>{{ $hearing->person_appearing ?? '-' }}</td>
                                            <td>{{ Str::limit($hearing->outcome, 30) ?? '-' }}</td>
                                            <td>
                                                @if($hearing->next_hearing_date)
                                                    {{ $hearing->next_hearing_date->format('d M Y') }}
                                                @else
                                                    -
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
                <div class="card-header text-white" style="background-color: #28a745;">
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
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #17a2b8;">
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
            <div class="modal-header bg-primary text-white">
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save mr-1"></i>Save Notice
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
            <div class="modal-header bg-success text-white">
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
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save mr-1"></i>Save Hearing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

