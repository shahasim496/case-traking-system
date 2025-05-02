@extends('layouts.main')
@section('title','Add Department')


@section('content')

<div class="row affix-row form-main">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('department.store') }}" enctype='multipart/form-data'>
            @csrf

            <div class="row">
                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type Name"
                        value="{{old('name')}}" name="name" id="name">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        </div>
                </div>

                <div class="col-lg-6 col-12 col-md-6">
                    <div class="form-group">
                        <label for="" class="label-font">Type<span class="text-danger">*</span></label>
                        <?php $organization_type_id = old('organization_type_id'); ?>
                        <select data-placeholder="Select Type" data-allow-clear="1"
                        name="organization_type_id" id="organization_type_id" style="width:100%">
                            <option></option>
                            @foreach($types as $c=>$type)
                                <option value="{{$type->id}}" {{$organization_type_id == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('organization_type_id'))
                            <span class="text-danger">{{ $errors->first('organization_type_id') }}</span>
                        @endif
                    </div>
                </div>

            </div>



                <div class="col-lg-12 btn-submit mt-2 mb-2 pl-0">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{route('department')}}" class="btn btn-secondary">Back</a>
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

  });//end of function

</script>

@endsection
