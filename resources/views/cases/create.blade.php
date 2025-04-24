
@extends('layouts.main')
@section('title', 'Create Case')
@section('breadcrumb', 'Create Case')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create Case</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('cases.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Case Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection