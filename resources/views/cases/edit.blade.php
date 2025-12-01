@extends('layouts.main')
@section('title', 'Edit Case')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-edit mr-2"></i>Edit Case
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('cases.update', $case->id) }}" id="caseForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Case Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-gavel mr-2"></i>Case Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Case Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="case_number" 
                                           name="case_number" 
                                           class="form-control form-control-lg @error('case_number') is-invalid @enderror" 
                                           placeholder="Enter case number" 
                                           required 
                                           value="{{ old('case_number', $case->case_number) }}">
                                    @error('case_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Court Type <span class="text-danger">*</span>
                                    </label>
                                    <select id="court_type" 
                                            name="court_type" 
                                            class="form-control form-control-lg @error('court_type') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Court Type</option>
                                        <option value="High Court" {{ old('court_type', $case->court_type) == 'High Court' ? 'selected' : '' }}>High Court</option>
                                        <option value="Supreme Court" {{ old('court_type', $case->court_type) == 'Supreme Court' ? 'selected' : '' }}>Supreme Court</option>
                                        <option value="Session Court" {{ old('court_type', $case->court_type) == 'Session Court' ? 'selected' : '' }}>Session Court</option>
                                    </select>
                                    @error('court_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Case Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="case_title" 
                                           name="case_title" 
                                           class="form-control form-control-lg @error('case_title') is-invalid @enderror" 
                                           placeholder="Enter case title" 
                                           required 
                                           value="{{ old('case_title', $case->case_title) }}">
                                    @error('case_title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-info-circle mr-2"></i>Additional Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Entity
                                    </label>
                                    <select id="entity_id" 
                                            name="entity_id" 
                                            class="form-control form-control-lg @error('entity_id') is-invalid @enderror">
                                        <option value="">Select Entity</option>
                                        @foreach($entities as $entity)
                                            <option value="{{ $entity->id }}" {{ old('entity_id', $case->entity_id) == $entity->id ? 'selected' : '' }}>
                                                {{ $entity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('entity_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select id="status" 
                                            name="status" 
                                            class="form-control form-control-lg @error('status') is-invalid @enderror" 
                                            required>
                                        <option value="Open" {{ old('status', $case->status) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Closed" {{ old('status', $case->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg mr-2" style="background-color: #00349C; color: white;">
                                        <i class="fa fa-save mr-2"></i>Update Case
                                    </button>
                                    <a href="{{ route('cases.index') }}" class="btn btn-secondary btn-lg">
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
}

.btn-lg {
    border-radius: 6px;
    padding: 0.75rem 2rem;
    font-weight: 500;
}

</style>

@endsection

