@extends('layouts.web')
@section('title','Forgot Password')
@section('content')

<div class="sign-in">

            <div class="out-div">
            <h1 class="heading">Forgot Password</h1>
            <p>Enter your email address to receive reset link.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

           <form class="form-signin" method="POST" action="{{ route('sendcode') }}">
                @csrf
                <div class="single-input">
                <label class="label-font">Email</label>
                <input id="email" type="email" placeholder="Email address"
                class="form-control mb-3 @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>

                <div class="mt-2 spacebox"></div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-sm btn-success" type="submit">Send Reset Link</button>
                    </div>
                    <div class="col-lg-12 text-center mt-3 reg-now">
                    	<p>Don't have an account? <a href="#">Send Again</a></p>
                    </div>
                </div>
            </form>


        </div>

@endsection
