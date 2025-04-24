@extends('layouts.main')
@section('title','Add Posting')
@section('breadcrumb','Add Posting')
@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Subdivision</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('subdivisions.update', $subdivision->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="administrative_unit_id">Administrative Unit</label>
                <select name="administrative_unit_id" id="administrative_unit_id" class="form-control" required>
                    <option value="">Select</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ $subdivision->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $subdivision->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection