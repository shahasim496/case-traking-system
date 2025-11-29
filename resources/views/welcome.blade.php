<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>Case Tracking System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="h-full overflow-hidden bg-black bg-cover " style="background-image: url('/assets/img/login/1.jpg');">

  <!-- Logo Watermark Overlay -->
  <div class="absolute inset-0 flex items-center justify-start z-0">

  </div>

  <!-- Login Card -->
  <div class="absolute inset-0 flex items-center  p-10 z-10">
    <div class="bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-lg shadow-lg w-full max-w-sm">
      <div class="flex justify-center mb-6">
        <img src="/assets/img/login/Ministry-Logo.png" alt="Case Tracking System Logo" class="h-24">
      </div>
      <form class="form-signin" method="POST" action="{{ route('login') }}">
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

        <div class="mb-4">
          <label for="password" class="block text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input id="password" type="password" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') is-invalid @enderror" 
            name="password" placeholder="Enter your password" required>
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          @error('password')
          <span class="invalid-feedback text-red-500 text-sm" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <div class="flex justify-between items-center mb-4">
          <label class="flex items-center text-sm text-gray-600">
            <input type="checkbox" class="mr-2" name="remember"> Keep me logged in
          </label>
          <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition duration-200">Sign In</button>
      </form>
      
  
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
    }
  </script>
</body>
</html>
