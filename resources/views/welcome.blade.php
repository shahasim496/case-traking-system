<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>Case Tracking System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full overflow-hidden bg-black bg-cover " style="background-image: url('/assets/img/login/1.jpg');">






 

  <!-- Login Card -->
  <div class="absolute inset-0 flex items-center justify-end p-10 z-10">
    <div class="bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-lg shadow-lg w-full max-w-sm">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Sign In</h2>
      <form class="form-signin" method="POST" action="{{ route('login') }}">
        @csrf

        @if (session('error'))
        <div id="errorMessage" class="mb-4 p-4 text-red-800 bg-red-100 border border-red-300 rounded relative">
          {{ session('error') }}
          <button type="button" onclick="document.getElementById('errorMessage').style.display='none'" 
            class="absolute top-0 right-0 mt-2 mr-2 text-red-800 hover:text-red-600">
            &times;
          </button>
        </div>
        @endif

        @if (session('success'))
        <div id="successMessage" class="mb-4 p-4 text-green-800 bg-green-100 border border-green-300 rounded relative">
          {{ session('success') }}
          <button type="button" onclick="document.getElementById('successMessage').style.display='none'" 
            class="absolute top-0 right-0 mt-2 mr-2 text-green-800 hover:text-green-600">
            &times;
          </button>
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
            <button type="button" onclick="togglePassword()" class="absolute right-2 top-1/2 transform -translate-y-1/2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

        <script>
          function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
              passwordInput.type = 'text';
            } else {
              passwordInput.type = 'password';
            }
          }
        </script>

        <div class="flex justify-between items-center mb-4">
          
          <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition duration-200">Sign In</button>
      </form>
    </div>
  </div>
</body>
</html>
