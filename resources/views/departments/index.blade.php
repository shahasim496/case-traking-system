resources\views\departments\index.blade.php
@extends('layouts.main')
@section('title', 'Departments')
@section('breadcrumb', 'Departments')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Manage Departments</h4>
        <a href="{{ route('departments.create') }}" class="btn btn-sm btn-success">Add Department</a>
    </div>
    <div class="card-body">
        <table id="departmentsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this department?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsfile')
<script>
    $(document).ready(function () {
        $('#departmentsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('departments.getDepartments') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        // Handle delete button click
        $('#departmentsTable').on('click', '.delete_icon', function () {
            var id = $(this).data('id');
            var url = "{{ route('departments.delete', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', url);
        });
    });
</script>
@endsection