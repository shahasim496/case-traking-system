
@extends('layouts.main')
@section('title', 'Edit Case')


@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Case</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('cases.update', $case->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Case Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $case->name }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
@endsection