@extends('layouts.main')
@section('title','Edit Posting')
@section('breadcrumb','Edit Posting')

@section('content')

<div class="row affix-row">

    <div class="col-sm-12 col-md-12">
    <div class="affix-content">

        <form class="pt-4" method="POST" action="{{ route('country.update',['id'=>$record->id]) }}" enctype='multipart/form-data'>
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="row">
                <div class="col-lg-6 col-12 col-md-6 ">
                    <div class="form-group">
                        <label for="" class="label-font">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Type Name"
                        value="{{old('name',$record->name)}}" name="name" id="name">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        </div>
                </div>

            </div>

            <div class="col-lg-12 btn-submit mt-2 mb-2 pl-0">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{route('country')}}" class="btn btn-secondary">Back</a>
            </div>

        </form>


    </div>
    </div>
</div>


@endsection

@section('jsfile')

<script>

    function deleteR(id) {
    event.preventDefault();

    var url = $("#delete_"+id).attr('href');
   console.log(url);
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      confirmButtonClass: 'btn btn-primary',
      cancelButtonClass: 'btn btn-danger ml-1',
      buttonsStyling: false,
    }).then(function (result) {
      console.log(result);
      if (result.value) {
        console.log("yes delete it.");
        window.location.href = url;
      }
      else if (result.dismiss === Swal.DismissReason.cancel) {
        console.log("cancel.");

      }
    });
      // if(!confirm("Are You Sure to delete this"))
      //     event.preventDefault();
  }

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

    var department_data = {
        id: '<?php echo $record->department->id ?? '' ?>',
        text: '<?php echo $record->department->name ?? '' ?>'
    };

    var newOption = new Option(department_data.text, department_data.id, false, false);
    $("#department_id").append(newOption).trigger("change");


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

    var post_data = {
        id: '<?php echo $record->post->id ?? '' ?>',
        text: '<?php echo $record->post->name ?? '' ?>'
    };

    var newOption = new Option(post_data.text, post_data.id, false, false);
    $("#post_id").append(newOption).trigger("change");

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

    var division_data = {
        id: '<?php echo $record->division->id ?? '' ?>',
        text: '<?php echo $record->division->name ?? '' ?>'
    };

    var newOption = new Option(division_data.text, division_data.id, false, false);
    $("#division_id").append(newOption).trigger("change");

  });//end of function

</script>

@endsection
