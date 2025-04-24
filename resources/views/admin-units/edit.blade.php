@extends('layouts.main')
@section('title','Add Posting')
@section('breadcrumb','Add Posting')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Administrative Unit</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin-units.update', $unit->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $unit->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection