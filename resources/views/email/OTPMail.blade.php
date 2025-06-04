<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        h2 {
            color: #333333;
            margin-top: 0;
        }

        p {
            color: #555555;
            line-height: 1.5;
            margin: 15px 0;
        }

        .otp {
            display: inline-block;
            background-color: #f1f1f1;
            color: #333333;
            font-size: 20px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 4px;
            letter-spacing: 3px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Verify Your Account</h2>
        <p>Your OTP code is:</p>
        <div class="otp">{{ $otp }}</div>
        <p>Please use this code to complete your verification process.</p>
        <p>If you didn’t request this, please ignore this email.</p>
        <div class="footer">
            &copy; {{ date('Y') }} Your Company
        </div>
    </div>
</body>

</html>
