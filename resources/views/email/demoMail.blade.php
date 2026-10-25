<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Approved</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .email-container {
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Header Styles */
        .email-header {
            background-color: #4285F4; /* Google Blue */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .email-header h1 {
            font-size: 24px;
            margin: 0;
        }

        /* Body Styles */
        .email-body {
            padding: 20px;
            line-height: 1.6;
            color: #333333;
        }
        .email-body h5 {
            color: #555555;
            font-size: 18px;
            margin: 0;
        }
        .email-body p {
            margin: 15px 0;
        }

        /* Footer Styles */
        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888888;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .email-container {
                padding: 10px;
            }
            .email-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="email-header">
            <h1>Appointment Approved</h1>
        </div>

        <!-- Body Section -->
        <div class="email-body">
            <h5>Dear, {{ $mailData['client'] }}</h5>
            <p>{{ $mailData['body'] }}</p>
        </div>

        <!-- Footer Section -->
        <div class="email-footer">
            <p>This email was sent from our system. If you have any questions, feel free to contact us.</p>
        </div>
    </div>
</body>
</html>
