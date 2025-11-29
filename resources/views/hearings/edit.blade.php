@extends('layouts.main')
@section('title', 'Edit Hearing')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-edit mr-2"></i>Edit Hearing
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
                                <p class="mb-0">{{ $hearing->courtCase->case_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Case Title:</strong>
                                <p class="mb-0">{{ $hearing->courtCase->case_title }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('hearings.update', $hearing->id) }}" enctype="multipart/form-data" id="hearingForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hearing Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-calendar mr-2"></i>Hearing Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Hearing Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           id="hearing_date" 
                                           name="hearing_date" 
                                           class="form-control form-control-lg @error('hearing_date') is-invalid @enderror" 
                                           required 
                                           value="{{ old('hearing_date', $hearing->hearing_date->format('Y-m-d')) }}">
                                    @error('hearing_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Next Hearing Date
                                    </label>
                                    <input type="date" 
                                           id="next_hearing_date" 
                                           name="next_hearing_date" 
                                           class="form-control form-control-lg @error('next_hearing_date') is-invalid @enderror" 
                                           value="{{ old('next_hearing_date', $hearing->next_hearing_date ? $hearing->next_hearing_date->format('Y-m-d') : '') }}">
                                    @error('next_hearing_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Person Appearing
                                    </label>
                                    <input type="text" 
                                           id="person_appearing" 
                                           name="person_appearing" 
                                           class="form-control form-control-lg @error('person_appearing') is-invalid @enderror" 
                                           placeholder="Enter person appearing in court..." 
                                           value="{{ old('person_appearing', $hearing->person_appearing) }}">
                                    @error('person_appearing')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Current Court Order
                                    </label>
                                    <div class="mt-2">
                                        @if($hearing->court_order)
                                            <a href="{{ Storage::url($hearing->court_order) }}" target="_blank" class="btn btn-sm btn-info mb-2">
                                                <i class="fa fa-download mr-1"></i>View Current Court Order
                                            </a>
                                            <p class="text-muted small mb-0">Upload a new file to replace the current court order</p>
                                        @else
                                            <p class="text-muted mb-0">No court order currently</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Purpose
                                    </label>
                                    <textarea id="purpose" 
                                              name="purpose" 
                                              class="form-control form-control-lg @error('purpose') is-invalid @enderror" 
                                              rows="3" 
                                              placeholder="Enter hearing purpose...">{{ old('purpose', $hearing->purpose) }}</textarea>
                                    @error('purpose')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Outcome
                                    </label>
                                    <textarea id="outcome" 
                                              name="outcome" 
                                              class="form-control form-control-lg @error('outcome') is-invalid @enderror" 
                                              rows="3" 
                                              placeholder="Enter hearing outcome...">{{ old('outcome', $hearing->outcome) }}</textarea>
                                    @error('outcome')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Court Order
                                    </label>
                                    <input type="file" 
                                           id="court_order" 
                                           name="court_order" 
                                           class="form-control form-control-lg @error('court_order') is-invalid @enderror" 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                                    @error('court_order')
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
                                        <i class="fa fa-save mr-2"></i>Update Hearing
                                    </button>
                                    <a href="{{ route('hearings.show', $hearing->id) }}" class="btn btn-secondary btn-lg mr-2">
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

