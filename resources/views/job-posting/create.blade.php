@extends('layouts.main')
@section('title','Add Job Posting')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">Create New Job Posting</h4>
                </div>
                                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('job-posting.store') }}" id="jobPostingForm">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">Basic Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Job Title <span class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control form-control-lg" placeholder="e.g., Senior Software Developer" required value="{{ old('title') }}">
                                    @if ($errors->has('title'))
                                        <small class="text-danger">{{ $errors->first('title') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Department <span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-control form-control-lg" required>
                                        <option value="">Choose Department</option>
                                        @foreach($departments ?? [] as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <small class="text-danger">{{ $errors->first('department_id') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Job Details Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">Job Details</h5>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Job Description <span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" class="form-control" rows="5" placeholder="Describe the role, responsibilities, and what the ideal candidate will do..." required>{{ old('description') }}</textarea>
                                    @if ($errors->has('description'))
                                        <small class="text-danger">{{ $errors->first('description') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Requirements & Qualifications <span class="text-danger">*</span></label>
                                    <textarea id="requirements" name="requirements" class="form-control" rows="4" placeholder="List the required skills, experience, education, and qualifications..." required>{{ old('requirements') }}</textarea>
                                    @if ($errors->has('requirements'))
                                        <small class="text-danger">{{ $errors->first('requirements') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">Additional Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Number of Positions <span class="text-danger">*</span></label>
                                    <input type="number" id="positions" name="positions" class="form-control" placeholder="1" min="1" required value="{{ old('positions', 1) }}">
                                    @if ($errors->has('positions'))
                                        <small class="text-danger">{{ $errors->first('positions') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Application Deadline <span class="text-danger">*</span></label>
                                    <input type="date" id="deadline" name="deadline" class="form-control" required value="{{ old('deadline') }}" min="">
                                    <small class="text-muted">Deadline must be at least 15 days from today</small>
                                    <div id="deadlineError" class="text-danger" style="display: none;"></div>
                                    @if ($errors->has('deadline'))
                                        <small class="text-danger">{{ $errors->first('deadline') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">Publishing</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}> Active - Accepting Applications</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}> Draft - Save for Later</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}> Inactive - Not Accepting Applications</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <small class="text-danger">{{ $errors->first('status') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('job-posting.index') }}" class="btn btn-light btn-lg mr-3">
                                        <i class="fa fa-times mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-lg text-white" style="background-color: #00349C; border-color: #00349C;">
                                        Create Job Posting
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deadlineInput = document.getElementById('deadline');
    const deadlineError = document.getElementById('deadlineError');
    const form = document.getElementById('jobPostingForm');
    
    // Set minimum date to 15 days from today
    const today = new Date();
    const minDate = new Date(today);
    minDate.setDate(today.getDate() + 15);
    
    const minDateString = minDate.toISOString().split('T')[0];
    deadlineInput.setAttribute('min', minDateString);
    
    // Validate deadline on input change
    deadlineInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const currentDate = new Date();
        const daysDifference = Math.ceil((selectedDate - currentDate) / (1000 * 60 * 60 * 24));
        
        if (daysDifference < 15) {
            deadlineError.textContent = 'Deadline must be at least 15 days from today';
            deadlineError.style.display = 'block';
            this.setCustomValidity('Deadline must be at least 15 days from today');
        } else {
            deadlineError.style.display = 'none';
            this.setCustomValidity('');
        }
    });
    
    // Validate on form submission
    form.addEventListener('submit', function(e) {
        const selectedDate = new Date(deadlineInput.value);
        const currentDate = new Date();
        const daysDifference = Math.ceil((selectedDate - currentDate) / (1000 * 60 * 60 * 24));
        
        if (daysDifference < 15) {
            e.preventDefault();
            deadlineError.textContent = 'Deadline must be at least 15 days from today';
            deadlineError.style.display = 'block';
            deadlineInput.focus();
        }
    });
});
</script>

@endsection