<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<base href="">
	<meta charset="utf-8" />
	<title>Evidence Management System</title>
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
<body id="kt_body">

<div class="container-fluid">
<div class="row">

	<div class="col-md-12 bg-img">
		 <div class="container flex">
		 	<div class="footer_logo my-1 mb-4">
					<img src="{{asset('assets/img/ic_nitblogo.png')}}">
				</div>
        <div class="sign-in">

            <div class="out-div">
            <h1 class="heading">Login</h1>
            </div>
           <form class="form-signin" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="single-input">
                <label class="label-font">Email</label>
                <input id="email" type="email" class="form-control mb-3 @error('email') is-invalid @enderror"
                placeholder="Email address" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                </div>
                <div class="single-input">
                <label class="label-font">Password</label>
                <input id="password" type="password" class="form-control mb-3 @error('password') is-invalid @enderror" placeholder="Password"
                name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>
                <div id="Forget-pass" class="checkbox pull-right mt-n2">
                    <a href="{{ route('password.request') }}" class="forgot-password">
               Forgot Password?

            </a>
                </div>
                <div class="mt-3 spacebox"></div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-sm btn-success" type="submit">Login</button>
                    </div>
                    <div class="col-lg-12 text-center mt-3 reg-now">

                    </div>
                </div>
            </form>


        </div>
        <!-- /card-container -->
			 <div class="bottom-text">
			 	<span class="border-footer"></span>
               <p>
               	Powered by <?php echo date("Y"); ?> &copy;
               	<a href="https://nitb.gov.pk/" target="_blank">National Information Technology Board</a>
               </p>
               	<span class="border-footer"></span>
            </div>


    </div><!-- /container -->
	</div>
</div>

</div>

</body>
<!--end::Body-->

</html>
