
@extends('layouts.main')
@section('title', 'Evidence List')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header btn-primary text-white">
            <h4 class="mb-0">Evidence List</h4>
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
                                <td>{{ $evidence->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('evidence.show', $evidence->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('evidence.edit', $evidence->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('evidence.destroy', $evidence->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this evidence?')">Delete</button>
                                    </form>
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
        </div>
    </div>
</div>
@endsection