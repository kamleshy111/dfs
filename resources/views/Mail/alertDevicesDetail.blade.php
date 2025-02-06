<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Alert</title>
    <style>
        body, h1, p, table, td, th {
            margin: 0;
            padding: 0;
            font: .875rem / 1.38 Inter, system-ui;
        }
        body {
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        .email-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            margin: 0 auto;
        }

        h1 {
            font-size: 24px;
            color: #1a2e44c7;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 700;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
            color: #1a2e44c7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #1a2e44a3;
            color: #fff;
            font-weight: 700;
        }

        td {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            color: #1a2e44c7;
        }

        input[type="button"] {
            background-color:#1a2e44e0;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        input[type="button"]:hover {
            background-color:#1a2e44a3;
        }

        .footer {
            margin-top: 10px;
            font-size: 14px;
            color: #1a2e44c7;
            padding: 0 1px;
        }

        .footer p {
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Device Alert Notification</h1>
        <p>Hello,</p>
        <p>We detected an alert for your device. Below are the details:</p>
        <table>
            <thead>
                <tr>
                    <th>Device ID</th>
                    <th>Location</th>
                    <th>Capture</th>
                    <th>Message</th>
                    <th>Alert Type</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $alertDevices->device_id }}</td>
                    <td>{{ $alertDevices->location }}</td>
                    <td>{{ $alertDevices->captures }}</td>
                    <td>{{ $alertDevices->message }}</td>
                    <td>{{ $alertDevices->alert_type }}</td>
                </tr>
            </tbody>
        </table>
        <p>If you need further assistance, feel free to contact us.</p>
        <p class="footer">Best regards,</p>
        <p class="footer">The Team</p>
    </div>
</body>
</html>
