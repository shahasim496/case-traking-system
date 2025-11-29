@extends('layouts.main')
@section('title', 'Hearing Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-calendar mr-2"></i>Hearing Details
                    </h4>
                    <div>
                        <a href="{{ route('cases.show', $hearing->case_id) }}" class="btn btn-light btn-sm">
                            <i class="fa fa-arrow-left mr-1"></i>Back to Case
                        </a>
                    </div>
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
                    
                    <!-- Hearing Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                            <i class="fa fa-calendar mr-2"></i>Hearing Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Hearing Date:</strong>
                                <p class="mb-0">{{ $hearing->hearing_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Next Hearing Date:</strong>
                                <p class="mb-0">{{ $hearing->next_hearing_date ? $hearing->next_hearing_date->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Person Appearing:</strong>
                                <p class="mb-0">{{ $hearing->person_appearing ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Created By:</strong>
                                <p class="mb-0">{{ $hearing->creator->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Purpose:</strong>
                                <p class="mb-0">{{ $hearing->purpose ?? '-' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Outcome:</strong>
                                <p class="mb-0">{{ $hearing->outcome ?? '-' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Court Order:</strong>
                                <div class="mt-2">
                                    @if($hearing->court_order)
                                        <a href="{{ Storage::url($hearing->court_order) }}" target="_blank" class="btn btn-info">
                                            <i class="fa fa-download mr-2"></i>Download Court Order
                                        </a>
                                    @else
                                        <p class="text-muted mb-0">No court order available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Audit Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                            <i class="fa fa-info-circle mr-2"></i>Audit Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Created At:</strong>
                                <p class="mb-0">{{ $hearing->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Updated At:</strong>
                                <p class="mb-0">{{ $hearing->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('hearings.edit', $hearing->id) }}" class="btn btn-warning mr-2">
                                    <i class="fa fa-edit mr-2"></i>Edit Hearing
                                </a>
                                <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#deleteHearingModal">
                                    <i class="fa fa-trash mr-2"></i>Delete Hearing
                                </button>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Hearing Modal -->
<div class="modal fade" id="deleteHearingModal" tabindex="-1" role="dialog" aria-labelledby="deleteHearingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #dc3545;">
                <h5 class="modal-title" id="deleteHearingModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Delete Hearing
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('hearings.destroy', $hearing->id) }}" id="deleteHearingForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this hearing?</p>
                    <p class="mb-0"><strong>Hearing Date:</strong> {{ $hearing->hearing_date->format('d M Y') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
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

.btn {
    border-radius: 6px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
}
</style>
@endsection

