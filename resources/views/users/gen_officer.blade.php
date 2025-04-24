@extends('layouts.main')
@section('title','Genrate Officer')
@section('breadcrumb','Genrate Officer')

@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('user.genOfficer') }}" enctype='multipart/form-data'>
            @csrf
            <div class="row">

                <div class="col-lg-6">
                <label class="label-font">Service Group<span class="text-danger">*</span></label>
                @php
                $group_service_id = old('group_service_id',$group_service_id);
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
                $grade_id = old('grade_id',$grade_id);
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

                <div class="col-lg-12 btn-submit mt-2 mb-2">
                <button type="submit" class="btn btn-primary">Generate</button>
                <a type="button" href="{{route('user.all')}}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>

        @if($group_service_id && $grade_id)
            <a href="{{route('user.exportOfficer',['group_service_id'=>$group_service_id,'grade_id'=>$grade_id])}}" class="">Download Users</a>
        @endif

        @if($value = Session::get('export_path'))
            <a href="{{asset($value)}}" class="" download>Download Users</a>
        @endif
    </div>
    </div>
</div>


@endsection

@section('jsfile')

<script>

</script>

@endsection
