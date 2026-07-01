<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password – SSTS</title>
  @vite(['resources/sass/app.scss', 'resources/sass/icons.scss', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --navy:       #0a1628;
      --slate:      #4a5568;
      --white:      #ffffff;
      --bg:         #f5f7fa;
      --border:     rgba(0,184,148,0.25);
      --input-bg:   #f0f4f8;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      overflow: hidden; position: relative;
    }
    body::before {
      content: '';
      position: fixed; inset: 0;
      background: repeating-linear-gradient(105deg, transparent 0px, transparent 38px, rgba(0,184,148,0.045) 38px, rgba(0,184,148,0.045) 40px);
      animation: roadLines 6s linear infinite;
      pointer-events: none;
    }
    @keyframes roadLines {
      from { background-position: 0 0; }
      to   { background-position: 200px 0; }
    }
    .glow-tr {
      position: fixed; width: 600px; height: 600px; top: -150px; right: -150px;
      background: radial-gradient(circle, rgba(0,184,148,0.13) 0%, transparent 70%);
      pointer-events: none; z-index: 0;
    }
    .glow-bl {
      position: fixed; width: 400px; height: 400px; bottom: -120px; left: -100px;
      background: radial-gradient(circle, rgba(0,184,148,0.09) 0%, transparent 70%);
      pointer-events: none; z-index: 0;
    }
    .wrap {
      position: relative; z-index: 10;
      width: 100%; max-width: 460px; padding: 1rem;
      animation: fadeUp 0.65s ease both;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .card {
      background: var(--white);
      border: 1.5px solid var(--border);
      border-radius: 20px;
      padding: 2.8rem 2.5rem;
      box-shadow: 0 8px 32px rgba(0,184,148,0.08);
    }
    .brand-top { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 2rem; }
    .brand-top img { height: 36px; }
    .brand-top .name { font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 800; color: var(--navy); letter-spacing: 0.04em; }
    .brand-top .name span { color: var(--emerald); }
    h2 { font-family: 'Syne', sans-serif; font-size: 1.75rem; font-weight: 800; color: var(--navy); margin-bottom: 0.4rem; }
    .sub { font-size: 0.88rem; color: var(--slate); margin-bottom: 2rem; }
    label { font-size: 0.8rem; font-weight: 600; color: var(--slate); letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 0.45rem; display: block; }
    .input-wrap { position: relative; margin-bottom: 1.25rem; }
    .input-wrap i.icon-left { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #a0aab4; font-size: 1rem; pointer-events: none; }
    .input-wrap input {
      width: 100%; background: var(--input-bg);
      border: 1.5px solid #dde3ea; border-radius: 10px;
      padding: 0.75rem 1rem 0.75rem 2.75rem;
      color: var(--navy); font-family: 'DM Sans', sans-serif; font-size: 0.95rem;
      transition: border-color 0.2s; outline: none;
    }
    .input-wrap input::placeholder { color: #a0aab4; }
    .input-wrap input:focus { border-color: var(--emerald); background: rgba(0,184,148,0.04); }
    .toggle-pw {
      position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
      color: #a0aab4; cursor: pointer; font-size: 1rem; transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--emerald); }
    .btn-submit {
      width: 100%; background: var(--emerald); color: var(--white);
      font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700;
      border: none; border-radius: 10px; padding: 0.85rem;
      cursor: pointer; transition: background 0.2s, transform 0.15s;
      box-shadow: 0 4px 20px rgba(0,184,148,0.3); letter-spacing: 0.03em;
      margin-top: 0.5rem;
    }
    .btn-submit:hover { background: var(--emerald-dk); transform: translateY(-2px); }
    .err-text { font-size: 0.8rem; color: #e74c3c; margin-top: 0.3rem; }
    .alert-error-ssts {
      background: rgba(255,118,118,0.08); border: 1px solid rgba(255,118,118,0.28);
      border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.88rem; color: #c0392b;
      margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.6rem;
    }
  </style>
</head>
<body>
<div class="glow-tr"></div>
<div class="glow-bl"></div>

<div class="wrap">
  <div class="card">

    <div class="brand-top">
      <img src="{{ asset('assets/img/photo_2024-10-22_11-35-22-Photoroom.png') }}" alt="SSTS Logo">
      <span class="name">SS<span>TS</span></span>
    </div>

    <h2>Set New Password</h2>
    <p class="sub">Enter your email and choose a new password.</p>

    @if($errors->any())
      <div class="alert-error-ssts">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>@foreach($errors->all() as $error){{ $error }}{{ !$loop->last ? ' · ' : '' }}@endforeach</div>
      </div>
    @endif

    <form action="{{ route('reset-password-post') }}" method="POST" novalidate>
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div>
        <label for="email">Email Address</label>
        <div class="input-wrap">
          <i class="bi bi-envelope icon-left"></i>
          <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}">
        </div>
        @error('email')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label for="password">New Password</label>
        <div class="input-wrap">
          <i class="bi bi-lock icon-left"></i>
          <input type="password" id="password" name="password" placeholder="At least 6 characters">
          <i class="bi bi-eye toggle-pw" id="togglePw1"></i>
        </div>
        @error('password')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label for="password_confirmation">Confirm Password</label>
        <div class="input-wrap">
          <i class="bi bi-lock icon-left"></i>
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat password">
          <i class="bi bi-eye toggle-pw" id="togglePw2"></i>
        </div>
      </div>

      <button type="submit" class="btn-submit">Reset Password</button>
    </form>

  </div>
</div>

<script>
  function toggleField(toggleId, inputId) {
    const t = document.getElementById(toggleId);
    const i = document.getElementById(inputId);
    t.addEventListener('click', function() {
      const show = i.type === 'text';
      i.type = show ? 'password' : 'text';
      t.className = show ? 'bi bi-eye toggle-pw' : 'bi bi-eye-slash toggle-pw';
    });
  }
  toggleField('togglePw1', 'password');
  toggleField('togglePw2', 'password_confirmation');
</script>
</body>
</html>
