@extends('layouts.main')
@section('title','Add Posting')


@section('content')
<div class="card">
    <div class="card-header">
        <h4>Subdivisions</h4>
        <a href="{{ route('subdivisions.create') }}" class="btn btn-primary float-right">Add Subdivision</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Administrative Unit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subdivisions as $subdivision)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $subdivision->name }}</td>
                    <td>{{ $subdivision->administrativeUnit->name }}</td>
                    <td>
                        <a href="{{ route('subdivisions.edit', $subdivision->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('subdivisions.destroy', $subdivision->id) }}" method="POST" style="display:inline;">
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