
@extends('layouts.main')
@section('title','Add Posting')
@section('breadcrumb','Add Posting')


@section('content')
<div class="card">
    <div class="card-header">
        <h4>Administrative Units</h4>
        <a href="{{ route('admin-units.create') }}" class="btn btn-primary float-right">Add Administrative Unit</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $unit->name }}</td>
                    <td>
                        <a href="{{ route('admin-units.edit', $unit->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin-units.destroy', $unit->id) }}" method="POST" style="display:inline;">
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