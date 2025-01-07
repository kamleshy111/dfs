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

    <table>
        <thead>
            <tr>
                <th>Device ID</th>
                <th>Device Name</th>
                <th>Alert Type</th>
                <th>Timestamp</th>
                <th>Capture</th>
                <th>Message</th>
                <th>Alert Type</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $alertDevices->deviceId }}</td>
            <td>{{ $alertDevices->latitude }}</td>
            <td>{{ $alertDevices->longitude }}</td>
            <td>{{ $alertDevices->location }}</td>
            <td>{{ $alertDevices->captures }}</td>
            <td>{{ $alertDevices->message }}</td>
            <td>{{ $alertDevices->alert_type }}</td>
        </tr>
        </tbody>
    </table>


    <p>If you need further assistance, feel free to contact us.</p>

    <p>Best regards,</p>
    <p>The Team</p>
</body>
</html>
