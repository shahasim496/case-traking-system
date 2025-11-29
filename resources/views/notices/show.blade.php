@extends('layouts.main')
@section('title', 'Notice Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-file-text mr-2"></i>Notice Details
                    </h4>
                    <div>
                      
                        <a href="{{ route('cases.show', $notice->case_id) }}" class="btn btn-light btn-sm">
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
                                <p class="mb-0">{{ $notice->courtCase->case_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Case Title:</strong>
                                <p class="mb-0">{{ $notice->courtCase->case_title }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notice Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                            <i class="fa fa-file-text mr-2"></i>Notice Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Notice Date:</strong>
                                <p class="mb-0">{{ $notice->notice_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Created By:</strong>
                                <p class="mb-0">{{ $notice->creator->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Notice Details:</strong>
                                <p class="mb-0">{{ $notice->notice_details ?? '-' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Attachment:</strong>
                                <div class="mt-2">
                                    @if($notice->attachment)
                                      
                                        <a href="{{ Storage::url($notice->attachment) }}" target="_blank" class="btn" style="background-color: #17a2b8; color: white;">
                                            <i class="fa fa-download mr-2"></i>Download Attachment
                                        </a>
                                    @else
                                        <p class="text-muted mb-0">No attachment available</p>
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
                                <p class="mb-0">{{ $notice->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Updated At:</strong>
                                <p class="mb-0">{{ $notice->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                @if(auth()->user()->can('edit notice'))
                                <a href="{{ route('notices.edit', $notice->id) }}" class="btn btn-warning mr-2">
                                    <i class="fa fa-edit mr-2"></i>Edit Notice
                                </a>
                                @endif
                                @if(auth()->user()->can('delete notice'))
                                <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#deleteNoticeModal">
                                    <i class="fa fa-trash mr-2"></i>Delete Notice
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Notice Modal -->
<div class="modal fade" id="deleteNoticeModal" tabindex="-1" role="dialog" aria-labelledby="deleteNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #dc3545;">
                <h5 class="modal-title" id="deleteNoticeModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Delete Notice
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('notices.destroy', $notice->id) }}" id="deleteNoticeForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this notice?</p>
                    <p class="mb-0"><strong>Notice Date:</strong> {{ $notice->notice_date->format('d M Y') }}</p>
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

