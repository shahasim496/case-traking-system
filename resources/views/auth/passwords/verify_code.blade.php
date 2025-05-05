<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>Evidence Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full overflow-hidden bg-black bg-cover" style="background-image: url('/assets/img/login/main_login.png');">

  <!-- Logo Watermark Overlay -->
  <div class="absolute inset-0 flex items-center justify-start z-0">
    <img src="/assets/img/login/logo1.png" alt="MOHA Logo" class="w-3/6 opacity-80" />
  </div>

  <!-- Verification Code Card -->
  <div class="absolute inset-0 flex items-center justify-end p-10 z-10">
    <div class="bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-lg shadow-lg w-full max-w-sm">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Verification Code</h2>
      <p class="text-center text-gray-600 mb-6">Enter the 4-digit code sent to your email.</p>

      <form class="form-signin" method="POST" action="{{ route('verifyUserCode') }}">
        @csrf

        <div class="flex justify-between mb-6">
          <input type="text" id="code1" name="code1" maxlength="1" required
            class="w-12 h-12 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
          <input type="text" id="code2" name="code2" maxlength="1" required
            class="w-12 h-12 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
          <input type="text" id="code3" name="code3" maxlength="1" required
            class="w-12 h-12 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
          <input type="text" id="code4" name="code4" maxlength="1" required
            class="w-12 h-12 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition duration-200">
          Verify
        </button>
      </form>

      <div class="text-center mt-4 text-gray-600">
        <p>Didn't receive the code? <a href="{{ route('resendCode') }}" class="text-blue-600 hover:underline">Send Again</a></p>
      </div>
    </div>
  </div>
</body>
</html>
