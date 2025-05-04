@extends('layouts.main')
@section('title','Edit User')


@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('user.update',$user->id) }}" enctype='multipart/form-data'>
            @csrf
            @method('PUT')
            <div class="row">

                <!-- User Name -->
                <div class="col-lg-6">
                <label class="label-font">User name</label>
                <input type="text" id="name" name="name" class="form-control mb-3"
                placeholder="Type your full name" required="" value="{{old('name',$user->name)}}">
                @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                <!-- National ID -->
                <div class="col-lg-6">
                <label class="label-font">National ID</label>
                <input type="text" id="cnic" name="cnic" class="form-control mb-3"
                placeholder="Type CNIC" required="" value="{{ old('cnic', $user->cnic) }}">
                @if ($errors->has('cnic'))
                        <span class="text-danger">{{ $errors->first('cnic') }}</span>
                @endif
                </div>

                <!-- Email -->
                <div class="col-lg-6">
                <label class="label-font">Email</label>
                <input type="email" id="email" name="email" class="form-control mb-3"
                placeholder="Type your email" required="" value="{{old('email',$user->email)}}">
                @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                </div>

                <!-- Mobile Number -->
                <div class="col-lg-6">
                <label class="label-font">Mobile Number</label>
                <input type="text" id="phone" name="phone" class="form-control mb-3"
                placeholder="0000 - 0000 000" required="" value="{{ old('phone', $user->phone) }}">
                @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
                </div>

                <!-- Password -->
                <div class="col-md-6">
                <label class="label-font">Password</label>
                <input type="Password" id="password" name="password" placeholder="Type Password" class="form-control mb-3">
                @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                </div>

                <!-- Re-type Password -->
                <div class="col-md-6">
                <label class="label-font">*Re-type Password</label>
                <input type="Password" id="password_confirmation" name="password_confirmation" placeholder="Re-type Password" class="form-control mb-3">
                @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
                </div>

                <!-- Department -->
                <div class="col-lg-6">
                    <label class="label-font">Department</label>
                    <select name="department_id" id="department_id" class="form-control mb-3" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('department_id'))
                        <span class="text-danger">{{ $errors->first('department_id') }}</span>
                    @endif
                </div>

                <!-- Designation -->
                <div class="col-lg-6">
                    <label class="label-font">Designation</label>
                    <select name="designation_id" id="designation_id" class="form-control mb-3" required>
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation->id }}" {{ old('designation_id', $user->designation_id) == $designation->id ? 'selected' : '' }}>
                                {{ $designation->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('designation_id'))
                        <span class="text-danger">{{ $errors->first('designation_id') }}</span>
                    @endif
                </div>

                <!-- Administrative Unit -->
                <div class="col-lg-6">
                    <label class="label-font">Administrative Unit</label>
                    <select name="administrative_unit_id" id="administrative_unit_id" class="form-control mb-3" required>
                        <option value="">Select Administrative Unit</option>
                        @foreach($administrativeUnits as $unit)
                            <option value="{{ $unit->id }}" {{ old('administrative_unit_id', $user->administrative_unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('administrative_unit_id'))
                        <span class="text-danger">{{ $errors->first('administrative_unit_id') }}</span>
                    @endif
                </div>

                <!-- Subdivision -->
                <div class="col-lg-6">
                    <label class="label-font">Subdivision (Optional)</label>
                    <select name="subdivision_id" id="subdivision_id" class="form-control mb-3">
                        <option value="">Select Subdivision</option>
                        @foreach($subdivisions as $subdivision)
                            <option value="{{ $subdivision->id }}" {{ old('subdivision_id', $user->subdivision_id) == $subdivision->id ? 'selected' : '' }}>
                                {{ $subdivision->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('subdivision_id'))
                        <span class="text-danger">{{ $errors->first('subdivision_id') }}</span>
                    @endif
                </div>

                <!-- Police Station -->
                <div class="col-lg-6">
                    <label class="label-font">Police Station (Optional)</label>
                    <select name="police_station_id" id="police_station_id" class="form-control mb-3">
                        <option value="">Select Police Station</option>
                        @foreach($policeStations as $station)
                            <option value="{{ $station->id }}" {{ old('police_station_id', $user->police_station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('police_station_id'))
                        <span class="text-danger">{{ $errors->first('police_station_id') }}</span>
                    @endif
                </div>

                <!-- User Roles -->
                <div class="col-lg-6">
                    <label class="label-font">User Roles</label>
                    <select name="roles[]" id="roles" class="form-control mb-3" multiple required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('roles'))
                        <span class="text-danger">{{ $errors->first('roles') }}</span>
                    @endif
                </div>

                <!-- Submit Buttons -->
                <div class="col-lg-12 btn-submit mt-2 mb-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>

@endsection

@section('jsfile')
<script>
    $(document).ready(function () {
        // Handle Administrative Unit change
        $('#administrative_unit_id').on('change', function () {
            let administrativeUnitId = $(this).val();

            // Clear existing options
            $('#subdivision_id').html('<option value="">Select Subdivision</option>');
            $('#police_station_id').html('<option value="">Select Police Station</option>');

            if (administrativeUnitId) {
                // Fetch subdivisions
                $.ajax({
                    url: '{{ route("getSubdivisions") }}',
                    type: 'GET',
                    data: { administrative_unit_id: administrativeUnitId },
                    success: function (data) {
                        if (data.subdivisions) {
                            $.each(data.subdivisions, function (key, value) {
                                $('#subdivision_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    }
                });
            }
        });

        // Handle Subdivision change
        $('#subdivision_id').on('change', function () {
            let subdivisionId = $(this).val();

            // Clear existing options
            $('#police_station_id').html('<option value="">Select Police Station</option>');

            if (subdivisionId) {
                // Fetch police stations
                $.ajax({
                    url: '{{ route("getPoliceStations") }}',
                    type: 'GET',
                    data: { subdivision_id: subdivisionId },
                    success: function (data) {
                        if (data.policeStations) {
                            $.each(data.policeStations, function (key, value) {
                                $('#police_station_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
