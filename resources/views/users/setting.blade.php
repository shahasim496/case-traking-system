@extends('layouts.main')
@section('title','Dashboard')

@section('content')

<div class="row affix-row">
    <div class="col-sm-12 col-md-12">
    <div class="affix-content">
        <div class="page-header">
            <h3>My Profile</h3>
        </div>
        <form method="POST" action="{{ route('user.updateProfile',$user->id) }}" enctype='multipart/form-data'>
            @csrf
            <div class="row">
                <div class="col-lg-12 profile-picture">
                <div class="small-12 medium-2 large-2 columns">
                    <div class="circle">
                        <!-- User Profile Image -->
                        <img class="profile-pic" src="{{$user->profile ? $user->profile->picture_attachment_id : '/images/profile.jpg'}}">

                        <!-- Default Image -->
                        <!-- <i class="fa fa-user fa-5x"></i> -->
                    </div>
                    <div class="p-image">
                        <i class="fa fa-camera upload-button"></i>
                        <input class="file-upload" name="picture_attachment" type="file" accept="image/*"/>
                    </div>
                    <label>Upload Profile Picture:</label>
                    @if ($errors->has('picture_attachment'))
                        <label class="text-danger">{{ $errors->first('picture_attachment') }}</label>
                    @endif

                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                <label class="label-font">Name</label>
                <input type="text" id="name" class="form-control "
                name="name" value="{{$user->name}}"
                placeholder="Enter your name" required="">

                @if ($errors->has('name'))
                    <span class="danger">{{ $errors->first('name') }}</span>
                @endif

                </div>
                <div class="col-lg-6 mb-3">
                <label class="label-font">CNIC</label>
                <input type="number" id="cnic" class="form-control "
                name="cnic" value="{{old('cnic', $user->profile->cnic ?? '')}}"
                placeholder="Enter your CNIC" required="">

                @if ($errors->has('cnic'))
                    <span class="danger">{{ $errors->first('cnic') }}</span>
                @endif

                </div>
                <div class="col-lg-6 mb-3">
                <label class="label-font">Phone Number</label>
                <input type="text" id="number" class="form-control "
                name="phone" value="{{old('phone', $user->profile->phone ?? '')}}"
                placeholder="Enter your phone number" required="">

                @if ($errors->has('phone'))
                    <span class="danger">{{ $errors->first('phone') }}</span>
                @endif

                </div>
                <div class="col-lg-6 mb-3">
                <label class="label-font">Email</label>
                <input type="email" id="email" class="form-control "
                name="email" value="{{$user->email}}"
                placeholder="Enter your email Address" required="">

                @if ($errors->has('email'))
                    <span class="danger">{{ $errors->first('email') }}</span>
                @endif

                </div>
                <div class="col-md-6 mt-2">
                <label class="label-font">Province</label>
                <?php $province_id = $user->profile->province_id ?? ''; ?>

                <select class="form-control mb-3" required="" id="province_id" name="province_id">
                    <option value="">Select Province</option>
                    @foreach($provinces as $p=>$province)
                    <option value="{{$province->id}}" {{$province->id == $province_id ? 'selected' : ''}}>{{$province->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('province_id'))
                    <span class="danger">{{ $errors->first('province_id') }}</span>
                @endif
                </div>
                <div class="col-md-6 mt-2">
                <label class="label-font">Tehsil</label>
    <?php $tehsil_id = $user->profile->tehsil_id ?? ''; ?>

                <select class="form-control mb-3" required="" id="tehsil_id" name="tehsil_id">
                    <option value="">Select Tehsil</option>
                    @foreach($tehsils as $p=>$tehsil)
                    <option value="{{$tehsil->id}}" {{$tehsil->id == $tehsil_id ? 'selected' : ''}}>{{$tehsil->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('tehsil_id'))
                    <span class="danger">{{ $errors->first('tehsil_id') }}</span>
                @endif

                </div>
                <div class="col-lg-6">
                <label class="label-font">District</label>

                <?php $district_id = $user->profile->district_id ?? ''; ?>

                <select class="form-control mb-3" required="" id="district_id" name="district_id">
                    <option value="">Select District</option>
                    @foreach($districts as $p=>$district)
                    <option value="{{$district->id}}" {{$district->id == $district_id ? 'selected' : ''}}>{{$district->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('district_id'))
                    <span class="danger">{{ $errors->first('district_id') }}</span>
                @endif

                </div>
                <div class="col-lg-6">
                <label class="label-font">Residential Address</label>
                <input type="text" id="posting" class="form-control mb-3"
                name="residential_address" value="{{old('residential_address', $user->profile->residential_address ?? '')}}"
                placeholder="Enter your residential address" required="">

                @if ($errors->has('residential_address'))
                    <span class="danger">{{ $errors->first('residential_address') }}</span>
                @endif

                </div>

                <div class="col-lg-12 btn-submit mt-2 mb-3 text-center">
                <button type="Submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>
<div class="row affix-row mt-3">
    <div class="col-sm-12 col-md-12">

    <form method="POST" action="{{ route('user.updatePassword',$user->id) }}">
    @csrf
    <div class="affix-content">
        <div class="page-header">
            <h3>Password</h3>
        </div>
        <div class="userpassword-sec">
        <div class="col-lg-12 mb-3">
                <label class="label-font">Current Password</label>
                <input type="password" id="password" name="current_password" class="form-control "
                placeholder="Enter your current password" required="">
                @if ($errors->has('current_password'))
                    <span class="text-danger">{{ $errors->first('current_password') }}</span>
                @endif
        </div>
        <div class="col-lg-12 mb-3">
                <label class="label-font">New Password</label>
                <input type="password" id="password" name="password" class="form-control " placeholder="Enter your new password" required="">
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
        </div>
        <div class="col-lg-12 mb-3">
                <label class="label-font">Confirm Password</label>
                <input type="password" id="password" name="password_confirmation" class="form-control " placeholder="Re-enter your new password" required="">
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
        </div>
        <div id="Forget-pass" class="checkbox pull-right mt-n2" style="padding-right: 15px;">
        <!-- <a href="{{ route('user.sendCode') }}" class="forgot-password">
        Forgot Password?

        </a> -->
        </div>
        <div class="col-lg-12 btn-submit mt-2 mb-3 text-center" style="display: inline-block;">
                <button type="Submit" class="btn btn-primary">Update</button>
        </div>
    </div>
    </form>

    </div>
    </div>
</div>

@endsection

@section('jsfile')

<script>
    $(document).ready(function() {

        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.profile-pic').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }


        $(".file-upload").on('change', function(){
            readURL(this);
        });

        $(".upload-button").on('click', function() {
            $(".file-upload").click();
        });
    });
</script>

@endsection

