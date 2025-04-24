@extends('layouts.main')
@section('title','Change Password')
@section('breadcrumb','Change Password')

@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('user.savePassword') }}" enctype='multipart/form-data'>
            @csrf

            <div class="row">

                <div class="col-lg-6">
                <label class="label-font">Current Password<span class="text-danger">*</span></label>
                <input type="password" id="current_password" name="current_password" class="form-control mb-3"
                placeholder="Current Password" required="" value="{{old('current_password')}}">
                @if ($errors->has('current_password'))
                        <span class="text-danger">{{ $errors->first('current_password') }}</span>
                @endif
                </div>
            </div>

            <div class="row">

                <div class="col-lg-6">
                <label class="label-font">New Password<span class="text-danger">*</span></label>
                <input type="password" id="password" name="password" class="form-control mb-3"
                placeholder="New Password" required="" value="{{old('password')}}">
                @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                </div>

            </div>

            <div class="row">
                <div class="col-lg-6">
                <label class="label-font">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control mb-3"
                placeholder="Confirm Password" required="" value="{{old('confirm_password')}}">
                @if ($errors->has('confirm_password'))
                        <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                @endif
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 btn-submit mt-2 mb-2">
                <button type="submit" class="btn btn-primary">Save</button>
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

</script>

@endsection
