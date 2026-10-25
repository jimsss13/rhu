<!DOCTYPE html>
<html>
<head>
    <title>Appointment Approved</title>
</head>
<body>
    <h1>Dear {{ $appointment->name }},</h1>
    <p>Your appointment scheduled on <strong>{{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y') }}</strong> has been approved.</p>
    <p>Thank you for choosing our service.</p>
    {{-- <p>Best regards,<br>{{ config('app.name') }}</p> --}}
</body>
</html>
