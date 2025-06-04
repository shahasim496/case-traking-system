@extends('layouts.main')
@section('title', 'Evidence Details')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header  text-white" style="background-color: #0066cc;">
            <h4 class="mb-0">Evidence Details</h4>
        </div>
        <div class="card-body">
            <!-- General Evidence Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">General Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Type</th>
                                        <td>{{ ucfirst($evidence->type) }}</td>
                                    </tr>
                                    <tr>
                                        <th width="40%">Reference Number</th>
                                        <td>GFSL-{{ date('Y', strtotime($evidence->created_at)) }}-{{ $evidence->id }}</td>
                                    </tr>
                                    <tr>
                                        <th width="40%">Case ID</th>
                                        <td>{{ $evidence->case_id }}</td>
                                    </tr>
                                
                                    <tr>
                                        <th width="40%">Status</th>
                                        <td>
                                            <span class="badge {{ $evidence->status == 'pending' ? 'bg-danger' : ($evidence->status == 'verified' ? 'bg-warning' : 'bg-success') }}">
                                                {{ ucfirst($evidence->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%">Submission Date</th>
                                        <td>{{ $evidence->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                <th width="40%">GFSL Officer ID</th>
                                <td>{{ $evidence->g_officer_id }}</td>
                            </tr>
                            <tr>
                                <th width="40%">GFSL Officer Name</th>
                                <td>{{ $evidence->g_officer_name }}</td>
                            </tr>
                            <tr>
                                <th width="40%">GFSL Officer Designation</th>
                                <td>{{ $evidence->g_designation }}</td>
                            </tr>



                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GFSL Officer Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Submission Officer Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                                        <th width="40%">Police Officer ID</th>
                                        <td>{{ $evidence->officer_id }}</td>
                                    </tr>
                                    <tr>
                                        <th width="40%">Police Officer Name</th>
                                        <td>{{ $evidence->officer_name }}</td>
                                    </tr>
                                    <tr>
                                        <th width="40%">Police Officer Designation</th>
                                        <td>{{ $evidence->designation }}</td>
                                    </tr>
                                    <tr>
                                        <th width="40%">Police Officer Email</th>
                                        <td>{{ $evidence->officer_email }}</td>
                                    </tr>
                        </tbody>
                    </table>


                    @if(auth()->user()->can('verify officer'))
                    
                    <!-- Officer Verification Button -->
                    <div class="mt-3 d-flex justify-content-end">
                        <form action="{{ route('evidence.verifyOfficer') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="officer_id" value="{{ $evidence->officer_id }}">
                            <input type="hidden" name="evidence_id" value="{{ $evidence->id }}">
                            <button type="submit" class="btn" style="background-color: #0066cc; color: white;">
                                <i class="fas fa-user-check mr-1"></i> Verify Officer in System
                            </button>
                        </form>
                    </div>
                    @endif


                </div>
            </div>

            <!-- Chain of Custody Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Chain of Custody</h5>
                </div>
                <div class="card-body">
                    @if($evidence->chainOfCustodies->isEmpty())
                        <p class="text-muted">No chain of custody records found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Delivered By</th>
                                        <th>Received By</th>
                                        <th>Comments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evidence->chainOfCustodies as $chain)
                                        <tr>
                                            <td>{{ $chain->date }}</td>
                                            <td>{{ $chain->time }}</td>
                                            <td>{{ $chain->delivered_by }}</td>
                                            <td>{{ $chain->received_by }}</td>
                                            <td>{{ $chain->comments }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Questioned Document Evidence -->
            @if($evidence->questionedDocumentEvidence)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Questioned Document Evidence</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Item ID</th>
                                    <td>{{ $evidence->questionedDocumentEvidence->item_id }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $evidence->questionedDocumentEvidence->description }}</td>
                                </tr>
                                <tr>
                                    <th>Items Submitted</th>
                                    <td>{{ $evidence->questionedDocumentEvidence->item_submitted }}</td>
                                </tr>
                                <tr>
                                    <th>Examination Requested</th>
                                    <td>{{ $evidence->questionedDocumentEvidence->examination_requested }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Currency Evidence -->
            @if($evidence->currencyEvidence)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Currency Evidence</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Item ID</th>
                                    <td>{{ $evidence->currencyEvidence->item_id }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $evidence->currencyEvidence->description }}</td>
                                </tr>
                                <tr>
                                    <th>Denomination</th>
                                    <td>{{ $evidence->currencyEvidence->denomination }}</td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td>{{ $evidence->currencyEvidence->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Subtotal</th>
                                    <td>{{ $evidence->currencyEvidence->subtotal }}</td>
                                </tr>
                                <tr>
                                    <th>Total Value</th>
                                    <td>{{ $evidence->currencyEvidence->total_value }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- DNA Evidence -->
            @if($evidence->dnaDonors->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">DNA Evidence</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Middle Initial</th>
                                        <th>Phone</th>
                                        <th>Date of Birth</th>
                                        <th>Gender</th>
                                        <th>Address</th>
                                        <th>Collection Date</th>
                                        <th>ID Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evidence->dnaDonors as $donor)
                                        <tr>
                                            <td>{{ $donor->last_name }}</td>
                                            <td>{{ $donor->first_name }}</td>
                                            <td>{{ $donor->middle_initial }}</td>
                                            <td>{{ $donor->phone }}</td>
                                            <td>{{ $donor->dob }}</td>
                                            <td>{{ $donor->gender }}</td>
                                            <td>{{ $donor->address }}</td>
                                            <td>{{ $donor->collection_datetime }}</td>
                                            <td>{{ $donor->id_number }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ballistics Evidence -->
            @if($evidence->ballisticsEvidence)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Ballistics Evidence</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Item ID</th>
                                    <td>{{ $evidence->ballisticsEvidence->item_id }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $evidence->ballisticsEvidence->description }}</td>
                                </tr>
                                <tr>
                                    <th>Firearms</th>
                                    <td>{{ $evidence->ballisticsEvidence->firearms }}</td>
                                </tr>
                                <tr>
                                    <th>Ammo</th>
                                    <td>{{ $evidence->ballisticsEvidence->ammo }}</td>
                                </tr>
                                <tr>
                                    <th>Casings</th>
                                    <td>{{ $evidence->ballisticsEvidence->casings }}</td>
                                </tr>
                                <tr>
                                    <th>Bullets</th>
                                    <td>{{ $evidence->ballisticsEvidence->bullets }}</td>
                                </tr>
                                <tr>
                                    <th>Examination Requested</th>
                                    <td>{{ $evidence->ballisticsEvidence->examination_requested }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Toxicology Evidence -->
            @if($evidence->toxicologyEvidence)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Toxicology Evidence</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Item ID</th>
                                    <td>{{ $evidence->toxicologyEvidence->item_id }}</td>
                                </tr>
                                <tr>
                                    <th>Sample Type</th>
                                    <td>{{ $evidence->toxicologyEvidence->sample_type }}</td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td>{{ $evidence->toxicologyEvidence->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Collection</th>
                                    <td>{{ $evidence->toxicologyEvidence->collection }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $evidence->toxicologyEvidence->description }}</td>
                                </tr>
                                <tr>
                                    <th>Examination</th>
                                    <td>{{ $evidence->toxicologyEvidence->examination }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Video Evidence -->
            @if($evidence->videoEvidence)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Video Evidence</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Extraction Date</th>
                                    <td>{{ $evidence->videoEvidence->extraction_date }}</td>
                                </tr>
                                <tr>
                                    <th>Extracted From</th>
                                    <td>{{ $evidence->videoEvidence->extracted_from }}</td>
                                </tr>
                                <tr>
                                    <th>Extraction Method</th>
                                    <td>{{ $evidence->videoEvidence->extraction_method }}</td>
                                </tr>
                                <tr>
                                    <th>Storage Media</th>
                                    <td>{{ $evidence->videoEvidence->storage_media }}</td>
                                </tr>
                                <tr>
                                    <th>Retrieved By</th>
                                    <td>{{ $evidence->videoEvidence->retrieved_by }}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td>{{ $evidence->videoEvidence->contact }}</td>
                                </tr>
                                <tr>
                                    <th>Number of Cameras</th>
                                    <td>{{ $evidence->videoEvidence->num_cameras }}</td>
                                </tr>
                                <tr>
                                    <th>Number of Videos</th>
                                    <td>{{ $evidence->videoEvidence->num_videos }}</td>
                                </tr>
                                <tr>
                                    <th>Total Length</th>
                                    <td>{{ $evidence->videoEvidence->total_length }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

              <!-- General Evidence -->
            @if($evidence->generalEvidence)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">General Evidence</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Item ID</th>
                                    <td>{{ $evidence->generalEvidence->item_id }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $evidence->generalEvidence->description }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $evidence->generalEvidence->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $evidence->generalEvidence->updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Report Section -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Evidence Report</h5>
                </div>
                <div class="card-body">
                    @if($evidence->report_path)
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Current Report</h6>
                                <p class="text-muted mb-0">
                                    Last updated: {{ \Carbon\Carbon::parse($evidence->updated_at)->format('M d, Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ asset('storage/' . $evidence->report_path) }}" target="_blank" class="btn" style="background-color: #0066cc;!important; color: white;">
                                    <i class="fas fa-file-download"></i> View Report
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">No report has been uploaded yet.</p>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('evidences.index') }}" class="btn btn-secondary">Back to List</a>
            </div>


            @if(auth()->user()->hasRole('GFSL Security Officer'))
            <!-- Status Update and EVO Assignment Section -->
            <div class="card mt-4">
                <div class="card-header  text-white" style="background-color: #0066cc;">
                    <h5 class="mb-0">Update Status & Assign Officer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('evidence.updateStatus', $evidence->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Evidence Status</label>
                                <select name="status" id="status" class="form-control" required onchange="toggleEvoOfficer1(this.value)">
                                    <option value="" disabled {{ !$evidence->status ? 'selected' : '' }}>Select Status</option>
                                    <option value="rejected" {{ $evidence->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Awating Verification" {{ $evidence->status == 'Awating Verification' ? 'selected' : '' }}>Awating Verification</option>
                        
                                </select>
                            </div>
                         
                            <div class="col-md-6 mb-3" id="evoOfficerSection1" style="{{ $evidence->status == 'rejected' ? 'display: none;' : '' }}">
                                <label for="evo_officer_id1" class="form-label">Assign Officer</label>
                                <select name="evo_officer_id1" id="evo_officer_id1" class="form-control">
                                    <option value="" disabled {{ !$evidence->evo_officer_id ? 'selected' : '' }}>Select Officer</option>
                                    @foreach($officers as $officer)
                                        <option value="{{ $officer->id }}" 
                                            {{ $evidence->evo_officer_id == $officer->id ? 'selected' : '' }}>
                                            {{ $officer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes/Comments</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any notes or comments about the status update...">{{ $evidence->notes }}</textarea>
                            </div>
                        </div>

                   
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge badge-{{ 
                                        $evidence->status == 'verified' ? 'warning' : 
                                        ($evidence->status == 'pending' ? 'secondary' : 'info') 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $evidence->status ?? 'Not Set')) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current EVO Officer</label>
                                <div class="form-control-plaintext">
                                    {{ $evidence->evoOfficer->name ?? 'Not Assigned' }}
                                    @if($evidence->evoOfficer)
                                   
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if(auth()->user()->hasRole('EVO'))
            <!-- Status Update and EVO Assignment Section -->
            <div class="card mt-4">
                <div class="card-header  text-white" style="background-color: #0066cc;">
                    <h5 class="mb-0">Update Status & Assign Officer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('evidence.updateStatus', $evidence->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Evidence Status</label>
                                <select name="status" id="status" class="form-control" required onchange="toggleEvoOfficer2(this.value)">
                                    <option value="" disabled {{ !$evidence->status ? 'selected' : '' }}>Select Status</option>
                                    <option value="rejected" {{ $evidence->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Verified" {{ $evidence->status == 'Verified' ? 'selected' : '' }}>Verified</option>
                        
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="evoOfficerSection2" style="{{ $evidence->status == 'rejected' ? 'display: none;' : '' }}">
                                <label for="evo_officer_id" class="form-label">Assign  Officer</label>
                                <select name="evo_officer_id" id="evo_officer_id3" class="form-control" required>
                                    <option value="">Select  Officer</option>
                                </select>
                              
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes/Comments</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any notes or comments about the status update...">{{ $evidence->notes }}</textarea>
                            </div>
                        </div>

                   
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge badge-{{ 
                                        $evidence->status == 'verified' ? 'warning' : 
                                        ($evidence->status == 'pending' ? 'secondary' : 'info') 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $evidence->status ?? 'Not Set')) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current EVO Officer</label>
                                <div class="form-control-plaintext">
                                    {{ $evidence->evoOfficer->name ?? 'Not Assigned' }}
                                    @if($evidence->evoOfficer)
                                   
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @if(auth()->user()->hasRole('EVO Analyst'))
            <!-- Status Update and EVO Assignment Section -->
            <div class="card mt-4">
                <div class="card-header  text-white" style="background-color: #0066cc;">
                    <h5 class="mb-0">Update Status & Assign EVO Officer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('evidence.updateStatus', $evidence->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Evidence Status</label>
                                <select name="status" id="status" class="form-control" required onchange="toggleEvoOfficer(this.value)">
                                    <option value="" disabled {{ !$evidence->status ? 'selected' : '' }}>Select Status</option>
                                 
                                    <option value="inprogress" {{ $evidence->status == 'inprogress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $evidence->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            
                     
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes/Comments</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any notes or comments about the status update...">{{ $evidence->notes }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="report" class="form-label">Upload Report</label>
                                <input type="file" name="report" id="report" class="form-control" accept=".pdf,.doc,.docx">
                          
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge badge-{{ 
                                        $evidence->status == 'verified' ? 'warning' : 
                                        ($evidence->status == 'pending' ? 'secondary' : 'info') 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $evidence->status ?? 'Not Set')) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Officer</label>
                                <div class="form-control-plaintext">
                                    {{ $evidence->evoOfficer->name ?? 'Not Assigned' }}
                                    @if($evidence->evoOfficer)
                                   
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>

            @endif


     


            @if(auth()->user()->hasRole('SuperAdmin'))
            <!-- Status Update and EVO Assignment Section -->
            <div class="card mt-4">
                <div class="card-header  text-white" style="background-color: #0066cc;">
                    <h5 class="mb-0">Update Status & Assign EVO Officer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('evidence.updateStatus', $evidence->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Evidence Status</label>
                                <select name="status" id="status" class="form-control" required onchange="toggleEvoOfficer(this.value)">
                                    <option value="" disabled {{ !$evidence->status ? 'selected' : '' }}>Select Status</option>
                                    <option value="rejected" {{ $evidence->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Awating Verification" {{ $evidence->status == 'Awating Verification' ? 'selected' : '' }}>Awating Verification</option>
                                
                                    <option value="verified" {{ $evidence->status == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="inprogress" {{ $evidence->status == 'inprogress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $evidence->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="evoOfficerSection2" style="{{ $evidence->status == 'rejected' ? 'display: none;' : '' }}">
                                <label for="evo_officer_id" class="form-label">Assign Officer</label>
                                <select name="evo_officer_id" id="evo_officer_id2" class="form-control" required>
                                    <option value="">Select Officer</option>
                                </select>
                                @error('evo_officer_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes/Comments</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any notes or comments about the status update...">{{ $evidence->notes }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="report" class="form-label">Upload Report</label>
                                <input type="file" name="report" id="report" class="form-control" accept=".pdf,.doc,.docx">
                          
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge badge-{{ 
                                        $evidence->status == 'verified' ? 'warning' : 
                                        ($evidence->status == 'pending' ? 'secondary' : 'info') 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $evidence->status ?? 'Not Set')) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Officer</label>
                                <div class="form-control-plaintext">
                                    {{ $evidence->evoOfficer->name ?? 'Not Assigned' }}
                                    @if($evidence->evoOfficer)
                                   
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>

            @endif
        </div>
    </div>
</div>

<script>
function toggleEvoOfficer(status) {
    const evoOfficerSection = document.getElementById('evoOfficerSection2');
   
    if (status === 'rejected') {
        evoOfficerSection.style.display = 'none';
    
    } else {
        evoOfficerSection.style.display = 'block';
       
    }
}

function toggleEvoOfficer1(status) {
 
   
    const evoOfficerSection1 = document.getElementById('evoOfficerSection1');
    if (status === 'rejected') {
      
        evoOfficerSection1.style.display = 'none';
    } else {
       
        evoOfficerSection1.style.display = 'block';
    }
    
}


function toggleEvoOfficer2(status) {
 
   
 const evoOfficerSection1 = document.getElementById('evoOfficerSection2');
 if (status === 'rejected') {
   
     evoOfficerSection1.style.display = 'none';
 } else {
    
     evoOfficerSection1.style.display = 'block';
 }
 
}

$(document).ready(function() {
    // Fetch users by roles when the page loads
    $.ajax({
        url: '{{ route("get.users.by.roles") }}',
        type: 'GET',
        success: function(response) {
            var select = $('#evo_officer_id2');
            // Clear existing options except the first one
            select.find('option:not(:first)').remove();
            
            // Add new options grouped by roles
            Object.keys(response).forEach(function(role) {
                if (response[role].length > 0) {
                    // Create optgroup
                    var optgroup = $('<optgroup>', {
                        label: role
                    });
                    
                    // Add users to optgroup
                    response[role].forEach(function(user) {
                        optgroup.append($('<option>', {
                            value: user.id,
                            text: user.name
                        }));
                    });
                    
                    select.append(optgroup);
                }
            });

            // If there's a previously selected officer, select it
        
        },
        error: function(xhr) {
            console.error('Error fetching users:', xhr);
        }
    });
});

// Function to load EVO Analysts
$(document).ready(function() {
    $.ajax({
        url: '{{ route("get.evo.analysts") }}',
        type: 'GET',
        success: function(response) {
            var select = $('#evo_officer_id3');
            // Clear existing options except the first one
            select.find('option:not(:first)').remove();
            
            // Add EVO Analysts to the dropdown
            response.forEach(function(analyst) {
                select.append($('<option>', {
                    value: analyst.id,
                    text: analyst.name + (analyst.designation ? ' (' + analyst.designation.name + ')' : '')
                }));
            });

            // If there's a previously selected officer, select it
            if ('{{ $evidence->evo_officer_id }}') {
                select.val('{{ $evidence->evo_officer_id }}');
            }
        },
        error: function(xhr) {
            console.error('Error fetching EVO Analysts:', xhr);
        }
    });
});


// Load EVO Analysts when the page loads

</script>
@endsection