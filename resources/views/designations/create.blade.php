@extends('layouts.main')
@section('title', 'Create Designation')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-briefcase mr-2"></i>Create New Designation  
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('designations.store') }}" id="designationForm">
                        @csrf
                        
                        <!-- Designation Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-info-circle mr-2"></i>Designation Information
                                </h5>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Designation Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           placeholder="Enter designation name" 
                                           required 
                                           value="{{ old('name') }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-align-left mr-2"></i>Description
                                </h5>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Designation Description
                                    </label>
                                    <textarea name="description" 
                                              id="description" 
                                              class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                              rows="4" 
                                              placeholder="Enter designation description">{{ old('description') }}</textarea>
                                    @error('description')
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
                                        <a href="{{ route('designations') }}" class="btn btn-outline-secondary btn-lg mr-2">
                                            <i class="fa fa-times mr-1"></i>Cancel
                                        </a>
                                       
                                        <button type="submit" class="btn btn-lg" style="background-color: #00349C; color: white;">
                                            <i class="fa fa-save mr-1"></i>Create Designation
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
    // Form validation
    $('#designationForm').on('submit', function(e) {
        // Basic form validation - check if required fields are filled
        var requiredFields = ['name'];
        var isValid = true;
        
        requiredFields.forEach(function(field) {
            var value = $('#' + field).val();
            if (!value || value.trim() === '') {
                $('#' + field).addClass('is-invalid');
                isValid = false;
            } else {
                $('#' + field).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });
});
</script>
@endsection

