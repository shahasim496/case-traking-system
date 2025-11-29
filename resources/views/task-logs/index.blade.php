@extends('layouts.main')
@section('title', 'Case Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-history mr-2"></i>Activity Logs
                    </h4>
                    <a href="{{ route('cases.show', $case->id) }}" class="btn btn-light btn-sm">
                        <i class="fa fa-arrow-left mr-1"></i>Back to Case
                    </a>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <div class="mb-3">
                        <p class="text-muted mb-0">
                            <strong>Case:</strong> {{ $case->case_number }} - {{ $case->case_title }}
                        </p>
                    </div>
                    
                    <!-- Search and Filters Section -->
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                            <div class="search-container">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="fa fa-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search logs..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" id="clearSearchBtn" title="Clear Search">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3 mb-md-0">
                            <select class="form-control filter-select" id="categoryFilter" name="category">
                                <option value="all" {{ request('category') == 'all' || !request('category') ? 'selected' : '' }}>All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3 mb-md-0">
                            <select class="form-control filter-select" id="activityTypeFilter" name="activity_type">
                                <option value="all" {{ request('activity_type') == 'all' || !request('activity_type') ? 'selected' : '' }}>All Activities</option>
                                @foreach($activityTypes as $activityType)
                                    <option value="{{ $activityType }}" {{ request('activity_type') == $activityType ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $activityType)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                      
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="logsTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Date & Time</th>
                                    <th width="12%">Category</th>
                                    <th width="12%">Activity</th>
                                    <th width="40%">Description</th>
                                    <th width="16%">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taskLogs as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 + ($taskLogs->currentPage() - 1) * $taskLogs->perPage() }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $log->created_at->format('d M Y') }}<br>
                                            <strong>{{ $log->created_at->format('h:i A') }}</strong>
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $badgeColors = [
                                                'case' => 'badge-primary',
                                                'notice' => 'badge-info',
                                                'hearing' => 'badge-warning',
                                                'forwarding' => 'badge-success',
                                                'comment' => 'badge-secondary'
                                            ];
                                            $badgeColor = $badgeColors[$log->category] ?? 'badge-dark';
                                        @endphp
                                        <span class="badge {{ $badgeColor }}">{{ ucfirst($log->category) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $activityColors = [
                                                'created' => 'text-success',
                                                'updated' => 'text-primary',
                                                'deleted' => 'text-danger',
                                                'forwarded' => 'text-info',
                                                'commented' => 'text-secondary'
                                            ];
                                            $activityColor = $activityColors[$log->activity_type] ?? 'text-dark';
                                        @endphp
                                        <span class="{{ $activityColor }}">
                                            <i class="fa fa-{{ $log->activity_type == 'created' ? 'plus' : ($log->activity_type == 'updated' ? 'edit' : ($log->activity_type == 'deleted' ? 'trash' : 'share')) }} mr-1"></i>
                                            {{ ucfirst($log->activity_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $log->description }}</div>
                                        @if($log->old_data || $log->new_data)
                                            <button type="button" class="btn btn-sm btn-link p-0 mt-1" data-toggle="modal" data-target="#logDetailsModal{{ $log->id }}">
                                                <small>View Details</small>
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->user)
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <strong>{{ $log->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $log->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Details Modal -->
                                @if($log->old_data || $log->new_data)
                                <div class="modal fade" id="logDetailsModal{{ $log->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #00349C; color: white;">
                                                <h5 class="modal-title">Activity Details</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Description:</strong> {{ $log->description }}</p>
                                                @if($log->old_data)
                                                <div class="mb-3">
                                                    <h6 class="text-danger">Old Data:</h6>
                                                    <pre class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">{{ json_encode($log->old_data, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                @endif
                                                @if($log->new_data)
                                                <div>
                                                    <h6 class="text-success">New Data:</h6>
                                                    <pre class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">{{ json_encode($log->new_data, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No activity logs found for this case.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $taskLogs->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
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

.badge {
    font-size: 0.75em;
    border-radius: 4px;
    padding: 0.35em 0.65em;
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

.search-container .form-control:focus {
    box-shadow: none;
    border-color: #00349C;
}

.search-container .btn {
    border: 1px solid #ced4da;
    border-left: none;
    padding: 0.75rem 1rem;
}

.filter-select {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 1px solid #ced4da;
}

.filter-select:focus {
    border-color: #00349C;
    box-shadow: 0 0 0 0.2rem rgba(0, 52, 156, 0.25);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9em;
    }
    
    .search-container {
        margin-bottom: 15px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Server-side filtering function
    function applyFilters() {
        var search = $('#searchInput').val();
        var category = $('#categoryFilter').val();
        var activityType = $('#activityTypeFilter').val();
        
        var url = "{{ route('cases.logs', $case->id) }}?";
        var params = [];
        
        if (search) params.push('search=' + encodeURIComponent(search));
        if (category && category !== 'all') params.push('category=' + encodeURIComponent(category));
        if (activityType && activityType !== 'all') params.push('activity_type=' + encodeURIComponent(activityType));
        
        if (params.length > 0) {
            url += params.join('&');
        }
        
        window.location.href = url;
    }
    
    // Clear search button
    $('#clearSearchBtn').click(function() {
        $('#searchInput').val('');
        applyFilters();
    });
    
    // Search input with debounce (server-side)
    var searchTimeout;
    $('#searchInput').on('keyup', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            applyFilters();
        }, 500); // Wait 500ms after user stops typing
    });
    
    // Enter key on search input
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            clearTimeout(searchTimeout);
            applyFilters();
        }
    });
    
    // Filter selects change event (server-side)
    $('.filter-select').on('change', function() {
        applyFilters();
    });
});
</script>
@endsection

