@extends('layouts.main')
@section('title','Add Posting Service')
@section('breadcrumb','Add Posting Service')

@section('content')

<div class="row affix-row form-main">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('service.store',$officerObj->id) }}" enctype='multipart/form-data'>
            @csrf

            <div class="row">
                <div class="col-lg-4 col-12 col-md-4 ">

                <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active">
                <label class="form-check-label" for="is_active">
                    Current Posting
                </label>
                </div>

                @if ($errors->has('is_active'))
                    <span class="text-danger">{{ $errors->first('is_active') }}</span>
                @endif

                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">Notification Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" placeholder="Type officer P No"
                        value="{{old('notification_date')}}" name="notification_date" id="notification_date">
                        @if ($errors->has('notification_date'))
                            <span class="text-danger">{{ $errors->first('notification_date') }}</span>
                        @endif
                        </div>
                </div>
                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">From Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" placeholder="Type from"
                        value="{{old('from_date')}}" name="from_date" id="from_date">
                        <small class="text-muted">(If date is not confirm then enter first day of month.)</small>
                        @if ($errors->has('from_date'))

                            <span class="text-danger">{{ $errors->first('from_date') }}</span>
                        @endif
                        </div>
                </div>
                <div class="col-lg-4 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">To Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" placeholder="Type to"
                        value="{{old('to_date')}}" name="to_date" id="to_date">
                        <small class="text-muted">(If date is not confirm then enter last day of month.)</small>

                        @if ($errors->has('to_date'))
                            <span class="text-danger">{{ $errors->first('to_date') }}</span>
                        @endif
                        </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-6 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Post <span class="text-danger">*</span></label>
                        <?php $post_id = old('post_id'); ?>
                        <select data-placeholder="Select Post" data-allow-clear="1"
                        name="post_id" id="post_id">
                            <option></option>
                            @foreach($posts as $p=>$post)
                            <option value="{{$post->id}}" {{$post_id == $post->id ? 'selected' : ''}}>{{$post->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('post_id'))
                            <span class="text-danger">{{ $errors->first('post_id') }}</span>
                        @endif
                        </div>
                </div>
                <div class="col-lg-6 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Areas of responsibility</label>
                        <input type="text" class="form-control" placeholder="Type Specialization"
                        value="{{old('specialization')}}" name="specialization" id="specialization">

                        @if ($errors->has('specialization'))
                            <span class="text-danger">{{ $errors->first('specialization') }}</span>
                        @endif
                        </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">Grade <span class="text-danger">*</span></label>
                        <?php $grade_id = old('grade_id'); ?>
                        <select data-placeholder="Select Grade" data-allow-clear="1"
                        name="grade_id" id="grade_id">
                            <option></option>
                            @foreach($grades as $p=>$grade)
                            <option value="{{$grade->id}}" {{$grade_id == $grade->id ? 'selected' : ''}}>{{$grade->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('prefix'))
                            <span class="text-danger">{{ $errors->first('prefix') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Province <span class="text-danger">*</span></label>
                        <?php $province_id = old('province_id'); ?>
                        <select data-placeholder="Select Province" data-allow-clear="1"
                        name="province_id" id="province_id">
                            <option></option>
                            @foreach($provinces as $p=>$province)
                            <option value="{{$province->id}}" {{$province_id == $province->id ? 'selected' : ''}}>{{$province->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('province_id'))
                            <span class="text-danger">{{ $errors->first('province_id') }}</span>
                        @endif
                        </div>
                </div>
                <div class="col-lg-4 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Ministry <span class="text-danger">*</span></label>
                        <?php $ministry_id = old('ministry_id'); ?>
                        <select data-placeholder="Select Ministry" data-allow-clear="1"
                        name="ministry_id" id="ministry_id">
                            <option></option>
                            @foreach($ministries as $p=>$ministry)
                            <option value="{{$ministry->id}}" {{$ministry_id == $ministry->id ? 'selected' : ''}}>{{$ministry->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('ministry_id'))
                            <span class="text-danger">{{ $errors->first('ministry_id') }}</span>
                        @endif
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">Division <span class="text-danger">*</span></label>
                        <?php $division_id = old('division_id'); ?>
                        <select data-placeholder="Select Division" data-allow-clear="1"
                        name="division_id" id="division_id">
                            <option></option>
                            @foreach($divisions as $p=>$division)
                            <option value="{{$division->id}}" {{$division_id == $division->id ? 'selected' : ''}}>{{$division->name}}</option>
                            @endforeach
                        </select>
                         @if ($errors->has('division_id'))
                            <span class="text-danger">{{ $errors->first('division_id') }}</span>
                        @endif
                        </div>
                </div>
                <div class="col-lg-4 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Department / Office <span class="text-danger">*</span></label>
                        <?php $department_id = old('department_id'); ?>
                        <select data-placeholder="Select Department" data-allow-clear="1"
                        name="department_id" id="department_id">
                            <option></option>
                            @foreach($departments as $p=>$department)
                            <option value="{{$department->id}}" {{$department_id == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('department_id'))
                            <span class="text-danger">{{ $errors->first('department_id') }}</span>
                        @endif
                        </div>
                </div>
                <div class="col-lg-4 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">District <span class="text-danger">*</span></label>
                        <?php $district_id = old('district_id'); ?>
                        <select data-placeholder="Select District" data-allow-clear="1"
                        name="district_id" id="district_id">
                            <option></option>
                            @foreach($districts as $p=>$district)
                            <option value="{{$district->id}}" {{$district_id == $district->id ? 'selected' : ''}}>{{$district->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('district_id'))
                            <span class="text-danger">{{ $errors->first('district_id') }}</span>
                        @endif
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">City/Town <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type city"
                        value="{{old('city')}}" name="city" id="city">

                        @if ($errors->has('city'))
                            <span class="text-danger">{{ $errors->first('city') }}</span>
                        @endif
                        </div>
                </div>
                <div class="col-lg-4 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Type of Posting <span class="text-danger">*</span></label>
                        <?php $posting_type_id = old('posting_type_id'); ?>
                        <select data-placeholder="Select Posting Type" data-allow-clear="1"
                        name="posting_type_id" id="posting_type_id">
                            <option></option>
                            @foreach($post_types as $p=>$post_type)
                            <option value="{{$post_type->id}}" {{$posting_type_id == $post_type->id ? 'selected' : ''}}>{{$post_type->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('posting_type_id'))
                            <span class="text-danger">{{ $errors->first('posting_type_id') }}</span>
                        @endif
                        </div>
                </div>

                <div class="col-lg-4 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Attach Notificaiton</label>
                        <input type="file" class="form-control" placeholder="Posting list Organization"
                        value="{{old('notification_attachment')}}" name="notification_attachment" id="notification_attachment">
                        @if ($errors->has('notification_attachment'))
                            <span class="text-danger">{{ $errors->first('notification_attachment') }}</span>
                        @endif
                    </div>
                </div>


            </div>
            <div class="row">

                <div class="col-lg-6 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Any Further Details</label>
                        <textarea class="form-control" id="further_detail" name="further_detail" cols="30"
                                  rows="4" spellcheck="false">{{old('further_detail')}}</textarea>

                        @if ($errors->has('further_detail'))
                            <span class="text-danger">{{ $errors->first('further_detail') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Posting List</label>
                        <textarea class="form-control" id="post_list" name="post_list" cols="30"
                                  rows="4" spellcheck="false">{{old('post_list')}}</textarea>

                        @if ($errors->has('post_list'))
                            <span class="text-danger">{{ $errors->first('post_list') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-12 col-md-4">
                    <div class="form-group">
                        <label for="" class="label-font">Posting Organization</label>
                        <textarea class="form-control" id="orgnization_list" name="orgnization_list" cols="30"
                                  rows="4" spellcheck="false">{{old('orgnization_list')}}</textarea>

                        @if ($errors->has('orgnization_list'))
                            <span class="text-danger">{{ $errors->first('orgnization_list') }}</span>
                        @endif
                    </div>
                </div>
            </div>


                <div class="col-lg-12 btn-submit mt-2 mb-2 pl-0">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{route('services',$officerObj->id)}}" class="btn btn-secondary">Back</a>
                </div>

        </form>


    </div>
    </div>
</div>


@endsection

@section('jsfile')

<script>

$(function(){

    $('#department_id').select2('destroy');
    $('#department_id').empty();
    $('#department_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                department: params.term,
                type: 'department'
            }

            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

    $('#post_id').select2('destroy');
    $('#post_id').empty();
    $('#post_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                post: params.term,
                type: 'post'
            }
            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

    $('#division_id').select2('destroy');
    $('#division_id').empty();
    $('#division_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                division: params.term,
                type: 'division'
            }
            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

  });//end of function

</script>

@endsection
