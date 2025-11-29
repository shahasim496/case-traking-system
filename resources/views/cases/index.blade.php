@extends('layouts.main')
@section('title', 'Case Tracking')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-gavel mr-2"></i>Case Tracking
                    </h4>
                    <a href="{{ route('cases.create') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus mr-1"></i>Add Case
                    </a>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                            <div class="search-container">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="fa fa-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" 
                                           id="searchInput" 
                                           class="form-control border-left-0" 
                                           placeholder="Search cases..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 mb-3 mb-md-0">
                            <select class="form-control" id="courtTypeFilter" name="court_type">
                                <option value="">All Court Types</option>
                                <option value="High Court" {{ request('court_type') == 'High Court' ? 'selected' : '' }}>High Court</option>
                                <option value="Supreme Court" {{ request('court_type') == 'Supreme Court' ? 'selected' : '' }}>Supreme Court</option>
                                <option value="Session Court" {{ request('court_type') == 'Session Court' ? 'selected' : '' }}>Session Court</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 mb-3 mb-md-0">
                            <select class="form-control" id="statusFilter" name="status">
                                <option value="">All Status</option>
                                <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" id="applyFilters">
                                    <i class="fa fa-filter mr-1"></i>Apply Filters
                                </button>
                                <a href="{{ route('cases.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh mr-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="casesTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Case Number</th>
                                    <th width="10%">Court Type</th>
                                    <th width="18%">Case Title</th>
                                    <th width="12%">Party Name</th>
                                    <th width="12%">Lawyer Name</th>
                                    <th width="10%">Department</th>
                                    <th width="8%">Status</th>
                                    <th width="8%">Notices</th>
                                    <th width="7%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cases as $index => $case)
                                    <tr>
                                        <td>{{ $index + 1 + ($cases->currentPage() - 1) * $cases->perPage() }}</td>
                                        <td><strong>{{ $case->case_number }}</strong></td>
                                        <td>{{ $case->court_type }}</td>
                                        <td>{{ $case->case_title }}</td>
                                        <td>{{ $case->party_name }}</td>
                                        <td>{{ $case->lawyer_name ?? '-' }}</td>
                                        <td>{{ $case->department->name ?? '-' }}</td>
                                        <td>
                                            @if($case->status == 'Open')
                                                <span class="badge badge-success">Open</span>
                                            @else
                                                <span class="badge badge-danger">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $case->notices->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('cases.show', $case->id) }}" 
                                                   class="btn btn-sm btn-info d-flex align-items-center justify-content-center" 
                                                   style="width: 80px;"
                                                   title="View">
                                                    <i class="fa fa-eye mr-1"></i>View
                                                </a>
                                                <a href="{{ route('cases.edit', $case->id) }}" 
                                                   class="btn btn-sm d-flex align-items-center justify-content-center" 
                                                   style="width: 80px; background-color: #00349C; color: white;" 
                                                   title="Edit">
                                                    <i class="fa fa-edit mr-1"></i>Edit
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center justify-content-center" 
                                                        style="width: 80px;"
                                                        data-id="{{ $case->id }}" 
                                                        data-name="{{ $case->case_number }}"
                                                        data-toggle="modal" 
                                                        data-target="#deleteModal" 
                                                        title="Delete">
                                                    <i class="fa fa-trash mr-1"></i>Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No cases found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $cases->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Confirm Delete
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete case: <strong id="deleteCaseName"></strong>?</p>
                <p class="text-muted small">This action cannot be undone. All related notices and hearings will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash mr-1"></i>Delete
                    </button>
                </form>
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

.table {
    border-radius: 8px;
    overflow: hidden;
}

.thead-dark th {
    background-color: #343a40;
    border-color: #454d55;
    color: white;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.d-flex.gap-1 .btn {
    margin-right: 0.25rem;
}

.d-flex.gap-1 .btn:last-child {
    margin-right: 0;
}

.badge {
    font-size: 0.75em;
    border-radius: 4px;
}

.search-container .input-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.search-container .input-group-text {
    border: 1px solid #ced4da;
    border-right: none;
}

.search-container .form-control {
    border: 1px solid #ced4da;
    border-left: none;
    padding: 0.75rem 1rem;
}
</style>

<script>
$(document).ready(function() {
    // Handle delete button click
    $('.delete-btn').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var url = "{{ route('cases.destroy', ':id') }}".replace(':id', id);
        
        $('#deleteCaseName').text(name);
        $('#deleteForm').attr('action', url);
    });
    
    // Apply filters
    $('#applyFilters').click(function() {
        var search = $('#searchInput').val();
        var courtType = $('#courtTypeFilter').val();
        var status = $('#statusFilter').val();
        
        var url = "{{ route('cases.index') }}?";
        var params = [];
        
        if (search) params.push('search=' + encodeURIComponent(search));
        if (courtType) params.push('court_type=' + encodeURIComponent(courtType));
        if (status) params.push('status=' + encodeURIComponent(status));
        
        if (params.length > 0) {
            url += params.join('&');
        }
        
        window.location.href = url;
    });
    
    // Enter key on search input
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            $('#applyFilters').click();
        }
    });
});
</script>
@endsection

