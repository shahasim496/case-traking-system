@extends('layouts.main')
@section('title', 'Add Designation')

@section('content')
<div class="container">
    <h1>Add Designation</h1>
    <form action="{{ route('designations.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Designation Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection