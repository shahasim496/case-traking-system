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
                        <select name="case_type" id="case_type" class="form-control" >
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
                        <select name="officer" id="officer" class="form-control" >
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
                        <select name="case_status" id="case_status" class="form-control" >
                            <option value="">Select</option>
                            <option value="Awating Verification" {{ $case->CaseStatus == 'Awating Verification' ? 'selected' : '' }}>Awating Verification</option>
                            <option value="In Progress" {{ $case->CaseStatus == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed" {{ $case->CaseStatus == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control" >
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
                        <select name="administrative_unit" id="administrative_unit" class="form-control" >
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
                        <select name="subdivision" id="subdivision" class="form-control" >
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
                        <select name="police_station" id="police_station" class="form-control" >
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
                        <input type="text" name="complainant_name" id="complainant_name" class="form-control" value="{{ old('complainant_name', $complainant->ComplainantName) }}" placeholder="Type here" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_contact">Complainant Contact</label>
                        <input type="text" name="complainant_contact" id="complainant_contact" class="form-control" value="{{ old('complainant_contact', $complainant->ComplainantContact) }}" placeholder="Type here" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_id_number">ID Number</label>
                        <input type="text" name="complainant_id_number" id="complainant_id_number" class="form-control" value="{{ old('complainant_id_number', $complainant->ComplainantID) }}" placeholder="Type here" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_dob">DOB</label>
                        <input type="date" name="complainant_dob" id="complainant_dob" class="form-control" value="{{ old('complainant_dob', $complainant->ComplainantDateOfBirth) }}" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_gender">Gender</label>
                        <select name="complainant_gender" id="complainant_gender" class="form-control" >
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
                        <textarea name="complainant_address" id="complainant_address" class="form-control" rows="2" >{{ old('complainant_address', $complainant->ComplainantAddress) }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_relation">Relation</label>
                        <input type="text" name="complainant_relation" id="complainant_relation" class="form-control" value="{{ old('complainant_relation', $complainant->ComplainantRelation) }}" placeholder="Type here" >
                    </div>
                </div>
            </div>

            <!-- Accused Details -->
            <h5>Accused Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_name">Accused Name</label>
                        <input type="text" name="accused_name" id="accused_name" class="form-control" value="{{ old('accused_name', $accused->AccusedName) }}" placeholder="Type here" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_contact">Accused Contact</label>
                        <input type="text" name="accused_contact" id="accused_contact" class="form-control" value="{{ old('accused_contact', $accused->AccusedContact) }}" placeholder="Type here" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_id_number">ID Number</label>
                        <input type="text" name="accused_id_number" id="accused_id_number" class="form-control" value="{{ old('accused_id_number', $accused->AccusedID) }}" placeholder="Type here" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_dob">DOB</label>
                        <input type="date" name="accused_dob" id="accused_dob" class="form-control" value="{{ old('accused_dob', $accused->AccusedDateOfBirth) }}" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_gender">Gender</label>
                        <select name="accused_gender" id="accused_gender" class="form-control" >
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
                        <textarea name="accused_address" id="accused_address" class="form-control" rows="2" >{{ old('accused_address', $accused->AccusedAddress) }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_relation">Relation</label>
                        <input type="text" name="accused_relation" id="accused_relation" class="form-control" value="{{ old('accused_relation', $accused->AccusedRelation) }}" placeholder="Type here" >
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
                            <option value="">Select</option>
                            <option value="Awating Verification" {{ $case->CaseStatus == 'Awating Verification' ? 'selected' : '' }}>Awating Verification</option>
                            <option value="In Progress" {{ $case->CaseStatus == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed" {{ $case->CaseStatus == 'Closed' ? 'selected' : '' }}>Closed</option>
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
@if(auth()->user()->can('view investigation documents'))
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


                        @if(auth()->user()->can('manage investigation documents'))

                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editDocModal"
                            data-id="{{ $document->id }}"
                            data-name="{{ $document->name }}"
                            data-description="{{ $document->description }}">
                            Edit
                        </button>

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

<!-- Evidence Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Evidence</h4>
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addEvidenceModal">Add Evidence</button>
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
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editEvidenceModal"
                            data-id="{{ $evidence->id }}"
                            data-type="{{ $evidence->type }}"
                            data-date="{{ $evidence->date }}"
                            data-collected-by="{{ $evidence->collected_by }}">
                            Edit
                        </button>
                        <form action="{{ route('evidences.destroy', $evidence->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
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

<!-- Witness Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Witness</h4>
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addWitnessModal">Add Witness</button>
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
                            <!-- Delete File Button -->
                            <form action="{{ route('witness-files.destroy', $file->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm ml-2">
                                    &times;
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editWitnessModal"
                            data-id="{{ $witness->id }}"
                            data-name="{{ $witness->name }}"
                            data-address="{{ $witness->address }}"
                            data-national-id="{{ $witness->national_id }}">
                            Edit
                        </button>

                        <form action="{{ route('witness-files.destroy', $witness->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
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

<!-- Court Proceeding Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Court Proceeding</h4>
        <button type="button" class="btn btn-primary float-right" id="add-court-proceeding" data-toggle="modal" data-target="#addCourtProceedingModal">Add</button>
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

                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editCourtProceedingModal"
                            data-id="{{ $proceeding->id }}"
                            data-name="{{ $proceeding->name }}"
                            data-description="{{ $proceeding->description }}">
                            Edit
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('court-proceedings.destroy', $proceeding->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
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
        <div class="container">
    <!-- <div class="d-flex justify-content-center">
        {{ $taskLogs->links() }}
    </div> -->
</div>
</div>

<!-- Take Action Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Take Action</h4>
    </div>
    <div class="card-body">
        <form action="#" method="POST">
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="take_action">Take Action</label>
                        <select name="take_action" id="take_action" class="form-control">
                            <option value="">Select</option>
                            <option value="approve">Approve</option>
                            <option value="reject">Reject</option>
                        </select>
                    </div>
                </div>

                        <!-- units -->
                    <!-- admininstratin -->
                    <div class="col-md-3">
                    <div class="form-group">
                        <label for="administrative_unit">Administrative Unit</label>
                        <select name="administrative_unit" id="administrative_unit" class="form-control" >
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
                        <select name="subdivision" id="subdivision" class="form-control" >
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
                        <select name="police_station" id="police_station" class="form-control" >
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
                            <option value="department1">Department 1</option>
                            <option value="department2">Department 2</option>
                        </select>
                    </div>
                </div>

                <!-- unitss -->
                 
          

        

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="case_description_action">Case Description</label>
                        <textarea name="case_description_action" id="case_description_action" class="form-control" rows="3" placeholder="Type here"></textarea>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


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

@endsection