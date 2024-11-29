<!DOCTYPE html>
<html>
<head>
    <title>{{ $loginData['title'] }}</title>
</head>
<body>
    <h1>{{ $loginData['title'] }}</h1>
    <p>{{ $loginData['body'] }}</p>
    <ul>
        <li><strong>Email:</strong> {{ $loginData['email'] }}</li>
        <li><strong>Password:</strong> {{ $loginData['password'] }}</li>
    </ul>
    <p>If you did not request this change, please contact support immediately.</p>
    <p>Thank you,</p>
    <p>The Team</p>
</body>
</html>
