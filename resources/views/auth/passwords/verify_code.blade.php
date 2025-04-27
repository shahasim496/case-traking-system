@extends('layouts.web')
@section('title','Verify Account')

@section('content')

<div class="sign-in">

            <div class="out-div">
            <h1 class="heading">Verify Account</h1>
            <p>We  have  sent  a  verification code on your email
account  Please enter
verification code below. </p>
            </div>
           <form class="form-signin"method="POST" action="{{ route('verifyUserCode') }}">
            @csrf
                <div class="single-input">
                <div class="code_group">
        <input type="text" id="code1" name="code1" class="form-control" maxlength="1" required>
        <input type="text" id="code2" name="code2" class="form-control" maxlength="1" required>
        <input type="text" id="code3" name="code3" class="form-control" maxlength="1" required>
        <input type="text" id="code4" name="code4" class="form-control" maxlength="1" required>
      </div>

                </div>

                <!-- <div class="mt-2 spacebox"></div> -->
                <div class="row">
                    <div class="col-lg-12 text-center mt-2 reg-now">
                        <p>Don't have an account? <a href="{{route('resendCode')}}">Send Again</a></p>
                    </div>
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-sm btn-success" type="submit">Verify</button>
                    </div>

                </div>
            </form>


        </div>

@endsection
