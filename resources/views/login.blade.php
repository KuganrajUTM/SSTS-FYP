<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – SSTS</title>
  @vite(['resources/sass/app.scss', 'resources/sass/icons.scss', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;      
      --navy-mid:   #132035;      
      --slate:      #4a5568;      
      --white:      #ffffff;
      --bg:         #f5f7fa;     
      --card-bg:    #ffffff;    
      --input-bg:   #f0f4f8;      
      --border:     rgba(0,184,148,0.25);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
    }

    /* ── diagonal road lines ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: repeating-linear-gradient(
        105deg,
        transparent 0px, transparent 38px,
        rgba(0,184,148,0.045) 38px, rgba(0,184,148,0.045) 40px
      );
      animation: roadLines 6s linear infinite;
      pointer-events: none;
    }

    @keyframes roadLines {
      from { background-position: 0 0; }
      to   { background-position: 200px 0; }
    }

    /* glows */
    .glow-tr {
      position: fixed; width: 600px; height: 600px;
      top: -150px; right: -150px;
      background: radial-gradient(circle, rgba(0,184,148,0.13) 0%, transparent 70%);
      pointer-events: none; z-index: 0;
    }
    .glow-bl {
      position: fixed; width: 400px; height: 400px;
      bottom: -120px; left: -100px;
      background: radial-gradient(circle, rgba(0,184,148,0.09) 0%, transparent 70%);
      pointer-events: none; z-index: 0;
    }

    /* ── ROAD STRIP ── */
    .road-strip {
      position: fixed;
      bottom: 72px; left: 0; right: 0;
      height: 52px;
      z-index: 1;
      pointer-events: none;
    }

    .road-surface {
      position: absolute; inset: 0;
      background: rgba(0,184,148,0.06);
      border-top: 2px solid rgba(0,184,148,0.22);
      border-bottom: 2px solid rgba(0,184,148,0.22);
    }

    .road-dash {
      position: absolute;
      top: 50%; left: 0;
      width: 200%; height: 2px;
      transform: translateY(-50%);
      background: repeating-linear-gradient(
        90deg,
        rgba(0,184,148,0.45) 0px, rgba(0,184,148,0.45) 40px,
        transparent 40px, transparent 70px
      );
      animation: dashMove 1.1s linear infinite;
    }

    @keyframes dashMove {
      from { transform: translateY(-50%) translateX(0); }
      to   { transform: translateY(-50%) translateX(-70px); }
    }

    /* ground shadow under bus */
    .road-strip::after {
      content: '';
      position: absolute;
      bottom: -6px; left: 0; right: 0;
      height: 6px;
      background: rgba(0,0,0,0.25);
      filter: blur(4px);
    }

    /* ── BUS ── */
    .bus-container {
      position: fixed;
      bottom: 80px;
      left: -220px;
      z-index: 2;
      pointer-events: none;
      animation: busDrive 13s linear infinite;
    }

    @keyframes busDrive {
      0%   { left: -220px; }
      100% { left: calc(100vw + 30px); }
    }

    .bus-svg-wrap {
      animation: busRock 0.4s ease-in-out infinite alternate;
    }

    @keyframes busRock {
      from { transform: translateY(0px); }
      to   { transform: translateY(-2.5px); }
    }

    /* wheel spin */
    .wheel {
      animation: spin 0.45s linear infinite;
      transform-box: fill-box;
      transform-origin: center;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to   { transform: rotate(360deg); }
    }

    /* exhaust puffs */
    .puff {
      position: absolute;
      left: -14px;
      bottom: 18px;
    }

    .puff span {
      display: block;
      width: 9px; height: 9px;
      border-radius: 50%;
      background: rgba(0,184,148,0.3);
      margin-bottom: 3px;
      animation: puffUp 0.9s ease-out infinite;
    }

    .puff span:nth-child(2) { animation-delay: 0.3s; width: 6px; height: 6px; opacity: 0.6; }
    .puff span:nth-child(3) { animation-delay: 0.6s; width: 4px; height: 4px; opacity: 0.4; }

    @keyframes puffUp {
      0%   { opacity: 0.6; transform: translateY(0) scale(1); }
      100% { opacity: 0;   transform: translateY(-20px) scale(1.8); }
    }

    /* ── LOGIN CARD ── */
    .login-wrap {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 460px;
      padding: 1rem;
      animation: fadeUp 0.65s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .login-card {
      background: var(--card-bg);
      border: 1.5px solid var(--border);
      border-radius: 20px;
      padding: 2.8rem 2.5rem;
    }

    .brand-top {
      display: flex; align-items: center; gap: 0.65rem;
      margin-bottom: 2rem;
    }

    .brand-top img { height: 36px; width: auto; }

    .brand-top .name {
      font-family: 'Syne', sans-serif;
      font-size: 1.25rem; font-weight: 800;
      color: var(--white); letter-spacing: 0.04em;
    }

    .brand-top .name span { color: var(--emerald); }

    .login-card h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.85rem; font-weight: 800;
      color: var(--white); margin-bottom: 0.4rem;
    }

    .login-card .sub {
      font-size: 0.88rem;
      color: rgba(255,255,255,0.42);
      margin-bottom: 2rem;
    }

    label {
      font-size: 0.8rem; font-weight: 600;
      color: rgba(255,255,255,0.62);
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 0.45rem;
      display: block;
    }

    .input-wrap { position: relative; margin-bottom: 1.25rem; }

    .input-wrap i.icon-left {
      position: absolute; left: 1rem; top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,0.28); font-size: 1rem;
      pointer-events: none;
    }

    .input-wrap input {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid #dde3ea;
      border-radius: 10px;
      padding: 0.75rem 1rem 0.75rem 2.75rem;
      color: var(--white);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      transition: border-color 0.2s, background 0.2s;
      outline: none;
    }

    .input-wrap input::placeholder { color: rgba(255,255,255,0.2); }

    .input-wrap input:focus {
      border-color: var(--emerald);
      background: rgba(0,184,148,0.06);
    }

    .toggle-pw {
      position: absolute; right: 1rem; top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,0.28);
      cursor: pointer; font-size: 1rem;
      transition: color 0.2s;
    }

    .toggle-pw:hover { color: var(--emerald); }

    .err-text { font-size: 0.8rem; color: #ff7675; margin-top: 0.35rem; }

    .forgot-link {
      display: block; text-align: right;
      font-size: 0.82rem; color: var(--emerald);
      text-decoration: none;
      margin-top: -0.75rem; margin-bottom: 1.75rem;
      transition: color 0.2s;
    }

    .forgot-link:hover { color: #80ffd4; }

    .btn-submit {
      width: 100%;
      background: var(--emerald);
      color: var(--white);
      font-family: 'Syne', sans-serif;
      font-size: 1rem; font-weight: 700;
      border: none; border-radius: 10px;
      padding: 0.85rem;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(0,184,148,0.3);
      letter-spacing: 0.03em;
    }

    .btn-submit:hover {
      background: var(--emerald-dk);
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(0,184,148,0.4);
    }

    .divider {
      display: flex; align-items: center; gap: 0.75rem;
      margin: 1.5rem 0;
    }

    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px;
      background: rgba(255,255,255,0.1);
    }

    .divider span { font-size: 0.75rem; color: rgba(255,255,255,0.28); white-space: nowrap; }

    .signup-row {
      text-align: center;
      font-size: 0.88rem;
      color: rgba(255,255,255,0.42);
    }

    .signup-row a {
      color: var(--emerald); font-weight: 600;
      text-decoration: none; transition: color 0.2s;
    }

    .signup-row a:hover { color: #80ffd4; }

    .alert-ssts {
      background: rgba(255,118,118,0.1);
      border: 1px solid rgba(255,118,118,0.28);
      border-radius: 10px;
      padding: 0.85rem 1rem;
      font-size: 0.85rem; color: #ff9a9a;
      margin-bottom: 1.5rem;
      display: flex; align-items: flex-start; gap: 0.6rem;
    }

    .alert-ssts i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

      /* ── MOBILE RESPONSIVE ── */
  @media (max-width: 576px) {

    /* Card */
    .login-card { padding: 2rem 1.5rem; }
    .login-wrap { padding: 1rem 0.75rem; }

    /* Heading */
    .login-card h2 { font-size: 1.45rem; }
    .login-card .sub { font-size: 0.82rem; }

    /* Brand */
    .brand-top img { height: 30px; }
    .brand-top .name { font-size: 1.1rem; }

    /* Inputs */
    .input-wrap input { font-size: 0.88rem; padding: 0.7rem 1rem 0.7rem 2.5rem; }
    label { font-size: 0.75rem; }

    /* Button */
    .btn-submit { font-size: 0.92rem; padding: 0.75rem; }

    /* Bus */
    .bus-svg-wrap svg { width: 150px; height: 60px; }
    .bus-container { bottom: 76px; }
    .road-strip { bottom: 68px; height: 44px; }
  }

  @media (max-width: 360px) {
    .login-card { padding: 1.75rem 1.25rem; }
    .login-card h2 { font-size: 1.3rem; }
    .bus-svg-wrap svg { width: 120px; height: 48px; }
  }

  .input-wrap input { color: var(--navy); }
  .input-wrap input::placeholder { color: #a0aab4; }
  .input-wrap i.icon-left { color: #a0aab4; }
  .login-card h2 { color: var(--navy); }
  .login-card .sub { color: var(--slate); }
  label { color: var(--slate); }
  .toggle-pw { color: #a0aab4; }
  .brand-top .name { color: var(--navy); }
  .forgot-link { color: var(--emerald); }
  .signup-row { color: var(--slate); }

  </style>
</head>
<body>

<!-- Glows -->
<div class="glow-tr"></div>
<div class="glow-bl"></div>

<!-- Road -->
<div class="road-strip">
  <div class="road-surface"></div>
  <div class="road-dash"></div>
</div>

<!-- Animated School Bus -->
<div class="bus-container">
  <div class="puff">
    <span></span><span></span><span></span>
  </div>
  <div class="bus-svg-wrap">
    <svg xmlns="http://www.w3.org/2000/svg" width="210" height="82" viewBox="0 0 210 82">

      <!-- Bus body -->
      <rect x="12" y="6" width="178" height="54" rx="8" fill="#0d2318" stroke="#00b894" stroke-width="1.8"/>

      <!-- Roof bar -->
      <rect x="16" y="6" width="170" height="10" rx="5" fill="#00b894" opacity="0.15"/>

      <!-- Front cab -->
      <rect x="166" y="9" width="21" height="48" rx="5" fill="#091c12" stroke="#00b894" stroke-width="1.5"/>

      <!-- Windshield -->
      <rect x="169" y="13" width="15" height="22" rx="3" fill="#00b894" opacity="0.22"/>
      <!-- windshield glare -->
      <line x1="172" y1="14" x2="169" y2="34" stroke="#80ffd4" stroke-width="1" opacity="0.25"/>

      <!-- Headlight -->
      <rect x="169" y="40" width="15" height="9" rx="2" fill="#00b894" opacity="0.65"/>
      <rect x="171" y="41.5" width="11" height="6" rx="1" fill="#80ffd4" opacity="0.45"/>

      <!-- Windows -->
      <rect x="22"  y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="52"  y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="82"  y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="112" y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="142" y="13" width="20" height="19" rx="3" fill="#00b894" opacity="0.18"/>

      <!-- Window glares -->
      <line x1="25"  y1="15" x2="23"  y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="55"  y1="15" x2="53"  y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="85"  y1="15" x2="83"  y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="115" y1="15" x2="113" y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="145" y1="15" x2="143" y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>

      <!-- Door -->
      <rect x="22" y="37" width="18" height="21" rx="2" fill="#081510" stroke="#00b894" stroke-width="1"/>
      <line x1="31" y1="39" x2="31" y2="57" stroke="#00b894" stroke-width="0.8" opacity="0.5"/>
      <!-- door handle -->
      <rect x="28" y="47" width="5" height="2" rx="1" fill="#00b894" opacity="0.5"/>

      <!-- Body stripe -->
      <rect x="12" y="37" width="178" height="3" rx="1" fill="#00b894" opacity="0.3"/>

      <!-- SSTS text -->
      <text x="98" y="55"
            text-anchor="middle"
            font-family="'Syne', sans-serif"
            font-size="8.5"
            font-weight="800"
            fill="#00b894"
            opacity="0.8"
            letter-spacing="2.5">SSTS</text>

      <!-- rear bump -->
      <rect x="9" y="52" width="5" height="7" rx="1" fill="#00b894" opacity="0.4"/>

      <!-- Wheels -->
      <g class="wheel">
        <circle cx="48"  cy="65" r="12" fill="#091812" stroke="#00b894" stroke-width="1.8"/>
        <circle cx="48"  cy="65" r="5.5" fill="#00b894" opacity="0.25"/>
        <circle cx="48"  cy="65" r="2"   fill="#00b894" opacity="0.5"/>
        <line x1="48" y1="53" x2="48" y2="77" stroke="#00b894" stroke-width="1" opacity="0.4"/>
        <line x1="36" y1="65" x2="60" y2="65" stroke="#00b894" stroke-width="1" opacity="0.4"/>
        <circle cx="48" cy="65" r="10" fill="none" stroke="#00b894" stroke-width="0.5" stroke-dasharray="4 3" opacity="0.35"/>
      </g>
      <g class="wheel">
        <circle cx="156" cy="65" r="12" fill="#091812" stroke="#00b894" stroke-width="1.8"/>
        <circle cx="156" cy="65" r="5.5" fill="#00b894" opacity="0.25"/>
        <circle cx="156" cy="65" r="2"   fill="#00b894" opacity="0.5"/>
        <line x1="156" y1="53" x2="156" y2="77" stroke="#00b894" stroke-width="1" opacity="0.4"/>
        <line x1="144" y1="65" x2="168" y2="65" stroke="#00b894" stroke-width="1" opacity="0.4"/>
        <circle cx="156" cy="65" r="10" fill="none" stroke="#00b894" stroke-width="0.5" stroke-dasharray="4 3" opacity="0.35"/>
      </g>
    </svg>
  </div>
</div>

<!-- Login Card -->
<div class="login-wrap">
  <div class="login-card">

    <div class="brand-top">
      <img src="assets/img/photo_2024-10-22_11-35-22-Photoroom.png" alt="SSTS Logo">
      <span class="name">SS<span>TS</span></span>
    </div>

    <h2>Welcome back</h2>
    <p class="sub">Sign in to your SSTS account to continue.</p>

    @if (session('failed') || $errors->any())
      <div class="alert-ssts">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
          @if (session('failed')){{ session('failed') }}@endif
          @foreach ($errors->all() as $error)
            {{ $error }}{{ !$loop->last ? ' · ' : '' }}
          @endforeach
        </div>
      </div>
    @endif

    <form action="{{ route('user-login') }}" method="POST" novalidate>
      @csrf

      <div class="mb-3">
        <label for="email">Email Address <span style="color:#ff7675">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-envelope icon-left"></i>
          <input type="text" id="email" name="email"
                 placeholder="you@example.com"
                 value="{{ old('email') }}">
        </div>
        @error('email')
          <p class="err-text">{{ $message }}</p>
        @enderror
      </div>

      <div class="mb-2">
        <label for="password">Password <span style="color:#ff7675">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-lock icon-left"></i>
          <input type="password" id="password" name="password" placeholder="••••••••">
          <i class="bi bi-eye toggle-pw" id="togglePw"></i>
        </div>
        @error('password')
          <p class="err-text">{{ $message }}</p>
        @enderror
      </div>

      <a href="{{ route('forgot-password') }}" class="forgot-link">Forgot password?</a>
      <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div class="divider"><span>New to SSTS?</span></div>
    <p class="signup-row">
      Don't have an account? <a href="{{ route('register') }}">Create one here</a>
    </p>

  </div>
</div>

<script>
  const togglePw = document.getElementById('togglePw');
  const pwInput  = document.getElementById('password');
  togglePw.addEventListener('click', () => {
    const isText = pwInput.type === 'text';
    pwInput.type = isText ? 'password' : 'text';
    togglePw.className = isText ? 'bi bi-eye toggle-pw' : 'bi bi-eye-slash toggle-pw';
  });
</script>
</body>
</html>