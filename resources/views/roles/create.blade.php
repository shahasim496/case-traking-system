@extends('layouts.main')
@section('title', 'Create Role')
@section('breadcrumb', 'Create Role')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create Role</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Role Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection