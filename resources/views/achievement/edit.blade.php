@extends('layouts.main')
@section('title','Edit Achievement')
@section('breadcrumb','Edit Achievement')

@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('achievement.update',['officer_id'=>$officerObj->id,'id'=>$record->id]) }}" enctype='multipart/form-data'>
            @csrf

            <input type="hidden" name="_method" value="PUT">

            <div class="row">
                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Award Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type award name"
                        value="{{old('award_name',$record->award_name)}}" name="award_name" id="award_name">
                        @if ($errors->has('award_name'))
                            <span class="text-danger">{{ $errors->first('award_name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Award Authority <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type award authority"
                        value="{{old('award_authority',$record->award_authority)}}" name="award_authority" id="award_authority">
                        @if ($errors->has('award_authority'))
                            <span class="text-danger">{{ $errors->first('award_authority') }}</span>
                        @endif
                    </div>
                </div>


            </div>

            <div class="row">

                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" placeholder="Type date"
                        value="{{old('date',$record->date)}}" name="date" id="landline_number">
                        @if ($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="decription" name="decription" cols="30"
                        rows="4" spellcheck="false">{{old('decription',$record->decription)}}</textarea>
                        @if ($errors->has('decription'))
                            <span class="text-danger">{{ $errors->first('decription') }}</span>
                        @endif
                    </div>
                </div>

            </div>

                <div class="col-lg-12 btn-submit mt-2 mb-2 pl-0">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{route('achievements',$officerObj->id)}}" class="btn btn-secondary">Back</a>
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
