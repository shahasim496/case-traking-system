@extends('layouts.main')
@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-user-edit mr-2"></i>Edit User: {{ $user->name }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('user.update', $user->id) }}" id="userForm" enctype='multipart/form-data'>
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-user mr-2"></i>Personal Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           placeholder="Enter full name" 
                                           required 
                                           value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        National ID <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="cnic" 
                                           name="cnic" 
                                           class="form-control form-control-lg @error('cnic') is-invalid @enderror" 
                                           placeholder="38302-6327920-5" 
                                           required 
                                           value="{{ old('cnic', $user->cnic) }}"
                                           pattern="\d{5}-\d{7}-\d{1}"
                                           title="Format: XXXXX-XXXXXXX-X (e.g., 38302-6327920-5)">
                                    <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                                    @error('cnic')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-phone mr-2"></i>Contact Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           placeholder="user@example.com" 
                                           required 
                                           value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="phone" 
                                           name="phone" 
                                           class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                           placeholder="+923049972964" 
                                           required 
                                           value="{{ old('phone', $user->phone) }}"
                                           pattern="\+92\d{10}"
                                           title="Format: +92XXXXXXXXXX (e.g., +923049972964)">
                                    <small class="text-muted">Format: +92XXXXXXXXXX</small>
                                    @error('phone')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Organizational Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-building mr-2"></i>Organizational Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    <select name="department_id" 
                                            id="department_id" 
                                            class="form-control form-control-lg @error('department_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Designation <span class="text-danger">*</span>
                                    </label>
                                    <select name="designation_id" 
                                            id="designation_id" 
                                            class="form-control form-control-lg @error('designation_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Designation</option>
                                        @foreach($designations as $designation)
                                            <option value="{{ $designation->id }}" {{ old('designation_id', $user->designation_id) == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('designation_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Security & Access Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-shield mr-2"></i>Security & Access
                                </h5>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        User Roles (Access Permissions) <span class="text-danger">*</span>
                                    </label>
                                    <select name="roles[]" 
                                            id="roles" 
                                            class="form-control form-control-lg @error('roles') is-invalid @enderror" 
                                            multiple 
                                            required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl (or Cmd on Mac) to select multiple roles</small>
                                    @error('roles')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div>
                                        <a href="{{ route('users') }}" class="btn btn-outline-secondary btn-lg mr-2">
                                            <i class="fa fa-times mr-1"></i>Cancel
                                        </a>
                                      
                                        <button type="submit" class="btn btn-lg" style="background-color: #00349C; color: white;">
                                            <i class="fa fa-save mr-1"></i>Update User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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

select[multiple] {
    min-height: 120px;
}

.text-danger {
    font-weight: 500;
}

@media (max-width: 768px) {
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .d-flex.justify-content-end {
        justify-content: center !important;
    }
    
    .d-flex.justify-content-end > div {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    .d-flex.justify-content-end .btn {
        margin-bottom: 0.5rem;
        margin-right: 0 !important;
    }
}
</style>

<script>
$(document).ready(function() {
    // National ID formatting: XXXXX-XXXXXXX-X
    $('#cnic').on('input', function() {
        var value = $(this).val().replace(/[^\d]/g, ''); // Remove all non-digits
        
        // Format as XXXXX-XXXXXXX-X
        if (value.length > 0) {
            if (value.length <= 5) {
                value = value;
            } else if (value.length <= 12) {
                value = value.substring(0, 5) + '-' + value.substring(5);
            } else {
                value = value.substring(0, 5) + '-' + value.substring(5, 12) + '-' + value.substring(12, 13);
            }
        }
        
        $(this).val(value);
    });
    
    // Phone number formatting: +92XXXXXXXXXX
    $('#phone').on('input', function() {
        var value = $(this).val();
        
        // Remove all non-digit characters
        value = value.replace(/[^\d+]/g, '');
        
        // Ensure it starts with +92
        if (value.length === 0) {
            value = '+92';
        } else if (!value.startsWith('+92')) {
            // If it doesn't start with +92, add it
            if (value.startsWith('92')) {
                value = '+' + value;
            } else if (value.startsWith('0')) {
                // If starts with 0, replace with +92
                value = '+92' + value.substring(1);
            } else {
                value = '+92' + value;
            }
        }
        
        // Limit to +92 followed by 10 digits (total 13 characters)
        if (value.length > 13) {
            value = value.substring(0, 13);
        }
        
        $(this).val(value);
    });
    
    // Form validation
    $('#userForm').on('submit', function(e) {
        var isValid = true;
        var errorMessages = [];
        
        // Validate CNIC format: XXXXX-XXXXXXX-X
        var cnic = $('#cnic').val();
        var cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
        if (!cnicPattern.test(cnic)) {
            $('#cnic').addClass('is-invalid');
            errorMessages.push('National ID must be in format: XXXXX-XXXXXXX-X (e.g., 38302-6327920-5)');
            isValid = false;
        } else {
            $('#cnic').removeClass('is-invalid');
        }
        
        // Validate Phone format: +92XXXXXXXXXX
        var phone = $('#phone').val();
        var phonePattern = /^\+92\d{10}$/;
        if (!phonePattern.test(phone)) {
            $('#phone').addClass('is-invalid');
            errorMessages.push('Phone number must be in format: +92XXXXXXXXXX (e.g., +923049972964)');
            isValid = false;
        } else {
            $('#phone').removeClass('is-invalid');
        }
        
        // Check required fields
        var requiredFields = ['name', 'email', 'department_id', 'designation_id'];
        requiredFields.forEach(function(field) {
            var value = $('#' + field).val();
            if (!value || value.trim() === '') {
                $('#' + field).addClass('is-invalid');
                isValid = false;
            } else {
                $('#' + field).removeClass('is-invalid');
            }
        });
        
        // Check if at least one role is selected
        var selectedRoles = $('#roles').val();
        if (!selectedRoles || selectedRoles.length === 0) {
            $('#roles').addClass('is-invalid');
            isValid = false;
        } else {
            $('#roles').removeClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            if (errorMessages.length > 0) {
                alert(errorMessages.join('\n'));
            } else {
                alert('Please fill in all required fields and select at least one role.');
            }
            return false;
        }
    });
});
</script>
@endsection
