<!-- filepath: resources/views/emails/user_created.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to the System</title>
</head>
<body>
    <h1>Welcome, {{ $name }}!</h1>
    <p>Your account has been created successfully. Below are your login details:</p>
    <ul>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    <p>Please log in to the system and change your password for security purposes.</p>
    <p>Thank you!</p>
</body>
</html>