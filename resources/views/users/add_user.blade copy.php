@extends('layouts.main')
@section('title','Dashboard')
@section('breadcrumb','Add User')

@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('user.store') }}" enctype='multipart/form-data'>
            @csrf
            <div class="row">

                <div class="col-lg-6">
                <label class="label-font">User name (As per CNIC)</label>
                <input type="text" id="name" name="name" class="form-control mb-3"
                placeholder="Type your full name" required="" value="{{old('name')}}">
                @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">CNIC</label>
                <input type="text" id="cnic" name="cnic" class="form-control mb-3"
                placeholder="Type CNIC" required="" value="{{old('cnic')}}">
                @if ($errors->has('cnic'))
                        <span class="text-danger">{{ $errors->first('cnic') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">Email</label>
                <input type="email" id="email" name="email" class="form-control mb-3"
                placeholder="Type your email" required="" value="{{old('email')}}">
                @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">Mobile Number</label>
                <input type="text" id="phone" name="phone" class="form-control mb-3"
                placeholder="0000 - 0000 000" required="" value="{{old('phone')}}">
                @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
                </div>
                <div class="col-md-6">
                <label class="label-font">(Password Max 8)</label>
                <input type="Password" id="password" name="password" placeholder="Type Password" class="form-control mb-3" required="" maxlength="8">
                @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                </div>
                <div class="col-md-6 mt-2">
                <label class="label-font">*Re-type Password</label>
                <input type="Password" id="password_confirmation" name="password_confirmation" placeholder="Re-type Password" class="form-control mb-3" required="" maxlength="8">

                @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
                </div>
                <div class="col-lg-6">
                <label class="label-font">Designation</label>
                <input type="text" id="designation" name="designation" class="form-control mb-3"
                placeholder="Type your designation" required="" value="{{old('designation')}}">
                <select name="designation_id" id="designation_id" class="form-control mb-3" required="">
                    <option value=""> Type Administration's Designation</option>
                    @foreach($designations as $designation)
                    <option value="{{$designation->id}}">{{$designation->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('designation'))
                        <span class="text-danger">{{ $errors->first('designation') }}</span>
                @endif
                </div>
                <div class="col-lg-6">
                <label class="label-font">User Role (Access Permissions)</label>
                <select name="role" id="role" class="form-control mb-3" required="">
                    <option value=""> Type Administration's Designation</option>
                    @foreach($roles as $role)
                    <option value="{{$role->name}}">{{$role->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('role'))
                        <span class="text-danger">{{ $errors->first('role') }}</span>
                @endif
                </div>


                <div class="col-lg-12 btn-submit mt-2 mb-2">
                <button type="button" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Add new user</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>


@endsection

@section('jsfile')

<script>

</script>

@endsection
