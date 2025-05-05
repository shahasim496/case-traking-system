<!DOCTYPE html>
<html class="loading" lang="en" style="--vs-primary:234,84,85; --vs-success:40,199,111; --vs-danger:234,84,85; --vs-warning:255,159,67; --vs-dark:30,30,30; --vh:6.63px;" data-textdirection="ltr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description" content="Responsive Admin Template" />
    <meta name="author" content="SmartUniversity" />
    <title>Evidence Management System</title>
    <link rel="apple-touch-icon" href="/images/Mask Group 17.svg">
    <link rel="shortcut icon" type="image/x-icon" href="/images/Mask Group 17.svg">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    @include('layouts.css')
    @yield('cssfile')
      <!-- favicon -->
      <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />
      

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white dark-sidebar-color logo-dark">
    <div class="page-wrapper">
    @include('layouts.header')

    <div class="page-container">
        @include('layouts.sidebar')
        <!-- start page content -->
        <div class="page-content-wrapper homepage">
            @yield('content')
        </div>
        <!-- end page content -->
    </div>
    @include('layouts.footer')
    </div>

    @include('layouts.js')
    @yield('jsfile')
    </body>
    <!-- END: Body-->

    </html>
