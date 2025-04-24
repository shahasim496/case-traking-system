@extends('layouts.main')
@section('title','Add Posting')
@section('breadcrumb','Add Posting')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Police Station</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('police-stations.update', $station->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="subdivision_id">Subdivision</label>
                <select name="subdivision_id" id="subdivision_id" class="form-control" required>
                    <option value="">Select</option>
                    @foreach($subdivisions as $subdivision)
                        <option value="{{ $subdivision->id }}" {{ $station->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                            {{ $subdivision->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $station->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection