<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Renewal Reminder</title>
</head>
<body>

    <h1>Vehicle Renewal Reminder</h1>

    <p>Dear Admin,</p>
    
    <p>The following vehicles have expired and require renewal:</p>

    <table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Vehicle Register Number</th>
                <th>Device ID</th>
                <th>Installation Date</th>
                <th>Start Date</th>
                <th>Expiration Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle['customerName'] }}</td>
                    <td>{{ $vehicle['email'] }}</td>
                    <td>{{ $vehicle['phone'] }}</td>
                    <td>{{ $vehicle['vehicleRegisterNumber'] }}</td>
                    <td>{{ $vehicle['deviceId'] }}</td>
                    <td>{{ $vehicle['installationDate'] }}</td>
                    <td>{{ $vehicle['startDate'] }}</td>
                    <td>{{ $vehicle['expirationDate'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Please take the necessary action to renew the subscriptions.</p>

    <p>Thank you.</p>

</body>
</html>
