@extends('layouts.main')
@section('title','Add Posting')


@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Police Station</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('police-stations.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="subdivision_id">Subdivision</label>
                <select name="subdivision_id" id="subdivision_id" class="form-control" required>
                    <option value="">Select</option>
                    @foreach($subdivisions as $subdivision)
                        <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection