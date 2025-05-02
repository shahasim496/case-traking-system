@extends('layouts.main')
@section('title', 'Create Department')


@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create Department</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Department Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection