<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Alert</title>
</head>
<body>
    <h1>Device Alert Notification</h1>
    <p>Hello,</p>
    
    <p>We detected an alert for your device. Below are the details:</p>

    <ul>
        <li><strong>Device ID:</strong> {{ $alertDevice['deviceId'] }}</li>
        <li><strong>Device Name:</strong> {{ $alertDevice['latitude'] }}</li>
        <li><strong>Alert Type:</strong> {{ $alertDevice['longitude'] }}</li>
        <li><strong>Timestamp:</strong> {{ $alertDevice['location'] }}</li>

        <li><strong>Timestamp:</strong> {{ $alertDevice['captures'] }}</li>
        <li><strong>Timestamp:</strong> {{ $alertDevice['message'] }}</li>
        <li><strong>Timestamp:</strong> {{ $alertDevice['alert_type'] }}</li>
    </ul>

    <p>If you need further assistance, feel free to contact us.</p>

    <p>Best regards,</p>
    <p>The Team</p>
</body>
</html>
