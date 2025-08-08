resources/views/designations/index.blade.php
@extends('layouts.main')
@section('title', 'Manage Designations')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-briefcase mr-2"></i>Manage Designations
                    </h4>
                    <a href="{{ route('designations.create') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus mr-1"></i>Add Designation
                    </a>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Name</th>
                                    <th width="45%">Description</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($designations as $index => $designation)
                                    <tr>
                                        <td>{{ $index + 1 + ($designations->currentPage() - 1) * $designations->perPage() }}</td>
                                        <td>{{ $designation->name }}</td>
                                        <td>{{ $designation->description ?? 'No description' }}</td>
                                        <td>
                                            <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-sm" style="background-color: #00349C; color: white; width: 80px;" title="Edit">
                                                <i class="fa fa-edit mr-1"></i>Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm delete-btn" style="width: 80px;" data-id="{{ $designation->id }}" data-toggle="modal" data-target="#deleteModal" title="Delete">
                                                <i class="fa fa-trash mr-1"></i>Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No designations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
            {{ $designations->appends(['status' => request('status'), 'per_page' => request('per_page')])->links('pagination::bootstrap-4') }}
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
                <p>Are you sure you want to delete this designation?</p>
                <p class="text-muted small">This action cannot be undone.</p>
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

.modal-content {
    border-radius: 10px;
    border: none;
}

.modal-header {
    border-radius: 10px 10px 0 0;
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #00349C;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #00349C;
    border-color: #00349C;
}
</style>

<script>
$(document).ready(function() {
    // Handle delete button click
    $('.delete-btn').click(function() {
        var id = $(this).data('id');
        var url = "{{ route('designations.delete', ':id') }}".replace(':id', id);
        $('#deleteForm').attr('action', url);
    });
});
</script>
@endsection

