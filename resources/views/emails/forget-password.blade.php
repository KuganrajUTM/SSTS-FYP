<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reset Your Password – SSTS</title>
  <style>
    body { font-family: 'Arial', sans-serif; background: #f5f7fa; margin: 0; padding: 0; }
    .container { max-width: 560px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: #0a1628; padding: 28px 32px; text-align: center; }
    .header h1 { margin: 0; font-size: 1.5rem; font-weight: 800; color: #ffffff; letter-spacing: 0.04em; }
    .header h1 span { color: #00b894; }
    .body { padding: 32px; color: #333; line-height: 1.65; }
    .body p { margin: 0 0 1rem; font-size: 0.95rem; }
    .btn-wrap { text-align: center; margin: 28px 0; }
    .btn { display: inline-block; background: #00b894; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-size: 1rem; font-weight: 700; letter-spacing: 0.03em; }
    .note { font-size: 0.82rem; color: #888; margin-top: 1rem; }
    .footer { background: #f0f4f8; padding: 16px 32px; text-align: center; font-size: 0.8rem; color: #999; border-top: 1px solid #e8ecf0; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>SS<span>TS</span> &mdash; EduTransit</h1>
    </div>
    <div class="body">
      <p>Hi <strong>{{ $name }}</strong>,</p>
      <p>We received a request to reset the password for your SSTS account. Click the button below to choose a new password:</p>
      <div class="btn-wrap">
        <a href="{{ route('reset-password', $token) }}" class="btn">Reset My Password</a>
      </div>
      <p>If you did not request a password reset, you can safely ignore this email — your password will remain unchanged.</p>
      <p class="note">This link will expire in 60 minutes. If the button doesn't work, copy and paste this URL into your browser:<br>
        <span style="color:#00b894; word-break:break-all;">{{ route('reset-password', $token) }}</span>
      </p>
    </div>
    <div class="footer">
      &copy; {{ date('Y') }} SSTS EduTransit. All rights reserved.
    </div>
  </div>
</body>
</html>
