<!-- filepath: resources/views/emails/user_updated.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Your Account Details Have Been Updated</title>
</head>
<body>
    <h1>Hello, {{ $name }}!</h1>
    <p>Your account details have been updated successfully. Below are your updated details:</p>
    <ul>
        <li><strong>Name:</strong> {{ $name }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
        <li><strong>Phone:</strong> {{ $phone }}</li>
        <li><strong>CNIC:</strong> {{ $cnic }}</li>
    </ul>
    <p>If you did not request these changes, please contact support immediately.</p>
    <p>Thank you!</p>
</body>
</html>