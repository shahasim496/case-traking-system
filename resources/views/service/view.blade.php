@extends('layouts.main')
@section('title','View Posting')
@section('breadcrumb','View Posting')
@section('edit','Edit')
@section('link',route('service.edit',['officer_id'=>$officerObj->id,'id'=>$record->id]))

@section('content')

    <div class="row affix-row form-main">

        <div class="col-lg-12 col-md-12 col-12 mb-3">
            <div class=" card p-gray p-5">



                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Notification Date:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->notification_date}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>From Date:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->from}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>To Date:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->to}}</p>
                    </div>
                </div>

                <div class="row mb-2" >
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Post:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->post->name ?? ''}}</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Grade:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->grade->name ?? ''}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Province:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->province->name ?? ''}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Ministry:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->ministry->name ?? ''}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Division:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->division->name ?? ''}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Department / Office:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->department->name ?? ''}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>District:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->district->name ?? ''}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>City/Town:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->city}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Type of Posting:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->posting_type->name ?? ''}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Details:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->further_detail}}</p>
                    </div>
                </div>


                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Posting list Post:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->post_list}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Posting list Organization:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <p>{{$record->orgnization_list}}</p>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <b>Notificaiton Attachment:</b>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        @if($record->notification_attachment_id)
                            <a href="{{$record->notification_attachment_id}}" target="_blank">View</a>
                        @else
                            -
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-lg-12 mt-2 pl-0">

            <!-- <a href="{{route('service.delete',['officer_id'=>$officerObj->id,'id'=>$record->id])}}"
                    onClick="return deleteR({{$record->id}});"
                    id="delete_{{$record->id}}" class="btn btn-danger">Delete</a> -->
                <a href="{{route('services',['officer_id'=>$officerObj->id,'id'=>$record->id])}}" class="btn btn-secondary">Back</a>
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
