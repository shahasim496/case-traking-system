@extends('layouts.main')
@section('title','View Contact')
@section('breadcrumb','View Contact')
@section('edit','Edit')
@section('link',route('contact.edit',['officer_id'=>$officerObj->id,'id'=>$record->id]))

@section('content')

<div class="row affix-row form-main">

        <div class="col-lg-12 col-md-12 col-12 mb-3">
            <div class=" card p-gray p-5">



                        <div class="row mb-2">
                            <div class="col-lg-3 col-md-6 col-12">
                                <b>Email:</b>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12">
                                <p>{{$record->email}}</p>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3 col-md-6 col-12">
                                <b>Mobile #:</b>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12">
                                <p>{{$record->mobile_number}}</p>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3 col-md-6 col-12">
                                <b>Landline #:</b>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12">
                                <p>{{$record->landline_number}}</p>
                            </div>
                        </div>

                        <div class="row mb-2" >
                            <div class="col-lg-3 col-md-6 col-12">
                                <b>Present Address:</b>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12">
                                <p>{{$record->present_address}}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3 col-md-6 col-12">
                                <b>Temporary Address:</b>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12">
                                <p>{{$record->temporary_address}}</p>
                            </div>
                        </div>

            </div>

            <div class="col-lg-12 mt-2 pl-0">

                <!-- <a href="{{route('contact.delete',['officer_id'=>$officerObj->id,'id'=>$record->id])}}"
                    onClick="return deleteR({{$record->id}});"
                    id="delete_{{$record->id}}" class="btn btn-danger">Delete</a> -->
                <a href="{{route('contacts',['officer_id'=>$officerObj->id,'id'=>$record->id])}}" class="btn btn-secondary">Back</a>
            </div>

        </div>


</div>


@endsection

@section('jsfile')

<script>
  function deleteR(id) {
    event.preventDefault();

console.log(id);
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
</script>

@endsection
