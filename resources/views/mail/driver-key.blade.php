<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Driver Registration Key</title>
<style>
  body { margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7fb; }
  .wrapper { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.10); }
  .header { background: linear-gradient(135deg, #0a1628 0%, #0d2040 100%); padding: 36px 40px 28px; text-align: center; }
  .header .logo-text { font-size: 1.6rem; font-weight: 800; color: #00b894; letter-spacing: 2px; }
  .header .subtitle { color: rgba(255,255,255,0.6); font-size: 0.82rem; margin-top: 4px; letter-spacing: 1px; }
  .body { padding: 36px 40px; }
  .greeting { font-size: 1.05rem; color: #0a1628; font-weight: 600; margin-bottom: 12px; }
  .intro { font-size: 0.9rem; color: #4a5568; line-height: 1.6; margin-bottom: 28px; }
  .key-box { background: #e6f9f5; border: 2px solid #00b894; border-radius: 14px; padding: 24px; text-align: center; margin-bottom: 28px; }
  .key-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #007a63; margin-bottom: 10px; }
  .key-code { font-family: 'Courier New', monospace; font-size: 2.6rem; font-weight: 900; letter-spacing: 0.5em; color: #0a1628; }
  .steps { background: #f8fafc; border-radius: 10px; padding: 18px 22px; margin-bottom: 28px; }
  .steps-title { font-size: 0.8rem; font-weight: 700; color: #0a1628; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.08em; }
  .step { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 8px; font-size: 0.86rem; color: #4a5568; }
  .step-num { background: #00b894; color: #fff; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; flex-shrink: 0; }
  .warning { background: #fff9e6; border: 1.5px solid #f39c12; border-radius: 10px; padding: 14px 18px; font-size: 0.82rem; color: #7f6000; margin-bottom: 28px; }
  .footer { background: #f4f7fb; padding: 20px 40px; text-align: center; font-size: 0.76rem; color: #a0aec0; border-top: 1px solid #e2e8f0; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="logo-text">SSTS</div>
    <div class="subtitle">School Transportation Tracking System</div>
  </div>
  <div class="body">
    <p class="greeting">Hello, {{ $requesterName }}!</p>
    <p class="intro">
      Your request for a Driver Registration Key has been approved by our manager.
      Use the key below to complete your driver registration on the SSTS platform.
    </p>

    <div class="key-box">
      <div class="key-label">Your One-Time Registration Key</div>
      <div class="key-code">{{ $keyCode }}</div>
    </div>

    <div class="steps">
      <div class="steps-title">How to use this key</div>
      <div class="step">
        <div class="step-num">1</div>
        <span>Go to the SSTS website and click <strong>Register</strong>.</span>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <span>Select <strong>Driver</strong> from the dropdown.</span>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <span>Enter the 6-digit key above in the popup and click <strong>Verify Key</strong>.</span>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <span>Complete the driver registration form.</span>
      </div>
    </div>

    <div class="warning">
      <strong>Important:</strong> This key is <strong>single-use only</strong>. Once used for registration it cannot be reused. Do not share this key with anyone else.
    </div>

    <p style="font-size:0.86rem; color:#4a5568;">
      If you did not request this key, please ignore this email.
    </p>
  </div>
  <div class="footer">
    &copy; {{ date('Y') }} SSTS &mdash; School Transportation Tracking System<br>
    This is an automated message, please do not reply.
  </div>
</div>
</body>
</html>
