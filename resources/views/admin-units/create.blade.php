@extends('layouts.main')
@section('title','Add Posting')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Administrative Unit</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin-units.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection