@extends('layouts.main')

@section('title', 'Cases')
@section('breadcrumb', 'Cases')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Cases</h4>
        <a href="{{ route('casess.create') }}" class="btn btn-primary float-right">Add New Case</a>
    </div>
    <div class="card-body">
        <table id="casesTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Case ID</th>
                    <th>Case Type</th>
                    <th>Case Status</th>
                    <th>Department</th>
                    <th>Assigned Officer</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cases as $case)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $case->CaseID }}</td>
                        <td>{{ $case->CaseType }}</td>
                        <td>{{ $case->CaseStatus }}</td>
                        <td>{{ $case->CaseDepartmentName }}</td>
                        <td>{{ $case->OfficerName }}</td>
                        <td>{{ $case->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <a href="{{ route('casess.edit', $case->CaseID) }}" class="btn btn-warning btn-sm">Edit</a>
                           
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No cases found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var caseId = button.data('id'); // Extract case ID from data-id attribute
        var url = "{{ route('casess.destroy', ':id') }}".replace(':id', caseId); // Replace placeholder with actual ID
        $('#deleteForm').attr('action', url); // Set the form action
    });
</script>
@endsection