@extends('layouts.main')
@section('title','Change Password')

@section('content')
<div class="affix-content">
<div class="sign-in">

            <div class="out-div">
            <h1 class="heading">Change Password</h1>
            </div>
           <form class="form-signin"method="POST" action="{{ route('user.resetPassword') }}">
                @csrf
                <div class="single-input">
                <label class="label-font">New Password</label>
                <input type="hidden" id="email" name="email" value="{{$email}}">

                <input id="password" type="password"
                class="form-control mb-3 @error('password') is-invalid @enderror"
                placeholder="Enter your new password"
                name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                </div>
                <div class="single-input">
                <label class="label-font">Confirm New Password</label>

                <input id="password-confirm" type="password" class="form-control mb-3"
                name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="mt-3 spacebox"></div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-sm btn-success" type="submit">Update</button>
                    </div>
                </div>
            </form>


        </div>
</div>
@endsection
