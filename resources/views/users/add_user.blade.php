@extends('layouts.main')
@section('title','Dashboard')

@section('content')

<div class="row affix-row">
    <div class="col-sm-12 col-md-12">
        <div class="affix-content">
            <form class="pt-4" method="POST" action="{{ route('user.store') }}" enctype='multipart/form-data'>
                @csrf
                <div class="row">

                    <!-- User Name -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">User Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Type your full name" required value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <!-- National ID -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">National ID</label>
                        <input type="text" id="cnic" name="cnic" class="form-control" placeholder="Type CNIC" required value="{{ old('cnic') }}">
                        @if ($errors->has('cnic'))
                            <span class="text-danger">{{ $errors->first('cnic') }}</span>
                        @endif
                    </div>

                    <!-- Email -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Type your email" required value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <!-- Mobile Number -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">Mobile Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="0000 - 0000 000" required value="{{ old('phone') }}">
                        @if ($errors->has('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">Password (Max 8)</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Type Password" required maxlength="8">
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">Re-type Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-type Password" required maxlength="8">
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>

                    <!-- Department -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">Department</label>
                        <select name="department_id" id="department_id" class="form-control" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('department_id'))
                            <span class="text-danger">{{ $errors->first('department_id') }}</span>
                        @endif
                    </div>

                    <!-- Designation -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">Designation</label>
                        <select name="designation_id" id="designation_id" class="form-control" required>
                            <option value="">Select Designation</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                    {{ $designation->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('designation_id'))
                            <span class="text-danger">{{ $errors->first('designation_id') }}</span>
                        @endif
                    </div>

                    <!-- Administrative Unit -->
                    

                 

                    <!-- User Role -->
                    <div class="col-lg-6 mb-4">
                        <label class="label-font">User Role (Access Permissions)</label>
                        <select name="roles[]" id="roles" class="form-control" multiple required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ (collect(old('roles'))->contains($role->name)) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('roles'))
                            <span class="text-danger">{{ $errors->first('roles') }}</span>
                        @endif
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-lg-12 text-right mt-4">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add New User</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('jsfile')

@endsection
