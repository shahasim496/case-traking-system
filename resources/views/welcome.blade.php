

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta content="width=device-width, initial-scale=1" name="viewport" />
      <meta name="description" content="Responsive Admin Template" />
      <meta name="author" content="SmartUniversity" />
      <title>Case Management System</title>
      <link href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
      <!--bootstrap -->
      <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset('assets/css/pages/animate_page.css')}}" rel="stylesheet">
      <!-- Template Styles -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
      <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset('assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset('assets/css/theme-color.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset('assets/css/custom-style.css')}}" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="{{asset('assets/css/jquery.dropdown.css')}}">
      <!-- favicon -->
      <link rel="shortcut icon" href="assets/img/favicon.ico" />
   </head>
   <style>
	body,html,.row,.container-fluid {
		height: 100%
	}
</style>
<body id="kt_body" >
   <div class="container-fluid  ">
       <div class="row">
          <div class="col-md-12 bg-img ">
             <div class="d-flex mt-2 ">
                   <div class="col-6">
                       <!-- <img src="/assets/img/login/gop.png" alt=""> -->
                  </div>
                   <div class="col-6 text-right">
                       <!-- <img src="/assets/img/login/nitb.png" alt=""> -->
                 </div>
              </div>
             <div class="container ">
                 <div class="row for-set " >
                     <div class="col-lg-6 col-md-6 d-none d-lg-block border-right pt-5">
                     <img src="/assets/img/login/Ministry-Logo.png" alt="" style="width: 320px; height: auto;">
                     </div>
                     <div class="col-lg-6 col-md-12 col-12 ">

                        <div class="sign-in  ">
                            <div class="out-div">
                            <h1 class="" style="color: #00349C;">Login</h1>

                                </div>

            <form class="form-signin" method="POST" action="{{ route('login') }}">
                @csrf

                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                        <span>{{ $message }}</span>
                </div>
                @endif

                <div class="single-input">
                    <label class="label-font">Email</label>
                    <input id="email" type="email" class="form-control mb-3 @error('email') is-invalid @enderror"
                    placeholder="Email address"name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                    @if(Session::has('error'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ Session::get('error') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="single-input">
                    <label class="label-font">Password</label>
                    <input id="password" type="password" class="form-control mb-3 @error('password') is-invalid @enderror"
                    placeholder="Password" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div id="Remember-me" class="checkbox pull-left mt-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" >
                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                    </div>
            </div>

                 <div id="Forget-pass" class="checkbox pull-right mt-2">
                    <a href="{{ route('password.request') }}" class="forgot-password">
               Forgot Password?

            </a>
                </div>
                <div class="mt-4 spacebox"></div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <button   class="btn btn-sm btn-secondary" type="submit">Login</button>
                        </div>
                        <div class="col-lg-12 text-center mt-4 reg-now">
                            <!-- <p>Don't have an account? <a href="#">Register now!</a></p> -->
                        </div>
                    </div>

            </form>



                    </div>
                     </div>
                 </div>


			       <!-- <div class="bottom-text  ">

                   <p>
                    Copyright © <?php echo date("Y"); ?> - <span>Powered by </span>   </p>

                <a href="https://nitb.gov.pk/" target="_blank">National Information Technology Board</a>
             </div> -->
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
</html>
