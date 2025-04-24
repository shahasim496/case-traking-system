@extends('layouts.web')
@section('title','Login')

@section('content')

<div class="sign-in">

            <div class="out-div">
            <h1 class="heading">Login</h1>
            </div>
           <form class="form-signin" method="POST" action="{{ route('login') }}">
                @csrf

                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                        <span>{{ $message }}</span>
                </div>
                @endif

                <div class="single-input">
                <label class="label-font">Email</label>

                <input id="email" type="email" class="form-control mb-3 @error('email') is-invalid @enderror"
                placeholder="Email address"name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                </div>
                <div class="single-input">
                <label class="label-font">Password</label>

                <input id="password" type="password" class="form-control mb-3 @error('password') is-invalid @enderror"
                placeholder="Password" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                </div>
                <div id="Forget-pass" class="checkbox pull-right mt-n2">
                    <a href="{{ route('password.request') }}" class="forgot-password">
               Forgot Password?

            </a>
                </div>
                <div class="mt-3 spacebox"></div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-sm btn-success" type="submit">Login</button>
                    </div>
                    <div class="col-lg-12 text-center mt-3 reg-now">
                    </div>
                </div>
            </form>


        </div>


@endsection
