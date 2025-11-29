@extends('layouts.main')
@section('title', 'Edit Notice')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-edit mr-2"></i>Edit Notice
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <!-- Case Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                            <i class="fa fa-gavel mr-2"></i>Case Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Case Number:</strong>
                                <p class="mb-0">{{ $notice->courtCase->case_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Case Title:</strong>
                                <p class="mb-0">{{ $notice->courtCase->case_title }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('notices.update', $notice->id) }}" enctype="multipart/form-data" id="noticeForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Notice Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-file-text mr-2"></i>Notice Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Notice Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           id="notice_date" 
                                           name="notice_date" 
                                           class="form-control form-control-lg @error('notice_date') is-invalid @enderror" 
                                           required 
                                           value="{{ old('notice_date', $notice->notice_date->format('Y-m-d')) }}">
                                    @error('notice_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Current Attachment
                                    </label>
                                    <div class="mt-2">
                                        @if($notice->attachment)
                                            <a href="{{ Storage::url($notice->attachment) }}" target="_blank" class="btn btn-sm btn-info mb-2">
                                                <i class="fa fa-download mr-1"></i>View Current Attachment
                                            </a>
                                            <p class="text-muted small mb-0">Upload a new file to replace the current attachment</p>
                                        @else
                                            <p class="text-muted mb-0">No attachment currently</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Notice Details
                                    </label>
                                    <textarea id="notice_details" 
                                              name="notice_details" 
                                              class="form-control form-control-lg @error('notice_details') is-invalid @enderror" 
                                              rows="5" 
                                              placeholder="Enter notice details...">{{ old('notice_details', $notice->notice_details) }}</textarea>
                                    @error('notice_details')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Attachment
                                    </label>
                                    <input type="file" 
                                           id="attachment" 
                                           name="attachment" 
                                           class="form-control form-control-lg @error('attachment') is-invalid @enderror" 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                                    @error('attachment')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg mr-2" style="background-color: #00349C; color: white;">
                                        <i class="fa fa-save mr-2"></i>Update Notice
                                    </button>
                                    <a href="{{ route('notices.show', $notice->id) }}" class="btn btn-secondary btn-lg mr-2">
                                        <i class="fa fa-times mr-2"></i>Cancel
                                    </a>
                                   
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

.form-control-lg {
    border-radius: 6px;
    padding: 0.75rem 1rem;
}

.btn-lg {
    border-radius: 6px;
    padding: 0.75rem 2rem;
    font-weight: 500;
}
</style>
@endsection

