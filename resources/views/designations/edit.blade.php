@extends('layouts.main')
@section('title', 'Edit Designation')

@section('content')
<div class="container">
    <h1>Edit Designation</h1>
    <form action="{{ route('designations.update', $designation->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Designation Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $designation->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $designation->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection