@extends('layouts.main')
@section('title','Edit Officer')
@section('breadcrumb','Edit Officer')

@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('user.updateOfficer',$user->id) }}" enctype='multipart/form-data'>
            @csrf
            @method('PUT')
            <div class="row">

                <div class="col-lg-6">
                <label class="label-font">Service Group<span class="text-danger">*</span></label>
                @php
                $group_service_id = old('group_service_id',$user->officer->group_service_id);
                @endphp
                <select data-placeholder="Select Group Service" data-allow-clear="1"
                name="group_service_id" id="group_service_id" required="">
                    <option value=""></option>
                    @foreach($caders as $cader)
                    <option value="{{$cader->id}}" {{$group_service_id == $cader->id ? 'selected' : ''}}>{{$cader->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('group_service_id'))
                        <span class="text-danger">{{ $errors->first('group_service_id') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">Grade<span class="text-danger">*</span></label>
                @php
                $grade_id = old('grade_id',$user->officer->grade_id);
                @endphp
                <select data-placeholder="Select Grade" data-allow-clear="1"
                name="grade_id" id="grade_id" required="">
                    <option value=""></option>
                    @foreach($grades as $grade)
                    <option value="{{$grade->id}}" {{$grade_id == $grade->id ? 'selected' : ''}}>{{$grade->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('grade_id'))
                        <span class="text-danger">{{ $errors->first('grade_id') }}</span>
                @endif
                </div>

                <div class="col-lg-12">
                <label class="label-font">Officer Profile<span class="text-danger">*</span></label>
                @php
                $officer_id = old('officer_id',$user->officer->officer_id);
                @endphp
                <select data-placeholder="Select Officer Profile" data-allow-clear="1"
                name="officer_id" id="officer_id" required="">
                    <option value=""></option>
                    @foreach($officer_profiles as $officer_profile)
                    <option value="{{$officer_profile->id}}" {{$officer_id == $officer_profile->id ? 'selected' : ''}}>{{$officer_profile->name}}</option>
                    @endforeach

                </select>

                @if ($errors->has('officer_id'))
                        <span class="text-danger">{{ $errors->first('officer_id') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">User name (As per CNIC) <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control mb-3"
                placeholder="Type your full name" required="" value="{{old('name',$user->name)}}">
                @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">CNIC</label>
                <input type="text" id="cnic" name="cnic" class="form-control mb-3"
                placeholder="Type CNIC" required="" value="{{old('cnic',$user->profile->cnic)}}">
                @if ($errors->has('cnic'))
                        <span class="text-danger">{{ $errors->first('cnic') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control mb-3"
                placeholder="Type your email" required="" value="{{old('email',$user->email)}}">
                @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                </div>

                <div class="col-lg-6">
                <label class="label-font">Mobile Number <span class="text-danger">*</span></label>
                <input type="text" id="phone" name="phone" class="form-control mb-3"
                placeholder="0000 - 0000 000" value="{{old('phone',$user->profile->phone)}}">
                @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
                </div>
                <div class="col-md-6">
                <label class="label-font">Password <span class="text-danger">*</span></label>
                <input type="text" id="password" value="{{old('password',$user->officer->pass_code)}}" name="password" placeholder="Type Password" class="form-control mb-3">
                @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                </div>
                <div class="col-md-6 ">
                <label class="label-font">Re-type Password <span class="text-danger">*</span></label>
                <input type="text" id="password_confirmation" value="{{old('password',$user->officer->pass_code)}}" name="password_confirmation" placeholder="Re-type Password" class="form-control mb-3">

                @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
                </div>
                <div class="col-lg-6">
                <label class="label-font">Designation <span class="text-danger">*</span></label>
                <input type="text" id="designation" name="designation" class="form-control mb-3"
                placeholder="Type your designation" value="{{old('designation',$user->profile->designation)}}">

                @if ($errors->has('designation'))
                        <span class="text-danger">{{ $errors->first('designation') }}</span>
                @endif
                </div>

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

var grade_id = null;
var group_service_id = null;

$("#grade_id,#group_service_id").change(function () {
    var idd = this.value;
    reSetFields();

    if(this.id == 'grade_id'){
        grade_id = idd;
        group_service_id = $('#group_service_id').val();

    }else{
        group_service_id = idd;
        grade_id = $('#grade_id').val();
    }

    $('#officer_id')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select</option>')
    .val('');

    $.ajax({
        type:'GET',
        url:'{{route('user.getOfficerProfiles')}}',
        data:{
            group_service_id:group_service_id,
            grade_id:grade_id
        },
        success:function(data) {
            console.log(data);

            var officer_id = $('#officer_id');
            $.each(data, function(val, obj) {

                officer_id.append(
                    $('<option></option>').val(obj.id).html(obj.name)
                );
            });
        }
    });//end of function


});//end of function

$("#officer_id").change(function () {
    var officer_id = this.value;
    reSetFields();

    $.ajax({
        type:'GET',
        url:'{{route('user.getOfficerProfile')}}',
        data:{
            officer_id:officer_id,
            group_service_id:group_service_id,
            grade_id:grade_id
        },
        success:function(data) {
            console.log(data);

            $('#name').val(data.name);
            $('#cnic').val(data.cnic);
            $('#email').val(data.email);

            var pass = generatePassword(data.cnic);

            $('#password').val(pass);
            $('#password_confirmation').val(pass);

        }
    });//end of function


});//end of function

function reSetFields(){
    $('#name').val('');
    $('#cnic').val('');
    $('#email').val('');
    $('#phone').val('');
    $('#password').val('');
    $('#password_confirmation').val('');
    $('#designation').val('');
}//end of function

function generatePassword(value){

    var pass = Math.random().toString(36).replace(/[^a-z]+|1|i|l|L|I/g, '').substr(0, 3);
    value=value+'-'+pass;

    return value;
}//end of function

</script>

@endsection
