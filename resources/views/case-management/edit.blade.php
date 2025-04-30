@extends('layouts.main')

@section('title', 'Edit Case')
@section('breadcrumb', 'Edit Case')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="card">
    <div class="card-header">
        <h4>Edit Case</h4>
        <!-- @if($case->CaseStatus == 'CaseApproved - Charged')
    <div class="text-right mt-3">
        <a href="{{ route('cases.pdf', $case->CaseID) }}" class="btn btn-success">Download Case Details (PDF)</a>
    </div>
@endif -->
    </div>
    <div class="card-body">
        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit case details'))
        <form action="{{ route('casess.update', $case->CaseID) }}" method="POST">
            @csrf
            @method('PUT') <!-- Use PUT method for updating -->

          
            <!-- Case Details -->
            <h5>Case Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="case_type">Case Type</label>
                        <select name="case_type" id="case_type" class="form-control">
                            <option value="">Select</option>
                            @foreach($caseTypes as $caseType)
                            <option value="{{ $caseType->id }}" {{ $case->CaseType == $caseType->id ? 'selected' : '' }}>
                                {{ $caseType->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="officer">Select Officer</label>
                        <select name="officer" id="officer" class="form-control">
                            <option value="">Select</option>
                            @foreach($officers as $officer)
                            <option value="{{ $officer->id }}" {{ $case->OfficerID == $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
    <div class="form-group">
        
        <label for="case_status">Case Status</label>
        <select name="case_status" id="case_status" class="form-control">
            <option value="Reopen" {{ $case->CaseStatus == 'Reopen' ? 'selected' : '' }}>Reopen</option>
            <option value="Closed" {{ $case->CaseStatus == 'Closed' ? 'selected' : '' }}>Closed</option>
            <option value="{{ $case->CaseStatus }}" {{ $case->CaseStatus != 'Reopen' && $case->CaseStatus != 'Closed' ? 'selected' : '' }}>
                {{ $case->CaseStatus }}
            </option>
        </select>
    </div>
</div>

                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control">
                            <option value="">Select</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $case->CaseDepartmentID == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- admininstratin -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="administrative_unit">Administrative Unit</label>
                        <select name="administrative_unit" id="administrative_unit" class="form-control">
                            <option value="">Select</option>
                            @foreach($administrativeUnits as $unit)
                            <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="subdivision">Subdivision</label>
                        <select name="subdivision" id="subdivision" class="form-control">
                            <option value="">Select</option>
                            @foreach($subdivisions as $subdivision)
                            <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                {{ $subdivision->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="police_station">Police Station</label>
                        <select name="police_station" id="police_station" class="form-control">
                            <option value="">Select</option>
                            @foreach($policeStations as $station)
                            <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>




                <div class="col-md-12">
                    <div class="form-group">
                        <label for="case_description">Case Description</label>
                        <textarea name="case_description" id="case_description" class="form-control" rows="3">{{ old('case_description', $case->CaseDescription) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Complainant Details -->
            <h5>Complainant Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_name">Complainant Name</label>
                        <input type="text" name="complainant_name" id="complainant_name" class="form-control" value="{{ old('complainant_name', $complainant->ComplainantName) }}" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_contact">Complainant Contact</label>
                        <input type="text" name="complainant_contact" id="complainant_contact" class="form-control" value="{{ old('complainant_contact', $complainant->ComplainantContact) }}" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_id_number">ID Number</label>
                        <input type="text" name="complainant_id_number" id="complainant_id_number" class="form-control" value="{{ old('complainant_id_number', $complainant->ComplainantID) }}" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_dob">DOB</label>
                        <input type="date" name="complainant_dob" id="complainant_dob" class="form-control" value="{{ old('complainant_dob', $complainant->ComplainantDateOfBirth) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_gender">Gender</label>
                        <select name="complainant_gender" id="complainant_gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male" {{ $complainant->ComplainantGenderType == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $complainant->ComplainantGenderType == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $complainant->ComplainantGenderType == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="complainant_address">Address</label>
                        <textarea name="complainant_address" id="complainant_address" class="form-control" rows="2">{{ old('complainant_address', $complainant->ComplainantAddress) }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_relation">Relation</label>
                        <input type="text" name="complainant_relation" id="complainant_relation" class="form-control" value="{{ old('complainant_relation', $complainant->ComplainantRelation) }}" placeholder="Type here">
                    </div>
                </div>
            </div>

            <!-- Accused Details -->
            <h5>Accused Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_name">Accused Name</label>
                        <input type="text" name="accused_name" id="accused_name" class="form-control" value="{{ old('accused_name', $accused->AccusedName) }}" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_contact">Accused Contact</label>
                        <input type="text" name="accused_contact" id="accused_contact" class="form-control" value="{{ old('accused_contact', $accused->AccusedContact) }}" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_id_number">ID Number</label>
                        <input type="text" name="accused_id_number" id="accused_id_number" class="form-control" value="{{ old('accused_id_number', $accused->AccusedID) }}" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_dob">DOB</label>
                        <input type="date" name="accused_dob" id="accused_dob" class="form-control" value="{{ old('accused_dob', $accused->AccusedDateOfBirth) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_gender">Gender</label>
                        <select name="accused_gender" id="accused_gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male" {{ $accused->AccusedGenderType == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $accused->AccusedGenderType == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $accused->AccusedGenderType == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="accused_address">Address</label>
                        <textarea name="accused_address" id="accused_address" class="form-control" rows="2">{{ old('accused_address', $accused->AccusedAddress) }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_relation">Relation</label>
                        <input type="text" name="accused_relation" id="accused_relation" class="form-control" value="{{ old('accused_relation', $accused->AccusedRelation) }}" placeholder="Type here">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <a href="{{ route('casess.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>

        </form>
        @else
        <form action="{{ route('casess.update', $case->CaseID) }}" method="POST">
            @csrf
            @method('PUT') <!-- Use PUT method for updating -->

            <!-- Case Details -->
            <h5>Case Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="case_type">Case Type</label>
                        <select name="case_type" id="case_type" class="form-control" disabled>
                            <option value="">Select</option>
                            @foreach($caseTypes as $caseType)
                            <option value="{{ $caseType->id }}" {{ $case->CaseType == $caseType->id ? 'selected' : '' }}>
                                {{ $caseType->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="officer">Select Officer</label>
                        <select name="officer" id="officer" class="form-control" disabled>
                            <option value="">Select</option>
                            @foreach($officers as $officer)
                            <option value="{{ $officer->id }}" {{ $case->OfficerID == $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
    <div class="form-group">
        <label for="case_status">Case Status</label>
        <select name="case_status" id="case_status" class="form-control" disabled>
            <option value="{{ $case->CaseStatus }}" selected>
                {{ $case->CaseStatus }}
            </option>
        </select>
    </div>
</div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control" disabled>
                            <option value="">Select</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $case->CaseDepartmentID == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- admininstratin -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="administrative_unit">Administrative Unit</label>
                        <select name="administrative_unit" id="administrative_unit" class="form-control" disabled>
                            <option value="">Select</option>
                            @foreach($administrativeUnits as $unit)
                            <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="subdivision">Subdivision</label>
                        <select name="subdivision" id="subdivision" class="form-control" disabled>
                            <option value="">Select</option>
                            @foreach($subdivisions as $subdivision)
                            <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                {{ $subdivision->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="police_station">Police Station</label>
                        <select name="police_station" id="police_station" class="form-control" disabled>
                            <option value="">Select</option>
                            @foreach($policeStations as $station)
                            <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>




                <div class="col-md-12">
                    <div class="form-group">
                        <label for="case_description">Case Description</label>
                        <textarea name="case_description" id="case_description" class="form-control" rows="3" disabled>{{ old('case_description', $case->CaseDescription) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Complainant Details -->
            <h5>Complainant Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_name">Complainant Name</label>
                        <input type="text" name="complainant_name" id="complainant_name" class="form-control" value="{{ old('complainant_name', $complainant->ComplainantName) }}" placeholder="Type here" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_contact">Complainant Contact</label>
                        <input type="text" name="complainant_contact" id="complainant_contact" class="form-control" value="{{ old('complainant_contact', $complainant->ComplainantContact) }}" placeholder="Type here" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_id_number">ID Number</label>
                        <input type="text" name="complainant_id_number" id="complainant_id_number" class="form-control" value="{{ old('complainant_id_number', $complainant->ComplainantID) }}" placeholder="Type here" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_dob">DOB</label>
                        <input type="date" name="complainant_dob" id="complainant_dob" class="form-control" value="{{ old('complainant_dob', $complainant->ComplainantDateOfBirth) }}" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_gender">Gender</label>
                        <select name="complainant_gender" id="complainant_gender" class="form-control" disabled>
                            <option value="">Select</option>
                            <option value="male" {{ $complainant->ComplainantGenderType == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $complainant->ComplainantGenderType == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $complainant->ComplainantGenderType == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="complainant_address">Address</label>
                        <textarea name="complainant_address" id="complainant_address" class="form-control" rows="2" disabled>{{ old('complainant_address', $complainant->ComplainantAddress) }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_relation">Relation</label>
                        <input type="text" name="complainant_relation" id="complainant_relation" class="form-control" value="{{ old('complainant_relation', $complainant->ComplainantRelation) }}" placeholder="Type here" disabled>
                    </div>
                </div>
            </div>

            <!-- Accused Details -->
            <h5>Accused Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_name">Accused Name</label>
                        <input type="text" name="accused_name" id="accused_name" class="form-control" value="{{ old('accused_name', $accused->AccusedName) }}" placeholder="Type here" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_contact">Accused Contact</label>
                        <input type="text" name="accused_contact" id="accused_contact" class="form-control" value="{{ old('accused_contact', $accused->AccusedContact) }}" placeholder="Type here" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_id_number">ID Number</label>
                        <input type="text" name="accused_id_number" id="accused_id_number" class="form-control" value="{{ old('accused_id_number', $accused->AccusedID) }}" placeholder="Type here" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_dob">DOB</label>
                        <input type="date" name="accused_dob" id="accused_dob" class="form-control" value="{{ old('accused_dob', $accused->AccusedDateOfBirth) }}" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_gender">Gender</label>
                        <select name="accused_gender" id="accused_gender" class="form-control" disabled>
                            <option value="">Select</option>
                            <option value="male" {{ $accused->AccusedGenderType == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $accused->AccusedGenderType == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $accused->AccusedGenderType == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="accused_address">Address</label>
                        <textarea name="accused_address" id="accused_address" class="form-control" rows="2" disabled>{{ old('accused_address', $accused->AccusedAddress) }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_relation">Relation</label>
                        <input type="text" name="accused_relation" id="accused_relation" class="form-control" value="{{ old('accused_relation', $accused->AccusedRelation) }}" placeholder="Type here" disabled>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <a href="{{ route('casess.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
        @endif
    </div>
</div>


<!-- Investigation Documents Section -->
@if(auth()->user()->can('manage investigation documents'))
<div class="card mt-4">
    <div class="card-header">
        <h4>Investigation Documents</h4>
        @if(auth()->user()->can('add investigation documents'))
        <button type="button" class="btn btn-primary float-right" id="add-doc" data-toggle="modal" data-target="#addDocModal">Add Doc</button>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                <tr>
                    <td>{{ $document->name }}</td>
                    <td>{{ $document->description }}</td>
                    <td>
                        <!-- View Button -->
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-link">View</a>


                        @if(auth()->user()->can('edit investigation documents'))

                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editDocModal"
                            data-id="{{ $document->id }}"
                            data-name="{{ $document->name }}"
                            data-description="{{ $document->description }}">
                            Edit
                        </button>
                        @endif
                        @if(auth()->user()->can('delete investigation documents'))
                        <!-- Delete Button -->
                        <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>

                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">No documents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif


@if(auth()->user()->can('manage evidence'))
<!-- Evidence Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Evidence</h4>
        @if(auth()->user()->can('add evidence'))
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addEvidenceModal">Add Evidence</button>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Evidence ID</th>
                    <th>Evidence Type</th>
                    <th>Date</th>
                    <th>Collected By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evidences as $evidence)
                <tr>
                    <td>{{ $evidence->id }}</td>
                    <td>{{ $evidence->type }}</td>
                    <td>{{ $evidence->date }}</td>
                    <td>{{ $evidence->collected_by }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $evidence->file_path) }}" target="_blank" class="btn btn-link">View</a>

                        @if(auth()->user()->can('edit evidence'))
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editEvidenceModal"
                            data-id="{{ $evidence->id }}"
                            data-type="{{ $evidence->type }}"
                            data-date="{{ $evidence->date }}"
                            data-collected-by="{{ $evidence->collected_by }}">
                            Edit
                        </button>
                        @endif

                        @if(auth()->user()->can('delete evidence'))
                        <form action="{{ route('evidences.destroy', $evidence->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No evidences found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@if(auth()->user()->can('manage witnesses'))
<!-- Witness Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Witness</h4>
        @if(auth()->user()->can('add witnesses'))
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addWitnessModal">Add Witness</button>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Witness ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>National ID</th>
                    <th>Files</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($witnesses as $witness)
                <tr>
                    <td>{{ $witness->id }}</td>
                    <td>{{ $witness->name }}</td>
                    <td>{{ $witness->address }}</td>
                    <td>{{ $witness->national_id }}</td>
                    <td>
                        @foreach($witness->files as $file)
                        <div class="d-flex align-items-center">
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-link">
                                {{ $file->file_name }} ({{ $file->file_type }})
                            </a>

                            @if(auth()->user()->can('delete witnesses'))
                            <!-- Delete File Button -->
                            <form action="{{ route('witness-files.destroy', $file->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm ml-2">
                                    &times;
                                </button>
                            </form>
                            @endif
                        </div>
                        @endforeach
                    </td>
                    <td>
                    @if(auth()->user()->can('edit witnesses'))
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editWitnessModal"
                            data-id="{{ $witness->id }}"
                            data-name="{{ $witness->name }}"
                            data-address="{{ $witness->address }}"
                            data-national-id="{{ $witness->national_id }}">
                            Edit
                        </button>
                        @endif
                        @if(auth()->user()->can('delete witnesses'))
                        <form action="{{ route('witness-files.destroy', $witness->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No witnesses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif


@if(auth()->user()->can('manage court proceedings'))

<!-- Court Proceeding Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Court Proceeding</h4>
        @if(auth()->user()->can('add court proceedings'))
        <button type="button" class="btn btn-primary float-right" id="add-court-proceeding" data-toggle="modal" data-target="#addCourtProceedingModal">Add</button>
        @endif
    </div>
    <div class="card-body">
        <h5>Proceedings</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courtProceedings as $proceeding)
                <tr>
                    <td>{{ $proceeding->name }}</td>
                    <td>{{ $proceeding->description }}</td>
                    <td>
                        <!-- View Button -->
                        <a href="{{ asset('storage/' . $proceeding->file_path) }}" target="_blank" class="btn btn-link">View</a>

                        @if(auth()->user()->can('edit court proceedings'))
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editCourtProceedingModal"
                            data-id="{{ $proceeding->id }}"
                            data-name="{{ $proceeding->name }}"
                            data-description="{{ $proceeding->description }}">
                            Edit
                        </button>
                        @endif
                        @if(auth()->user()->can('delete court proceedings'))
                        <!-- Delete Button -->
                        <form action="{{ route('court-proceedings.destroy', $proceeding->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">No proceedings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endif

<!-- Task Log Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Task Log</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Officer Name</th>
                    <th>Officer Rank</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action Taken</th>
                </tr>
            </thead>
            <tbody>
                @forelse($taskLogs as $log)
                <tr>
                    <td>{{ $log->officer_name }}</td>
                    <td>{{ $log->officer_rank }}</td>
                    <td>{{ $log->department }}</td>
                    <td>{{ $log->date }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->action_taken }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No task logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="text-right">
            <a href="{{ route('taskLogs.index', ['case_id' => $case->CaseID]) }}" class="btn btn-primary">View All</a>
        </div>

    </div>


    @if(auth()->user()->hasRole('Police Officer / Help Desk Officer') && $case->CaseStatus == 'CaseApproved - Charged')
    <!-- Take Action Section for help desk -->
    <div class="card mt-4">
        <div class="card-header">
            
 

            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                  


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit9" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision9" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station9" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To legal officer</label>
                            <select name="forward_to" id="forward_to8" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    

    @elseif(auth()->user()->hasRole('Police Officer / Help Desk Officer'))
    <!-- Take Action Section for help desk -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                    @if(auth()->user()->hasRole('Case Officer'))
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control">
                                <option value="">Select</option>
                                @if(auth()->user()->hasRole('Case Officer'))
                                <option value="open">Open</option>

                                <option value="closed">Closed</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    @endif


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit1" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision1" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station1" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasRole('Case Officer'))
    <!-- Take Action  Section for case officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control">
                                <option value="">Select</option>
                              
                                <option value="open">Open</option>

                                <option value="closed">Closed</option>
                             
                            </select>
                        </div>
                    </div>
              


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit2" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision2" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station2" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to1" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasRole('Investigation Officer'))
    <!-- Take Action Section for investigation officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">




                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit3" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision3" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station3" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to2" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    @if(auth()->user()->hasRole('Senior Investigation Officer / Inspector'))
    <!-- Take Action  Section for case officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control">
                                <option value="">Select</option>
                         
                                <option value="Under Review">Under Review</option>

                                <option value="closed">Closed</option>
                                <option value="Further information">Further information</option>
                              
                            </select>
                        </div>
                    </div>
                


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit4" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision4" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station4" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to3" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasRole('Station Sergeant'))
    <!-- Take Action  Section for case officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control" required>
                                <option value="">Select</option>
                         
                                <option value="Approved">Approved</option>

                                
                                <option value="Further information">Further information</option>
                              
                            </select>
                        </div>
                    </div>
                


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit5" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision5" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station5" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to4" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasRole('Sub-Divisional Officer'))
    <!-- Take Action  Section for case officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control" required>
                                <option value="">Select</option>
                         
                                <option value="Approved">Approved</option>

                                
                                <option value="Further information">Further information</option>
                              
                            </select>
                        </div>
                    </div>
                


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit6" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision6" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station6" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to5" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasRole('Commander of Division'))
    <!-- Take Action  Section for case officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control" required>
                                <option value="">Select</option>
                         
                                <option value="Approved">Approved</option>

                                
                                <option value="Further information">Further information</option>
                              
                            </select>
                        </div>
                    </div>
                


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit7" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision7" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station7" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to6" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    
    @if(auth()->user()->hasRole('DPP / PCA'))
    <!-- Take Action  Section for case officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control" required>
                                <option value="">Select</option>
                         
                                <option value="Case Resolved – Released">Case Resolved – Released</option>
                                <option value="CaseApproved - Charged">CaseApproved - Charged </option>
                              
                            </select>
                        </div>
                    </div>
                


                    <!-- administrative units -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="administrative_unit">Administrative Unit</label>
                            <select name="administrative_unit" id="administrative_unit8" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($administrativeUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $case->administrative_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- sub divisons -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subdivision">Subdivision</label>
                            <select name="subdivision" id="subdivision8" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}" {{ $case->subdivision_id == $subdivision->id ? 'selected' : '' }}>
                                    {{ $subdivision->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- police stations -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="police_station">Police Station</label>
                            <select name="police_station" id="police_station8" class="form-control" disabled>
                                <option value="">Select</option>
                                @foreach($policeStations as $station)
                                <option value="{{ $station->id }}" {{ $case->police_station_id == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forward_to">Forward To</label>
                            <select name="forward_to" id="forward_to7" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasRole('Legal Team Officer'))
    <!-- Take Action  Section for case officer -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Take Action</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.takeAction', $case->CaseID) }}" method="POST">
                @csrf
                <div class="row">

                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="change_status">Change Status</label>
                            <select name="change_status" id="change_status" class="form-control" required>
                                <option value="">Select</option>
                         
                                <option value="Case Resolved – Released">Case Resolved – Released</option>
                                <option value="Case Resolved - Convicted">Case Resolved - Convicted</option>
                                <option value="Case Closed on Court Order">Case Closed on Court Order</option>
                              
                            </select>
                        </div>
                    </div>
                


             



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="case_description_action">Case Description</label>
                            <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here" required></textarea>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif





    <!-- end the actions and manage sections -->

    <!-- Add Document Modal -->
    <div class="modal fade" id="addDocModal" tabindex="-1" role="dialog" aria-labelledby="addDocModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('documents.store', ['case_id' => $case->CaseID]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDocModalLabel">Add Investigation Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Document Name -->
                        <div class="form-group">
                            <label for="doc_name">Document Name</label>
                            <input type="text" name="doc_name" id="doc_name" class="form-control" placeholder="Enter document name" required>
                        </div>
                        <!-- Document Description -->
                        <div class="form-group">
                            <label for="doc_description">Description</label>
                            <textarea name="doc_description" id="doc_description" class="form-control" rows="3" placeholder="Enter description" required></textarea>
                        </div>
                        <!-- File Upload -->
                        <div class="form-group">
                            <label for="doc_file">Upload File</label>
                            <input type="file" name="doc_file" id="doc_file" class="form-control-file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Court Proceeding Modal -->
    <div class="modal fade" id="addCourtProceedingModal" tabindex="-1" role="dialog" aria-labelledby="addCourtProceedingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('court-proceedings.store', ['case_id' => $case->CaseID]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCourtProceedingModalLabel">Add Court Proceeding</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Proceeding Name -->
                        <div class="form-group">
                            <label for="proceeding_name">Proceeding Name</label>
                            <input type="text" name="proceeding_name" id="proceeding_name" class="form-control" placeholder="Enter proceeding name" required>
                        </div>
                        <!-- Proceeding Description -->
                        <div class="form-group">
                            <label for="proceeding_description">Description</label>
                            <textarea name="proceeding_description" id="proceeding_description" class="form-control" rows="3" placeholder="Enter description" required></textarea>
                        </div>
                        <!-- File Upload -->
                        <div class="form-group">
                            <label for="proceeding_file">Upload File</label>
                            <input type="file" name="proceeding_file" id="proceeding_file" class="form-control-file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Proceeding</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Court Proceeding Modal -->
    <div class="modal fade" id="editCourtProceedingModal" tabindex="-1" role="dialog" aria-labelledby="editCourtProceedingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editCourtProceedingForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCourtProceedingModalLabel">Edit Court Proceeding</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Proceeding Name -->
                        <div class="form-group">
                            <label for="edit_proceeding_name">Proceeding Name</label>
                            <input type="text" name="proceeding_name" id="edit_proceeding_name" class="form-control" required>
                        </div>
                        <!-- Proceeding Description -->
                        <div class="form-group">
                            <label for="edit_proceeding_description">Description</label>
                            <textarea name="proceeding_description" id="edit_proceeding_description" class="form-control" rows="3" required></textarea>
                        </div>
                        <!-- File Upload -->
                        <div class="form-group">
                            <label for="edit_proceeding_file">Upload File (Optional)</label>
                            <input type="file" name="proceeding_file" id="edit_proceeding_file" class="form-control-file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Proceeding</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Evidence Modal -->
    <div class="modal fade" id="addEvidenceModal" tabindex="-1" role="dialog" aria-labelledby="addEvidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('evidences.store', ['case_id' => $case->CaseID]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEvidenceModalLabel">Add Evidence</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="type">Evidence Type</label>
                            <input type="text" name="type" id="type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="collected_by">Collected By</label>
                            <input type="text" name="collected_by" id="collected_by" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="file">Upload File</label>
                            <input type="file" name="file" id="file" class="form-control-file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Evidence</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Evidence Modal -->
    <div class="modal fade" id="editEvidenceModal" tabindex="-1" role="dialog" aria-labelledby="editEvidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editEvidenceForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEvidenceModalLabel">Edit Evidence</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_type">Evidence Type</label>
                            <input type="text" name="type" id="edit_type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_date">Date</label>
                            <input type="date" name="date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_collected_by">Collected By</label>
                            <input type="text" name="collected_by" id="edit_collected_by" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_file">Upload File (Optional)</label>
                            <input type="file" name="file" id="edit_file" class="form-control-file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Evidence</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Witness Modal -->
    <div class="modal fade" id="addWitnessModal" tabindex="-1" role="dialog" aria-labelledby="addWitnessModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('witnesses.store', ['case_id' => $case->CaseID]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addWitnessModalLabel">Add Witness</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Witness Name -->
                        <div class="form-group">
                            <label for="witness_name">Name</label>
                            <input type="text" name="name" id="witness_name" class="form-control" required>
                        </div>
                        <!-- Witness Address -->
                        <div class="form-group">
                            <label for="witness_address">Address</label>
                            <textarea name="address" id="witness_address" class="form-control" rows="3" required></textarea>
                        </div>
                        <!-- Witness National ID -->
                        <div class="form-group">
                            <label for="witness_national_id">National ID</label>
                            <input type="text" name="national_id" id="witness_national_id" class="form-control" required>
                        </div>
                        <!-- Witness Files -->
                        <div class="form-group">
                            <label for="witness_files">Upload Files (Documents or Videos)</label>
                            <input type="file" name="files[]" id="witness_files" class="form-control-file" multiple required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Witness</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Witness Modal -->
    <div class="modal fade" id="editWitnessModal" tabindex="-1" role="dialog" aria-labelledby="editWitnessModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editWitnessForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editWitnessModalLabel">Edit Witness</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Witness Name -->
                        <div class="form-group">
                            <label for="edit_witness_name">Name</label>
                            <input type="text" name="name" id="edit_witness_name" class="form-control" required>
                        </div>
                        <!-- Witness Address -->
                        <div class="form-group">
                            <label for="edit_witness_address">Address</label>
                            <textarea name="address" id="edit_witness_address" class="form-control" rows="3" required></textarea>
                        </div>
                        <!-- Witness National ID -->
                        <div class="form-group">
                            <label for="edit_witness_national_id">National ID</label>
                            <input type="text" name="national_id" id="edit_witness_national_id" class="form-control" required>
                        </div>
                        <!-- File Upload -->
                        <div class="form-group">
                            <label for="edit_witness_files">Upload Files (Documents or Videos)</label>
                            <input type="file" name="files[]" id="edit_witness_files" class="form-control-file" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Witness</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->




    <script>
        $('#editCourtProceedingModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract proceeding ID
            var name = button.data('name'); // Extract proceeding name
            var description = button.data('description'); // Extract proceeding description

            var modal = $(this);
            modal.find('#edit_proceeding_name').val(name); // Set the proceeding name
            modal.find('#edit_proceeding_description').val(description); // Set the proceeding description

            // Update the form action dynamically with the proceeding ID
            var formAction = "{{ route('court-proceedings.update', ':id') }}".replace(':id', id);
            modal.find('#editCourtProceedingForm').attr('action', formAction);
        });
    </script>

    <script>
        $('#editEvidenceModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract evidence ID
            var type = button.data('type'); // Extract evidence type
            var date = button.data('date'); // Extract evidence date
            var collectedBy = button.data('collected-by'); // Extract collected by

            var modal = $(this);
            modal.find('#edit_type').val(type); // Set evidence type
            modal.find('#edit_date').val(date); // Set evidence date
            modal.find('#edit_collected_by').val(collectedBy); // Set collected by

            // Update the form action dynamically with the evidence ID
            var formAction = "{{ route('evidences.update', ':id') }}".replace(':id', id);
            modal.find('#editEvidenceForm').attr('action', formAction);
        });
    </script>

    <script>
        $('#editWitnessModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract witness ID
            var name = button.data('name'); // Extract witness name
            var address = button.data('address'); // Extract witness address
            var nationalId = button.data('national-id'); // Extract witness national ID

            var modal = $(this);
            modal.find('#edit_witness_name').val(name); // Set witness name
            modal.find('#edit_witness_address').val(address); // Set witness address
            modal.find('#edit_witness_national_id').val(nationalId); // Set witness national ID

            // Update the form action dynamically with the witness ID
            var formAction = "{{ route('witnesses.update', ':id') }}".replace(':id', id);
            modal.find('#editWitnessForm').attr('action', formAction);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Populate Subdivisions when an Administrative Unit is selected
            $('#administrative_unit').on('change', function() {
                var unitId = $(this).val();
                $('#subdivision').empty().append('<option value="">Select</option>');
                $('#police_station').empty().append('<option value="">Select</option>');

                if (unitId) {
                    $.ajax({
                        url: '/get-subdivisions/' + unitId,
                        type: 'GET',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#subdivision').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Populate Police Stations when a Subdivision is selected
            $('#subdivision').on('change', function() {
                var subdivisionId = $(this).val();
                $('#police_station').empty().append('<option value="">Select</option>');

                if (subdivisionId) {
                    $.ajax({
                        url: '/get-police-stations/' + subdivisionId,
                        type: 'GET',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#police_station').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>



    <!-- get case officers -->
    <script>
        $(document).ready(function() {
            var administrativeUnit = document.getElementById('administrative_unit1')?.value || 'Not Selected';
            var subdivision = document.getElementById('subdivision1')?.value || 'Not Selected';
            var policeStation = document.getElementById('police_station1')?.value || 'Not Selected';

            $.ajax({
                url: "{{ route('get.case.officers') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function(data) {
                    if (data.length > 0) {

                        // Populate the Forward To dropdown with the fetched data
                        $.each(data, function(key, officer) {
                            $('#forward_to').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {

                        $('#forward_to').append('<option value="">No officers available</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching officers:', error);

                },
            });
        });
    </script>

    <!-- get investigation officers -->
    <script>
        $(document).ready(function() {
            var administrativeUnit = document.getElementById('administrative_unit2')?.value || 'Not Selected';
            var subdivision = document.getElementById('subdivision2')?.value || 'Not Selected';
            var policeStation = document.getElementById('police_station2')?.value || 'Not Selected';

            $.ajax({
                url: "{{ route('get.case.investigation.officers') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function(data) {
                    if (data.length > 0) {

                        // Populate the Forward To dropdown with the fetched data
                        $.each(data, function(key, officer) {
                            $('#forward_to1').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {

                        $('#forward_to1').append('<option value="">No officers available</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching officers:', error);

                },
            });
        });
    </script>


    <!-- get senior investigation officers -->
    <script>
        $(document).ready(function() {
            var administrativeUnit = document.getElementById('administrative_unit3')?.value || 'Not Selected';
            var subdivision = document.getElementById('subdivision3')?.value || 'Not Selected';
            var policeStation = document.getElementById('police_station3')?.value || 'Not Selected';

            $.ajax({
                url: "{{ route('get.case.senior.investigation.officers') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function(data) {
                    if (data.length > 0) {

                        // Populate the Forward To dropdown with the fetched data
                        $.each(data, function(key, officer) {
                            $('#forward_to2').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {

                        $('#forward_to2').append('<option value="">No officers available</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching officers:', error);

                },
            });
        });
    </script>


<!-- manage senior investigation officer case -->

<script>
    $(document).ready(function () {
    // Listen for changes in the "Change Status" dropdown
    $('#change_status').on('change', function () {
        var status = $(this).val(); // Get the selected status
        var administrativeUnit = document.getElementById('administrative_unit4')?.value || 'Not Selected';
        var subdivision = document.getElementById('subdivision4')?.value || 'Not Selected';
        var policeStation = document.getElementById('police_station4')?.value || 'Not Selected';

        // Clear the Forward To dropdown
        $('#forward_to3').empty().append('<option value="">Select</option>');

        if (status === 'Further information') {
            // Fetch Case Officers
            $.ajax({
                url: "{{ route('get.case.officers') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Case Officers
                        $.each(data, function (key, officer) {
                            $('#forward_to3').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to3').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        } else if (status === 'Under Review') {
            // Fetch Station Sergeants
            $.ajax({
                url: "{{ route('get.station.sergeants') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Station Sergeants
                        $.each(data, function (key, officer) {
                            $('#forward_to3').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to3').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        }
    });
});
</script>


<!-- manage senior saregant case -->

<script>
    $(document).ready(function () {
    // Listen for changes in the "Change Status" dropdown
    $('#change_status').on('change', function () {
        var status = $(this).val(); // Get the selected status
        var administrativeUnit = document.getElementById('administrative_unit5')?.value || 'Not Selected';
        var subdivision = document.getElementById('subdivision5')?.value || 'Not Selected';
        var policeStation = document.getElementById('police_station5')?.value || 'Not Selected';

        // Clear the Forward To dropdown
        $('#forward_to4').empty().append('<option value="">Select</option>');

        if (status === 'Further information') {
            // Fetch Case Officers
            $.ajax({
                url: "{{ route('get.case.senior.investigation.officers') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Case Officers
                        $.each(data, function (key, officer) {
                            $('#forward_to4').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to4').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        } else if (status === 'Approved') {
            // Fetch Station Sergeants

            
            $.ajax({
                url: "{{ route('get.subdivisionalofficer') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Station Sergeants
                        $.each(data, function (key, officer) {
                            $('#forward_to4').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to4').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        }
    });
});
</script>

<!-- manage senior sub div officer case -->

<script>
    $(document).ready(function () {
    // Listen for changes in the "Change Status" dropdown
    $('#change_status').on('change', function () {
        var status = $(this).val(); // Get the selected status
        var administrativeUnit = document.getElementById('administrative_unit6')?.value || 'Not Selected';
        var subdivision = document.getElementById('subdivision6')?.value || 'Not Selected';
        var policeStation = document.getElementById('police_station6')?.value || 'Not Selected';

        // Clear the Forward To dropdown
        $('#forward_to5').empty().append('<option value="">Select</option>');

        if (status === 'Further information') {
            // Fetch Case Officers
            $.ajax({
                url: "{{ route('get.station.sergeants') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Case Officers
                        $.each(data, function (key, officer) {
                            $('#forward_to5').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to5').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        } else if (status === 'Approved') {
            // Fetch Station Sergeants

           
            $.ajax({
                url: "{{ route('get.commanders') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Station Sergeants
                        $.each(data, function (key, officer) {
                            $('#forward_to5').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to5').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        }
    });
});
</script>

<!-- manage commander of divison case -->

<script>
    $(document).ready(function () {
    // Listen for changes in the "Change Status" dropdown
    $('#change_status').on('change', function () {
        var status = $(this).val(); // Get the selected status
        var administrativeUnit = document.getElementById('administrative_unit7')?.value || 'Not Selected';
        var subdivision = document.getElementById('subdivision7')?.value || 'Not Selected';
        var policeStation = document.getElementById('police_station7')?.value || 'Not Selected';

        // Clear the Forward To dropdown
        $('#forward_to6').empty().append('<option value="">Select</option>');

        if (status === 'Further information') {
            // Fetch Case Officers
            $.ajax({
                url: "{{ route('get.subdivisionalofficer') }}", // Replace with your route name
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Case Officers
                        $.each(data, function (key, officer) {
                            $('#forward_to6').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to6').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        } else if (status === 'Approved') {
            // Fetch Station Sergeants

          
            $.ajax({
                url: "{{ route('get.dpp.pca') }}", // Replace with your route name
                
                type: "GET",
                data: {
                    administrative_unit_id: administrativeUnit,
                    subdivision_id: subdivision,
                    police_station_id: policeStation,
                },
                success: function (data) {
                    if (data.length > 0) {
                        // Populate the Forward To dropdown with Station Sergeants
                        $.each(data, function (key, officer) {
                            $('#forward_to6').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                        });
                    } else {
                        $('#forward_to6').append('<option value="">No officers available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching officers:', error);
                },
            });
        }
    });
});
</script>


<!-- manage commander of dpp/css case -->

<script>
$(document).ready(function () {
    var caseId = "{{ $case->CaseID }}"; // Get the case ID dynamically

    // Fetch Help Desk users
    $.ajax({
        url: "{{ route('get.help.desk.users', ':caseId') }}".replace(':caseId', caseId),
        type: "GET",
        success: function (data) {
            if (data.length > 0) {
                // Populate the Forward To dropdown
                $.each(data, function (key, user) {
                    $('#forward_to7').append('<option value="' + user.id + '">' + user.name + '</option>');
                });
            } else {
                $('#forward_to7').append('<option value="">No Help Desk users available</option>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching Help Desk users:', error);
        },
    });
});
</script>

<!-- manage approved case -->
 <script>
    $(document).ready(function () {
    // Fetch Legal Team Officers based on Administrative Unit, Subdivision, and Police Station
    var administrativeUnit = $('#administrative_unit9').val() || 'Not Selected';
    var subdivision = $('#subdivision9').val() || 'Not Selected';
    var policeStation = $('#police_station9').val() || 'Not Selected';


    // Clear the Forward To dropdown
    $('#forward_to8').empty().append('<option value="">Select</option>');

    // Fetch Legal Team Officers
    $.ajax({
        url: "{{ route('get.legal.team.officers') }}", // Replace with your route name
        type: "GET",
        data: {
            administrative_unit_id: administrativeUnit,
            subdivision_id: subdivision,
            police_station_id: policeStation,
        },
        success: function (data) {
            if (data.length > 0) {
                // Populate the Forward To dropdown with Legal Team Officers
                $.each(data, function (key, officer) {
                    $('#forward_to8').append('<option value="' + officer.id + '">' + officer.name + '</option>');
                });
            } else {
                $('#forward_to8').append('<option value="">No officers available</option>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching officers:', error);
        },
    });
});
 </script>








    @endsection