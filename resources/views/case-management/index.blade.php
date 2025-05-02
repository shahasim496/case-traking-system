@extends('layouts.main')

@section('title', 'Cases')


@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Cases</h4>

        <!-- Filter Dropdowns -->
        <form method="GET" action="{{ route('casess.index') }}" class="form-inline">
            <!-- Status Filter -->
            <select name="status" class="form-control mr-3" style="width: 250px; height: 45px; font-size: 16px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="Case Resolved – Released" {{ request('status') == 'Case Resolved – Released' ? 'selected' : '' }}>Case Resolved – Released</option>
                <option value="Case Resolved - Convicted" {{ request('status') == 'Case Resolved - Convicted' ? 'selected' : '' }}>Case Resolved - Convicted</option>
                <option value="Case Closed on Court Order" {{ request('status') == 'Case Closed on Court Order' ? 'selected' : '' }}>Case Closed on Court Order</option>
                <option value="CaseApproved - Charged" {{ request('status') == 'CaseApproved - Charged' ? 'selected' : '' }}>CaseApproved - Charged</option>
                <option value="Awating Verification" {{ request('status') == 'Awating Verification' ? 'selected' : '' }}>Awating Verification</option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Further information" {{ request('status') == 'Further information' ? 'selected' : '' }}>Further information</option>
                <option value="Under Review" {{ request('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <!-- Pagination Filter -->
            <select name="per_page" class="form-control" style="width: 150px; height: 45px; font-size: 16px;" onchange="this.form.submit()">
                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
            </select>
        </form>
    </div>
    <div class="card-body">
        <table id="casesTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Case ID</th>
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
                        <td colspan="7" class="text-center">No cases found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $cases->appends(['status' => request('status'), 'per_page' => request('per_page')])->links('pagination::bootstrap-4') }}
        </div>
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