<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
</head>
<body>
<div>
    <p>Dear user,</p>
    <p>This is your OTP code: {{ $otp }}</p>
    <p>Please use this OTP code to verify your account or perform the required action.</p>
    <p>If you did not request this OTP, please ignore this email.</p>
</div>
</body>
</html>
