@extends('layouts.main')
@section('title', 'Create Permission')
@section('breadcrumb', 'Create Permission')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create Permission</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection