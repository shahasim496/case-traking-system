@extends('layouts.main')
@section('title', 'Edit Permission')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Permission</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ $permission->description }}</textarea>
            </div>
            <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Update</button>
        </form>
    </div>
</div>
@endsection