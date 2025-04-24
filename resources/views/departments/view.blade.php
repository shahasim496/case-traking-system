@extends('layouts.main')
@section('title','View Department')
@section('breadcrumb','View Department')
@section('edit','Edit')
@section('link',route('department.edit',['id'=>$record->id]))

@section('content')

    <div class="row affix-row form-main">

        <div class="col-lg-12 col-md-12 col-12 mb-3">
            <div class=" card p-gray p-5">

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Name:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->name}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Type:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->organization_type->name ?? ''}}</p>
                    </div>
                </div>

            </div>
            <div class="col-lg-12 mt-2 pl-0">

            <!-- <a href="{{route('department.delete',['id'=>$record->id])}}"
                    onClick="return deleteR({{$record->id}});"
                    id="delete_{{$record->id}}" class="btn btn-danger">Delete</a> -->
                <a href="{{route('department')}}" class="btn btn-secondary">Back</a>
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
