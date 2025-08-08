@extends('layouts.main')
@section('title','Job Postings')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Job Openings</h4>
                        
                    </div>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    @if($jobPostings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Department</th>
                                        <th>Positions</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobPostings as $jobPosting)
                                        <tr>
                                            <td>
                                                <strong>{{ $jobPosting->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($jobPosting->description, 50) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $jobPosting->department->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $jobPosting->positions }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $deadline = \Carbon\Carbon::parse($jobPosting->deadline);
                                                    $now = \Carbon\Carbon::now();
                                                    $isExpired = $deadline->isPast();
                                                    $isNearExpiry = $deadline->diffInDays($now) <= 7;
                                                @endphp
                                                
                                                @if($isExpired)
                                                    <span class="text-danger">
                                                        <i class="fa fa-clock mr-1"></i>{{ $deadline->format('M d, Y') }}
                                                        <br><small>Expired</small>
                                                    </span>
                                                @elseif($isNearExpiry)
                                                    <span class="text-warning">
                                                        <i class="fa fa-clock mr-1"></i>{{ $deadline->format('M d, Y') }}
                                                        <br><small>Expires Soon</small>
                                                    </span>
                                                @else
                                                    <span class="text-success">
                                                        <i class="fa fa-clock mr-1"></i>{{ $deadline->format('M d, Y') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($jobPosting->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($jobPosting->status == 'draft')
                                                    <span class="badge badge-warning">Draft</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $jobPosting->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('job-posting.show', $jobPosting->id) }}" 
                                                       class="btn btn-sm btn-outline-primary mr-2" 
                                                       title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('job-posting.edit', $jobPosting->id) }}" 
                                                       class="btn btn-sm btn-outline-warning mr-2" 
                                                       title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete"
                                                            onclick="deleteJobPosting({{ $jobPosting->id }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $jobPostings->appends(['status' => request('status'), 'per_page' => request('per_page')])->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-briefcase fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Job Postings Found</h5>
                            <p class="text-muted">Get started by creating your first job posting.</p>
                            <a href="{{ route('job.posting') }}" class="btn" style="background-color: #00349C; color: white;">
                                <i class="fa fa-plus mr-2"></i>Create Job Posting
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this job posting? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteJobPosting(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/job/${id}`;
    $('#deleteModal').modal('show');
}
</script>

@endsection 