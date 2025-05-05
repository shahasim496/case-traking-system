<!DOCTYPE html>
<html class="loading" lang="en" style="--vs-primary:234,84,85; --vs-success:40,199,111; --vs-danger:234,84,85; --vs-warning:255,159,67; --vs-dark:30,30,30; --vh:6.63px;" data-textdirection="ltr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description" content="Responsive Admin Template" />
    <meta name="author" content="SmartUniversity" />
    <title>Evidence Management System</title>
    <link rel="apple-touch-icon" href="/images/Mask Group 17.svg">
    <link rel="shortcut icon" type="image/x-icon" href="/images/Mask Group 17.svg">
 
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    


    


    @include('layouts.css')
    @yield('cssfile')
      <!-- favicon -->
      <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white dark-sidebar-color logo-dark ">
    <div class="page-wrapper">
    @include('layouts.header')

    <div class="page-container">
        @include('layouts.sidebar')
        <!-- start page content -->
        <div class="page-content-wrapper homepage">
            <div class="page-content pt-4">

            @if(!empty($officerObj))
            <div class="text-center">
                <h1 class="page-title">
                {{!empty($officerObj) ? $officerObj->first_name.' '.$officerObj->middle_name.' '.$officerObj->last_name  : ''}}
                </h1>

                @php
                    $group_name = null;
                    if(!empty($officerObj)){
                        $group_name = $officerObj->group_service->name.'('.$officerObj->current_grade->name.')';
                    }
                @endphp
                <p class="page-title">{{$group_name}}</p>
            </div>
            @endif

            <div class="col-12 mt-2">
                @hasSection('back')
                <a href="@yield('back')" class="text-left btn-secondary  btn">  <i class="fa fa-chevron-left mr-2" ></i>Back to Profile</a>
                @endif

                <div class="pull-right">

                    @hasSection('add')
                    <a href="@yield('link')" class="btn btn-info">Add</a>
                    @endif

                    @hasSection('edit')
                    <a href="@yield('link')" class="btn btn-info">Edit</a>
                    @endif
                </div>

            </div>




                @yield('content')
            </div>
        </div>
        <!-- end page content -->
    </div>
    @include('layouts.footer')
    </div>

    @include('layouts.js')
    @yield('jsfile')

    
 

    <script>

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $('#notification_click').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                /* the route pointing to the post function */
                url: '/user/markNotification',
                type: 'GET',
                /* send the csrf-token and the input to the controller */
                data: {name: 'test'},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {

                    $("#unread_count").text(data.count);
                    $("#unread_count1").text(data.count);
                    console.log(data);
                }
            });

        });

        function deleteR(id) {
            event.preventDefault();

            var url = $("#delete_"+id).attr('href');
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
        }//end of function

    </script>

   </body>
    <!-- END: Body-->

    </html>