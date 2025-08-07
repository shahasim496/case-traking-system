<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<base href="">
	<meta charset="utf-8" />
	<title>Personnel Information Management System</title>
	<meta name="description" content="Standard HTML" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="{{asset('assets/media/logos/fav.png')}}" />
	<link href="{{asset('assets/css/custom-style.css')}}" rel="stylesheet" type="text/css" />
</head>
<!--end::Head-->
<!--begin::Body-->
<style>
	body,html,.row,.container-fluid {
		height: 100%
	}

</style>

<body id="kt_body" >
   <div class="container-fluid border pb-0 ">
       <div class="row border pb-0">
          <div class="col-md-12 bg-img ">
             <div class="d-flex mt-2 ">
                   <div class="col-6">
                       <img src="/assets/img/login/gop.png" alt="">
                  </div>
                   <div class="col-6 text-right">
                       <img src="/assets/img/login/nitb.png" alt="">
                 </div>
              </div>
             <div class="container ">
                 <div class="row for-set " >
                     <div class="col-lg-6 col-md-6 d-none d-lg-block border-right pt-5">
                     <!-- <img src="/assets/img/login/Ministry-Logo.png" alt="" style="width: 320px; height: auto;"> -->
                     </div>
                     <div class="col-lg-6 col-md-12 col-12 ">
                        @yield('content')
                     </div>
                 </div>


			       <div class="col-12 bottom-text mb-0 mt-4">

                   <p>
                    <!-- Copyright © <?php echo date("Y"); ?> - <span>Powered by </span>   </p> -->

                <!-- <a href="https://nitb.gov.pk/" target="_blank">National Information Technology Board</a> -->
             </div>
          </div>
	  </div>
</div>
   </div>



     <!-- start js include path -->
      <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
      <script src="{{asset('assets/js/app.js')}}"></script>
      <script src="{{asset('assets/js/layout.js')}}"></script>
      <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
      <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>


   </body>
<!--end::Body-->

</html>
