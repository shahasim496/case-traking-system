@extends('layouts.main')
@section('title', 'Add Case')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-plus-circle mr-2"></i>Add New Case
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('cases.store') }}" id="caseForm">
                        @csrf
                        
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
                                           value="{{ old('case_number') }}">
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
                                        <option value="High Court" {{ old('court_type') == 'High Court' ? 'selected' : '' }}>High Court</option>
                                        <option value="Supreme Court" {{ old('court_type') == 'Supreme Court' ? 'selected' : '' }}>Supreme Court</option>
                                        <option value="Session Court" {{ old('court_type') == 'Session Court' ? 'selected' : '' }}>Session Court</option>
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
                                           value="{{ old('case_title') }}">
                                    @error('case_title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Party Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="border-bottom pb-2 mb-0" style="color: #00349C;">
                                        <i class="fa fa-users mr-2"></i>Party Information
                                    </h5>
                                    <button type="button" class="btn btn-sm btn-success" id="addPartyBtn">
                                        <i class="fa fa-plus mr-1"></i>Add Party
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12" id="partiesContainer">
                                <div class="party-item mb-3 p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark">
                                                    Party Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="parties[0][party_name]" 
                                                       class="form-control form-control-lg @error('parties.0.party_name') is-invalid @enderror" 
                                                       placeholder="Enter party name" 
                                                       required 
                                                       value="{{ old('parties.0.party_name') }}">
                                                @error('parties.0.party_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark">
                                                    Party Details
                                                </label>
                                                <textarea name="parties[0][party_details]" 
                                                          class="form-control form-control-lg @error('parties.0.party_details') is-invalid @enderror" 
                                                          rows="2" 
                                                          placeholder="Enter party details...">{{ old('parties.0.party_details') }}</textarea>
                                                @error('parties.0.party_details')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark d-block">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm remove-party-btn" style="display: none;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @error('parties')
                                <div class="col-12">
                                    <small class="text-danger">{{ $message }}</small>
                                </div>
                            @enderror
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
                                            <option value="{{ $entity->id }}" {{ old('entity_id') == $entity->id ? 'selected' : '' }}>
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
                                        <option value="Open" {{ old('status', 'Open') == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
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
                                        <i class="fa fa-save mr-2"></i>Save Case
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

.party-item {
    background-color: #f8f9fa;
}

.party-item:first-child .remove-party-btn {
    display: none !important;
}
</style>

<script>
$(document).ready(function() {
    let partyIndex = {{ count(old('parties', [['party_name' => '', 'party_details' => '']])) }};
    
    // Add new party
    $('#addPartyBtn').click(function() {
        const partyHtml = `
            <div class="party-item mb-3 p-3 border rounded">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                Party Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="parties[${partyIndex}][party_name]" 
                                   class="form-control form-control-lg" 
                                   placeholder="Enter party name" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                Party Details
                            </label>
                            <textarea name="parties[${partyIndex}][party_details]" 
                                      class="form-control form-control-lg" 
                                      rows="2" 
                                      placeholder="Enter party details..."></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-party-btn">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#partiesContainer').append(partyHtml);
        partyIndex++;
        updateRemoveButtons();
    });
    
    // Remove party
    $(document).on('click', '.remove-party-btn', function() {
        $(this).closest('.party-item').remove();
        updateRemoveButtons();
    });
    
    // Update remove buttons visibility
    function updateRemoveButtons() {
        const partyItems = $('.party-item');
        if (partyItems.length > 1) {
            partyItems.each(function() {
                $(this).find('.remove-party-btn').show();
            });
            // Hide remove button for first item
            partyItems.first().find('.remove-party-btn').hide();
        } else {
            partyItems.find('.remove-party-btn').hide();
        }
    }
    
    // Initialize
    updateRemoveButtons();
});
</script>
@endsection

