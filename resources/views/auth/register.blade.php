@extends('layouts.web')
@section('title','Register')

@section('content')

<div class="sign-in">
                     <div class="out-div">
                        <h1 class="heading">Register</h1>
                     </div>
                     <form class="form-signin"method="POST" action="#">
                        @csrf
                        <div class="single-input">
                           <label class="label-font">Full Name</label>

                            <input id="name" type="text" class="form-control mb-3 @error('name') is-invalid @enderror"
                            placeholder="Full Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="single-input">
                           <label class="label-font">Email</label>

                            <input id="email" type="email" class="form-control mb-3 @error('email') is-invalid @enderror"
                            placeholder="Email address"
                            name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="single-input">
                           <label class="label-font">Password</label>

                            <input id="password" type="password" class="form-control mb-3 @error('password') is-invalid @enderror"
                            placeholder="Password"
                            name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="single-input">
                           <label class="label-font">Confirm Password</label>

                           <input id="password-confirm" type="password" class="form-control mb-3"
                                placeholder="Confirm Password" name="password_confirmation"
                                required autocomplete="new-password">

                        </div>
                        <div class="mt-3 spacebox"></div>
                        <div class="row">
                           <div class="col-lg-12 text-center">
                              <button class="btn btn-sm btn-success" type="submit">Register</button>
                           </div>
                           <div class="col-lg-12 text-center mt-3 reg-now">
                              <p>Don't have an account? <a href="{{route('login')}}">Login now!</a></p>
                           </div>
                        </div>
                     </form>
                  </div>


@endsection
