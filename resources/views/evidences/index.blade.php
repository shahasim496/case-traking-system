
@extends('layouts.main')
@section('title', 'Evidence List')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Evidence List</h4>
            <form method="GET" action="{{ route('evidences.index') }}" class="d-flex align-items-center">
                <!-- Filter by Type -->
                <label for="type" class="me-2 mb-0"><strong>Type:</strong></label>
                <select  name="type" id="type" class="form-select form-select-lg me-4" style="width: 200px;" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="currency" {{ request('type') == 'currency' ? 'selected' : '' }}>Currency</option>
                    <option value="dna" {{ request('type') == 'dna' ? 'selected' : '' }}>DNA</option>
                    <option value="ballistics" {{ request('type') == 'ballistics' ? 'selected' : '' }}>Ballistics</option>
                    <option value="toxicology" {{ request('type') == 'toxicology' ? 'selected' : '' }}>Toxicology</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                    <option value="questioned_document" {{ request('type') == 'questioned_document' ? 'selected' : '' }}>Questioned Document</option>
                </select>

                <!-- Pagination Dropdown -->
                <label style="margin-left: 10px;" for="perPage" class="me-2 mb-0"><strong>Show:</strong></label>
                <select name="perPage" id="perPage" class="form-select form-select-lg" style="width: 150px;" onchange="this.form.submit()">
                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Officer ID</th>
                            <th>Officer Name</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evidences as $evidence)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($evidence->type) }}</td>
                                <td>{{ $evidence->officer_id }}</td>
                                <td>{{ $evidence->officer_name }}</td>
                                <td>{{ $evidence->designation }}</td>
                                 <td>
        <span class="badge 
            @if($evidence->status == 'pending') bg-danger 
            @elseif($evidence->status == 'verified') bg-warning 
            @elseif($evidence->status == 'completed') bg-success 
            @endif">
            {{ ucfirst($evidence->status) }}
        </span>
    </td>
                                <td>{{ $evidence->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('evidence.show', $evidence->id) }}" class="btn btn-info btn-sm">View</a>


                                    @if(auth()->user()->can('delete evidence'))
                                    <form action="{{ route('evidence.destroy', $evidence->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No evidence records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <p class="mb-0">Showing {{ $evidences->firstItem() }} to {{ $evidences->lastItem() }} of {{ $evidences->total() }} records</p>
                {{ $evidences->appends(['perPage' => request('perPage'), 'type' => request('type')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection