@extends('layouts.main')
@section('title', $jobPosting->title)

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $jobPosting->title }}</h4>
                        <div>
                           
                            <a href="{{ route('job-posting.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fa fa-arrow-left mr-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <!-- Job Status and Meta Information -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3 flex-wrap">
                                <span class="badge badge-info mr-3">{{ $jobPosting->department->name ?? 'N/A' }}</span>
                                <span class="badge badge-primary mr-3">{{ $jobPosting->designation->name ?? 'N/A' }}</span>
                                <span class="badge badge-success mr-3">{{ $jobPosting->pay_scale ?? 'N/A' }}</span>
                                <span class="badge badge-warning mr-3">{{ ucwords(str_replace('_', ' ', $jobPosting->job_type ?? 'N/A')) }}</span>
                                <span class="badge badge-secondary">{{ $jobPosting->positions }} Position(s)</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <small class="text-muted">
                                Created: {{ $jobPosting->created_at->format('M d, Y') }}
                                @if($jobPosting->createdBy)
                                    <br>by {{ $jobPosting->createdBy->name ?? 'Unknown' }}
                                @endif
                            </small>
                        </div>
                    </div>

                    <!-- Application Deadline -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-clock mr-3 fa-2x"></i>
                                    <div>
                                        <strong>Application Deadline:</strong> 
                                        @php
                                            $deadline = \Carbon\Carbon::parse($jobPosting->deadline);
                                            $now = \Carbon\Carbon::now();
                                            $isExpired = $deadline->isPast();
                                            $isNearExpiry = $deadline->diffInDays($now) <= 7;
                                        @endphp
                                        
                                        @if($isExpired)
                                            <span class="text-danger">{{ $deadline->format('F d, Y') }} (Expired)</span>
                                        @elseif($isNearExpiry)
                                            <span class="text-warning">{{ $deadline->format('F d, Y') }} (Expires Soon)</span>
                                        @else
                                            <span class="text-success">{{ $deadline->format('F d, Y') }}</span>
                                        @endif
                                        
                                        @if(!$isExpired)
                                            <br><small>{{ $deadline->diffForHumans() }} remaining</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Advertisement -->
                    @if($jobPosting->job_advertisement)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                <i class="fa fa-file-pdf mr-2"></i>Job Advertisement
                            </h5>
                            <div class="bg-light p-3 rounded text-center">
                                <div class="mb-3">
                                    <i class="fa fa-file-pdf fa-3x text-danger"></i>
                                </div>
                                <h6>Official Job Advertisement</h6>
                                <p class="text-muted">View the complete job advertisement with all details</p>
                                <a href="{{ Storage::url($jobPosting->job_advertisement) }}" 
                                   target="_blank" 
                                   class="btn btn-danger">
                                    <i class="fa fa-eye mr-2"></i>View PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Job Description -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                <i class="fa fa-file-text mr-2"></i>Job Description
                            </h5>
                            <div class="bg-light p-3 rounded">
                                {!! $jobPosting->description !!}
                            </div>
                        </div>
                    </div>

                    <!-- Requirements & Qualifications -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                <i class="fa fa-list-check mr-2"></i>Requirements & Qualifications
                            </h5>
                            <div class="bg-light p-3 rounded">
                                {!! $jobPosting->requirements !!}
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                <i class="fa fa-info-circle mr-2"></i>Job Details
                            </h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Department:</strong></td>
                                    <td>{{ $jobPosting->department->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Designation:</strong></td>
                                    <td>{{ $jobPosting->designation->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pay Scale:</strong></td>
                                    <td>{{ $jobPosting->pay_scale ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Job Type:</strong></td>
                                    <td>{{ ucwords(str_replace('_', ' ', $jobPosting->job_type ?? 'N/A')) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender Preference:</strong></td>
                                    <td>{{ ucwords($jobPosting->gender ?? 'N/A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Age Limit:</strong></td>
                                    <td>{{ $jobPosting->age_limit ?? 'N/A' }} years</td>
                                </tr>
                                <tr>
                                    <td><strong>Positions Available:</strong></td>
                                    <td>{{ $jobPosting->positions }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($jobPosting->status == 'active')
                                            <span class="badge badge-success">Active - Accepting Applications</span>
                                        @elseif($jobPosting->status == 'draft')
                                            <span class="badge badge-warning">Draft - Not Published</span>
                                        @else
                                            <span class="badge badge-danger">Inactive - Not Accepting Applications</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $jobPosting->created_at->format('F d, Y \a\t g:i A') }}</td>
                                </tr>
                                @if($jobPosting->updated_at != $jobPosting->created_at)
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $jobPosting->updated_at->format('F d, Y \a\t g:i A') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                <i class="fa fa-calendar mr-2"></i>Timeline
                            </h5>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Job Posted</h6>
                                        <small class="text-muted">{{ $jobPosting->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Application Deadline</h6>
                                        <small class="text-muted">{{ $deadline->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                               
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






@endsection 