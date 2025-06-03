<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fa; margin: 0; padding: 0;">
    <table width="100%" bgcolor="#f5f7fa" cellpadding="0" cellspacing="0" style="padding: 20px 0;">
        <tr>
            <td>
                <table width="600" bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td style="background-color: #4f46e5; padding: 20px; text-align: center; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px;">🔐 Email Verification</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333;">Hello,</p>
                            <p style="font-size: 16px; color: #333;">
                                Thank you for registering. Please use the code below to verify your email address:
                            </p>

                            <div style="margin: 30px 0; text-align: center;">
                                <span style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4f46e5;">
                                    {{ $code }}
                                </span>
                            </div>

                            <p style="font-size: 14px; color: #666;">
                                This code will expire in 10 minutes. If you did not request this, please ignore this email.
                            </p>

                            <p style="font-size: 14px; color: #999; margin-top: 40px;">
                                — YourApp Team
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f1f1f1; text-align: center; padding: 15px; font-size: 12px; color: #999;">
                            &copy; {{ date('Y') }} YourApp. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
