@extends('layouts.main')
@section('title','Edit Job Posting')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Job Posting</h4>
                        <a href="{{ route('job-posting.show', $jobPosting->id) }}" class="btn btn-outline-light btn-sm">
                            <i class="fa fa-eye mr-1"></i>View Details
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('job-posting.update', $jobPosting->id) }}" id="jobPostingForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">Basic Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Job Title <span class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control form-control-lg" placeholder="e.g., Senior Software Developer" required value="{{ old('title', $jobPosting->title) }}">
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
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $jobPosting->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <small class="text-danger">{{ $errors->first('department_id') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Designation <span class="text-danger">*</span></label>
                                    <select name="designation_id" id="designation_id" class="form-control form-control-lg" required>
                                        <option value="">Choose Designation</option>
                                        @foreach($designations ?? [] as $designation)
                                            <option value="{{ $designation->id }}" {{ old('designation_id', $jobPosting->designation_id) == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('designation_id'))
                                        <small class="text-danger">{{ $errors->first('designation_id') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Pay Scale <span class="text-danger">*</span></label>
                                    <select name="pay_scale" id="pay_scale" class="form-control form-control-lg" required>
                                        <option value="">Select Pay Scale</option>
                                        @for($i = 1; $i <= 21; $i++)
                                            <option value="GY-{{ $i }}" {{ old('pay_scale', $jobPosting->pay_scale) == 'GY-' . $i ? 'selected' : '' }}>
                                                GY-{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @if ($errors->has('pay_scale'))
                                        <small class="text-danger">{{ $errors->first('pay_scale') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Job Type <span class="text-danger">*</span></label>
                                    <select name="job_type" id="job_type" class="form-control form-control-lg" required>
                                        <option value="">Select Job Type</option>
                                        <option value="full_time" {{ old('job_type', $jobPosting->job_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="part_time" {{ old('job_type', $jobPosting->job_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ old('job_type', $jobPosting->job_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="temporary" {{ old('job_type', $jobPosting->job_type) == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                        <option value="internship" {{ old('job_type', $jobPosting->job_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                    @if ($errors->has('job_type'))
                                        <small class="text-danger">{{ $errors->first('job_type') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Gender Preference <span class="text-danger">*</span></label>
                                    <select name="gender" id="gender" class="form-control form-control-lg" required>
                                        <option value="">Select Gender Preference</option>
                                        <option value="any" {{ old('gender', $jobPosting->gender) == 'any' ? 'selected' : '' }}>Any Gender</option>
                                        <option value="male" {{ old('gender', $jobPosting->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $jobPosting->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <small class="text-danger">{{ $errors->first('gender') }}</small>
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
                                    <label class="font-weight-bold text-dark">Job Advertisement PDF</label>
                                    @if($jobPosting->job_advertisement)
                                        <div class="mb-2">
                                            <span class="text-success">
                                                <i class="fa fa-file-pdf mr-1"></i>
                                                Current file: 
                                                <a href="{{ Storage::url($jobPosting->job_advertisement) }}" target="_blank" class="text-primary">
                                                    <i class="fa fa-eye mr-1"></i>{{ basename($jobPosting->job_advertisement) }} (Click to view)
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                    <input type="file" id="job_advertisement" name="job_advertisement" class="form-control" accept=".pdf">
                                    <small class="text-muted">Upload new PDF file to replace current one (max 10MB). Leave empty to keep current file.</small>
                                    @if ($errors->has('job_advertisement'))
                                        <small class="text-danger">{{ $errors->first('job_advertisement') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Job Description <span class="text-danger">*</span></label>
                                    <div id="description-editor" class="form-control" style="min-height: 200px;">{{ old('description', $jobPosting->description) }}</div>
                                    <textarea id="description" name="description" style="display: none;">{{ old('description', $jobPosting->description) }}</textarea>
                                    @if ($errors->has('description'))
                                        <small class="text-danger">{{ $errors->first('description') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Requirements & Qualifications <span class="text-danger">*</span></label>
                                    <div id="requirements-editor" class="form-control" style="min-height: 200px;">{{ old('requirements', $jobPosting->requirements) }}</div>
                                    <textarea id="requirements" name="requirements" style="display: none;">{{ old('requirements', $jobPosting->requirements) }}</textarea>
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
                                    <input type="number" id="positions" name="positions" class="form-control" placeholder="1" min="1" required value="{{ old('positions', $jobPosting->positions) }}">
                                    @if ($errors->has('positions'))
                                        <small class="text-danger">{{ $errors->first('positions') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Age Limit <span class="text-danger">*</span></label>
                                    <input type="number" id="age_limit" name="age_limit" class="form-control" placeholder="e.g., 35" min="18" max="65" required value="{{ old('age_limit', $jobPosting->age_limit) }}">
                                    <small class="text-muted">Maximum age limit for applicants</small>
                                    @if ($errors->has('age_limit'))
                                        <small class="text-danger">{{ $errors->first('age_limit') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Application Deadline <span class="text-danger">*</span></label>
                                    <input type="date" id="deadline" name="deadline" class="form-control" required value="{{ old('deadline', $jobPosting->deadline->format('Y-m-d')) }}" min="">
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
                                        <option value="active" {{ old('status', $jobPosting->status) == 'active' ? 'selected' : '' }}> Active - Accepting Applications</option>
                                        <option value="draft" {{ old('status', $jobPosting->status) == 'draft' ? 'selected' : '' }}> Draft - Save for Later</option>
                                        <option value="inactive" {{ old('status', $jobPosting->status) == 'inactive' ? 'selected' : '' }}> Inactive - Not Accepting Applications</option>
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
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('job-posting.show', $jobPosting->id) }}" class="btn btn-light btn-lg mr-3">
                                            <i class="fa fa-times mr-2"></i>Cancel
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-lg text-white" style="background-color: #00349C; border-color: #00349C;">
                                            <i class="fa fa-save mr-2"></i>Update Job Posting
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

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for job description
    ClassicEditor
        .create(document.querySelector('#description-editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 
                     'bulletedList', 'numberedList', '|', 'link', 'blockQuote', '|', 
                     'undo', 'redo'],
            placeholder: 'Describe the role, responsibilities, and what the ideal candidate will do...',
            height: '200px'
        })
        .then(editor => {
            // Sync editor content with hidden textarea
            editor.model.document.on('change:data', () => {
                const data = editor.getData();
                document.getElementById('description').value = data;
            });
            
            // Set initial content if there's old input
            const oldContent = document.getElementById('description').value;
            if (oldContent) {
                editor.setData(oldContent);
            }
        })
        .catch(error => {
            console.error(error);
        });

    // Initialize CKEditor for requirements
    ClassicEditor
        .create(document.querySelector('#requirements-editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 
                     'bulletedList', 'numberedList', '|', 'link', 'blockQuote', '|', 
                     'undo', 'redo'],
            placeholder: 'List the required skills, experience, education, and qualifications...',
            height: '200px'
        })
        .then(editor => {
            // Sync editor content with hidden textarea
            editor.model.document.on('change:data', () => {
                const data = editor.getData();
                document.getElementById('requirements').value = data;
            });
            
            // Set initial content if there's old input
            const oldContent = document.getElementById('requirements').value;
            if (oldContent) {
                editor.setData(oldContent);
            }
        })
        .catch(error => {
            console.error(error);
        });

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