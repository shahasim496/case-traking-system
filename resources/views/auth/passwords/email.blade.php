<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>MOHA Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full overflow-hidden bg-black bg-cover " style="background-image: url('/assets/img/login/main_login.png');">

  <!-- Logo Watermark Overlay -->
  <div class="absolute inset-0 flex items-center justify-start z-0">
    <img src="/assets/img/login/logo1.png" alt="MOHA Logo" class="w-3/6 opacity-80" />
  </div>

  <!-- Login Card -->
  <div class="absolute inset-0 flex items-center justify-end p-10 z-10">
    <div class="bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-lg shadow-lg w-full max-w-sm">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Forgot Password</h2>
  
      <form class="form-signin" method="POST" action="{{ route('sendcode') }}">
                @csrf

        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <span>{{ $message }}</span>
        </div>
        @endif

        <div class="mb-4">
          <label for="email" class="block text-gray-700 mb-1">Email</label>
          <input id="email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') is-invalid @enderror" 
          name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
          @error('email')
          <span class="invalid-feedback text-red-500 text-sm" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
 
        


        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition duration-200">Send Reset Link</button>
      </form>
    </div>
  </div>
</body>
</html>
