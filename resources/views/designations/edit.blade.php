@extends('layouts.main')
@section('title', 'Edit Designation')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-edit mr-2"></i>Edit Designation
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('designations.update', $designation->id) }}" id="designationForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information Section -->
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
                                           placeholder="e.g., Senior Manager" 
                                           required 
                                           value="{{ old('name', $designation->name) }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Description
                                    </label>
                                    <textarea name="description" 
                                              id="description" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              rows="4" 
                                              placeholder="Enter detailed description of the designation...">{{ old('description', $designation->description) }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('designations') }}" class="btn btn-secondary btn-lg">
                                        <i class="fa fa-arrow-left mr-2"></i>Back to List
                                    </a>
                                    <div>
                                      
                                        <button type="submit" class="btn btn-lg" style="background-color: #00349C; color: white;">
                                            <i class="fa fa-save mr-2"></i>Update Designation
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

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
}

.border-bottom {
    border-color: #e3e6f0 !important;
}
</style>
@endsection