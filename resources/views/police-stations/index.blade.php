@extends('layouts.main')
@section('title','Add Posting')


@section('content')
<div class="card">
    <div class="card-header">
        <h4>Police Stations</h4>
        <a href="{{ route('police-stations.create') }}" class="btn btn-primary float-right">Add Police Station</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Subdivision</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stations as $station)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $station->name }}</td>
                    <td>{{ $station->subdivision->name }}</td>
                    <td>
                        <a href="{{ route('police-stations.edit', $station->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('police-stations.destroy', $station->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection