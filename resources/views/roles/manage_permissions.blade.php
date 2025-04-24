@extends('layouts.main')
@section('title', 'Manage Permissions')
@section('breadcrumb', 'Manage Permissions')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Manage Permissions</h4>
    </div>
    <div class="card-body">
        <!-- Role Selection Form -->
        <form action="{{ route('roles.managePermissions') }}" method="GET">
            <div class="form-group">
                <label for="role">Select Role</label>
                <select name="role_id" id="role" class="form-control" onchange="this.form.submit()">
                    <option value="">Select a Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $request->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @if($request->role_id)
        <!-- Permissions Assignment Form -->
        <form action="{{ route('roles.storeAssignedPermissions') }}" method="POST">
            @csrf
            <input type="hidden" name="role_id" value="{{ $request->role_id }}">
            <div class="form-group">
                <label for="permissions">Permissions</label>
                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                    {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                <label>{{ $permission->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-success">Assign Permissions</button>
        </form>
        @endif
    </div>
</div>
@endsection