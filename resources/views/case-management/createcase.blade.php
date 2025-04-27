@extends('layouts.main')
@section('title', 'Create Case')
@section('breadcrumb', 'Create Case')

@section('content')





<div class="card">
    <div class="card-header">
        <h4>Create Case</h4>

    </div>
    <div class="card-body">
        <form action="{{ route('casess.store') }}" method="POST">
            @csrf

            <!-- Case Details -->
            <h5>Case Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="case_type">Case Type</label>
                        <select name="case_type" id="case_type" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($caseTypes as $caseType)
                            <option value="{{ $caseType->id }}">{{ $caseType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
    <div class="form-group">
        <label for="officer">Select Officer</label>
        <select name="officer" id="officer" class="form-control" disabled>
            <option value="{{ auth()->user()->id }}" selected>{{ auth()->user()->name }}</option>
        </select>
        <input type="hidden" name="officer" value="{{ auth()->user()->id }}">
    </div>
</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="case_status">Case Status</label>
                        <select name="case_status" id="case_status" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Awating Verification">Awating Verification</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="administrative_unit">Administrative Unit</label>
                        <select name="administrative_unit" id="administrative_unit" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($administrativeUnits as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="subdivision">Subdivision</label>
                        <select name="subdivision" id="subdivision" class="form-control" required>
                            <option value="">Select</option>
                            <!-- Subdivisions will be dynamically populated -->
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="police_station">Police Station</label>
                        <select name="police_station" id="police_station" class="form-control" required>
                            <option value="">Select</option>
                            <!-- Police Stations will be dynamically populated using JavaScript -->
                        </select>
                    </div>
                </div>



                <div class="col-md-12">
                    <div class="form-group">
                        <label for="case_description">Case Description</label>
                        <textarea name="case_description" id="case_description" class="form-control" rows="3" placeholder="Type here"></textarea>
                    </div>
                </div>


            </div>

            <!-- Complainant Details -->
            <h5>Complainant Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_name">Complainant Name</label>
                        <input type="text" name="complainant_name" id="complainant_name" class="form-control" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_contact">Complainant Contact</label>
                        <input type="text" name="complainant_contact" id="complainant_contact" class="form-control" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_id_number">ID Number</label>
                        <input type="text" name="complainant_id_number" id="complainant_id_number" class="form-control" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_dob">DOB</label>
                        <input type="date" name="complainant_dob" id="complainant_dob" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_gender">Gender</label>
                        <select name="complainant_gender" id="complainant_gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="complainant_address">Address</label>
                        <textarea name="complainant_address" id="complainant_address" class="form-control" rows="2" placeholder="Type here"></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="complainant_relation">Relation</label>
                        <input type="text" name="complainant_relation" id="complainant_relation" class="form-control" placeholder="Type here">
                    </div>
                </div>
            </div>

            <!-- Accused Details -->
            <h5>Accused Details</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_name">Accused Name</label>
                        <input type="text" name="accused_name" id="accused_name" class="form-control" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_contact">Accused Contact</label>
                        <input type="text" name="accused_contact" id="accused_contact" class="form-control" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_id_number">ID Number</label>
                        <input type="text" name="accused_id_number" id="accused_id_number" class="form-control" placeholder="Type here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_dob">DOB</label>
                        <input type="date" name="accused_dob" id="accused_dob" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_gender">Gender</label>
                        <select name="accused_gender" id="accused_gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="accused_address">Address</label>
                        <textarea name="accused_address" id="accused_address" class="form-control" rows="2" placeholder="Type here"></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="accused_relation">Relation</label>
                        <input type="text" name="accused_relation" id="accused_relation" class="form-control" placeholder="Type here">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Add Case</button>
            </div>
        </form>
    </div>
</div>

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
                    },
                    error: function() {
                        alert('Failed to fetch subdivisions. Please try again.');
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
                    },
                    error: function() {
                        alert('Failed to fetch police stations. Please try again.');
                    }
                });
            }
        });
    });
</script>

@endsection