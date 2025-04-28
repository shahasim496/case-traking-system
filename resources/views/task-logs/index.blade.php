
@extends('layouts.main')

@section('title', 'Task Logs')
@section('breadcrumb', 'Task Logs')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <h4>Task Logs</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Officer Name</th>
                    <th>Officer Rank</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action Taken</th>
                </tr>
            </thead>
            <tbody>
                @forelse($taskLogs as $log)
                <tr>
                    <td>{{ $log->officer_name }}</td>
                    <td>{{ $log->officer_rank }}</td>
                    <td>{{ $log->department }}</td>
                    <td>{{ $log->date }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->action_taken }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No task logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
        {{ $taskLogs->links('pagination::bootstrap-4') }}
        </div>
        <div class="text-right">
            <a href="{{ route('casess.edit', $case_id) }}" class="btn btn-secondary">Back to Case</a>
        </div>
    </div>
</div>
@endsection