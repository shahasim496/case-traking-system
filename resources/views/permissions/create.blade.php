@extends('layouts.main')
@section('title', 'Create Permission')


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
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Save</button>
        </form>
    </div>
</div>
@endsection