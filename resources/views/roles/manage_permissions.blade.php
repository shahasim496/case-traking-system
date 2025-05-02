@extends('layouts.main')
@section('title', 'Manage Permissions')


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

            <div class="text-right mb-3">
            <button type="submit" class="btn btn-success">Assign Permissions</button>
        </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Permission Name</th>
                            <th>Assigned</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $index => $permission)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if(Str::contains($permission->name, 'investigation'))
                                    Investigation
                                @elseif(Str::contains($permission->name, 'evidence'))
                                    Evidence
                                @elseif(Str::contains($permission->name, 'witness'))
                                    Witness
                                @elseif(Str::contains($permission->name, 'court'))
                                    Court Proceedings
                                @else
                                    Other
                                @endif
                            </td>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                    {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

           
        </form>
        @endif
    </div>
</div>
@endsection