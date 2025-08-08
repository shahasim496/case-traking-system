@extends('layouts.main')
@section('title', 'Manage Role Permissions')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-user-tag mr-2"></i>Manage Role Permissions
                    </h4>
                    <a href="{{ route('roles') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-arrow-left mr-1"></i>Back to Roles
                    </a>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <!-- Role Selection Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                <i class="fa fa-filter mr-2"></i>Select Role
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('roles.managePermissions') }}" method="GET">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Choose a Role</label>
                                    <select name="role_id" id="role" class="form-control form-control-lg" onchange="this.form.submit()">
                                        <option value="">Select a Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $request->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($request->role_id)
                    <!-- Permissions Assignment Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                <i class="fa fa-shield mr-2"></i>Assign Permissions
                            </h5>
                            
                            <form action="{{ route('roles.storeAssignedPermissions') }}" method="POST">
                                @csrf
                                <input type="hidden" name="role_id" value="{{ $request->role_id }}">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="badge badge-primary p-2">
                                            <i class="fa fa-info-circle mr-1"></i>
                                            Check the permissions you want to assign to this role
                                        </span>
                                    </div>
                                    <button type="submit" class="btn" style="background-color: #00349C; color: white;">
                                        <i class="fa fa-save mr-1"></i>Save Permissions
                                    </button>
                                </div>

                                <!-- Group permissions by category -->
                                @php
                                    $categories = [
                                        'user' => ['name' => 'User Management', 'badge' => 'badge-success', 'icon' => 'fa fa-users'],
                                        'role' => ['name' => 'Role Management', 'badge' => 'badge-primary', 'icon' => 'fa fa-user-tag'],
                                        'permission' => ['name' => 'Permission Management', 'badge' => 'badge-info', 'icon' => 'fa fa-shield'],
                                        'settings' => ['name' => 'System Settings', 'badge' => 'badge-secondary', 'icon' => 'fa fa-cog'],
                                        'evidence' => ['name' => 'Evidence Management', 'badge' => 'badge-warning', 'icon' => 'fa fa-file-text'],
                                        'other' => ['name' => 'Other Permissions', 'badge' => 'badge-light', 'icon' => 'fa fa-ellipsis-h']
                                    ];
                                    
                                    $groupedPermissions = [];
                                    foreach($permissions as $permission) {
                                        $category = 'other';
                                        foreach($categories as $key => $cat) {
                                            if($key !== 'other' && Str::contains($permission->name, $key)) {
                                                $category = $key;
                                                break;
                                            }
                                        }
                                        $groupedPermissions[$category][] = $permission;
                                    }
                                @endphp

                                @foreach($categories as $categoryKey => $category)
                                    @if(isset($groupedPermissions[$categoryKey]) && count($groupedPermissions[$categoryKey]) > 0)
                                    <div class="permission-category mb-4">
                                        <div class="category-header p-3 mb-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px;">
                                            <div class="d-flex align-items-center">
                                                <i class="{{ $category['icon'] }} mr-2" style="color: #00349C;"></i>
                                                <h6 class="mb-0 font-weight-bold" style="color: #00349C;">
                                                    {{ $category['name'] }}
                                                    <span class="{{ $category['badge'] }} ml-2">{{ count($groupedPermissions[$categoryKey]) }} permissions</span>
                                                </h6>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            @foreach($groupedPermissions[$categoryKey] as $permission)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="permission-card p-3 border rounded h-100" style="background-color: #fff; transition: all 0.2s ease;">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-2 font-weight-bold text-dark">{{ $permission->name }}</h6>
                                                            <small class="text-muted">Permission ID: {{ $permission->id }}</small>
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" 
                                                                       class="custom-control-input" 
                                                                       id="permission_{{ $permission->id }}" 
                                                                       name="permissions[]" 
                                                                       value="{{ $permission->id }}" 
                                                                       {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="permission_{{ $permission->id }}"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if(in_array($permission->id, $assignedPermissions))
                                                            <span class="badge badge-success badge-sm">
                                                                <i class="fa fa-check mr-1"></i>Assigned
                                                            </span>
                                                        @else
                                                            <span class="badge badge-light badge-sm">
                                                                <i class="fa fa-times mr-1"></i>Not Assigned
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- No Role Selected Message -->
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fa fa-user-tag fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Select a Role</h5>
                                <p class="text-muted">Please select a role from the dropdown above to manage its permissions.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border-bottom: none;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #00349C;
    box-shadow: 0 0 0 0.2rem rgba(0, 52, 156, 0.25);
}

.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.border-bottom {
    border-color: #e3e6f0 !important;
}

.table {
    border-radius: 8px;
    overflow: hidden;
}

.thead-dark th {
    background-color: #343a40;
    border-color: #454d55;
    color: white;
}

.badge {
    font-size: 0.75em;
    border-radius: 4px;
    padding: 0.5em 0.75em;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #00349C;
    border-color: #00349C;
}

.custom-control-input:focus ~ .custom-control-label::before {
    box-shadow: 0 0 0 0.2rem rgba(0, 52, 156, 0.25);
}

/* Permission category styling */
.permission-category {
    margin-bottom: 2rem;
}

.category-header {
    border-left: 4px solid #00349C;
}

.permission-card {
    border: 1px solid #e3e6f0 !important;
    transition: all 0.3s ease;
}

.permission-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #00349C !important;
}

.custom-switch .custom-control-label::before {
    background-color: #e9ecef;
    border-color: #adb5bd;
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #00349C;
    border-color: #00349C;
}

.custom-switch .custom-control-input:focus ~ .custom-control-label::before {
    box-shadow: 0 0 0 0.2rem rgba(0, 52, 156, 0.25);
}

.badge-sm {
    font-size: 0.7em;
    padding: 0.3em 0.6em;
}

@media (max-width: 768px) {
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: stretch !important;
    }
    
    .d-flex.justify-content-between > div {
        margin-bottom: 1rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Update status badge when toggle switch changes
    $('.custom-control-input').on('change', function() {
        var card = $(this).closest('.permission-card');
        var statusBadge = card.find('.badge');
        
        if ($(this).is(':checked')) {
            statusBadge.removeClass('badge-light').addClass('badge-success');
            statusBadge.html('<i class="fa fa-check mr-1"></i>Assigned');
        } else {
            statusBadge.removeClass('badge-success').addClass('badge-light');
            statusBadge.html('<i class="fa fa-times mr-1"></i>Not Assigned');
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        var checkedPermissions = $('input[name="permissions[]"]:checked').length;
        if (checkedPermissions === 0) {
            e.preventDefault();
            alert('Please select at least one permission to assign.');
            return false;
        }
    });
});
</script>
@endsection