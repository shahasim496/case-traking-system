@extends('layouts.main')
@section('title','Edit Contact')
@section('breadcrumb','Edit Contact')

@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('contact.update',['officer_id'=>$officerObj->id,'id'=>$record->id]) }}" enctype='multipart/form-data'>
            @csrf

            <input type="hidden" name="_method" value="PUT">

            <div class="row">
                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type email"
                        value="{{old('email',$record->email)}}" name="email" id="email">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type mobile number"
                        value="{{old('mobile_number',$record->mobile_number)}}" name="mobile_number" id="mobile_number">
                        @if ($errors->has('mobile_number'))
                            <span class="text-danger">{{ $errors->first('mobile_number') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">Landline Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type landline number"
                        value="{{old('landline_number',$record->landline_number)}}" name="landline_number" id="landline_number">
                        @if ($errors->has('landline_number'))
                            <span class="text-danger">{{ $errors->first('landline_number') }}</span>
                        @endif
                    </div>
                </div>


            </div>

            <div class="row">

                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Permanent Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="present_address" name="present_address" cols="30"
                        rows="4" spellcheck="false">{{old('present_address',$record->present_address)}}</textarea>
                        @if ($errors->has('present_address'))
                            <span class="text-danger">{{ $errors->first('present_address') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Temporary Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="temporary_address" name="temporary_address" cols="30"
                        rows="4" spellcheck="false">{{old('temporary_address',$record->temporary_address)}}</textarea>
                        @if ($errors->has('temporary_address'))
                            <span class="text-danger">{{ $errors->first('temporary_address') }}</span>
                        @endif
                    </div>
                </div>

            </div>

                <div class="col-lg-12 btn-submit mt-2 mb-2 pl-0">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{route('spouses',$officerObj->id)}}" class="btn btn-secondary">Back</a>
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
