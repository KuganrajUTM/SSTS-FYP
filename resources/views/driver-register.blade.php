<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Driver Registration – SSTS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
      align-items: flex-start;
      justify-content: center;
      overflow-x: hidden;
      position: relative;
      padding: 2.5rem 0 6rem;
    }

    /* diagonal lines */
    body::before {
      content: '';
      position: fixed; inset: 0;
      background: repeating-linear-gradient(
        105deg,
        transparent 0px, transparent 38px,
        rgba(0,184,148,0.04) 38px, rgba(0,184,148,0.04) 40px
      );
      animation: roadLines 6s linear infinite;
      pointer-events: none;
    }

    @keyframes roadLines {
      from { background-position: 0 0; }
      to   { background-position: 200px 0; }
    }

    .glow-tr {
      position: fixed; width: 500px; height: 500px;
      top: -120px; right: -120px;
      background: radial-gradient(circle, rgba(0,184,148,0.1) 0%, transparent 70%);
      pointer-events: none; z-index: 0;
    }

    .glow-bl {
      position: fixed; width: 350px; height: 350px;
      bottom: -100px; left: -80px;
      background: radial-gradient(circle, rgba(0,184,148,0.07) 0%, transparent 70%);
      pointer-events: none; z-index: 0;
    }

    /* ── ROAD ── */
    .road-strip {
      position: fixed;
      bottom: 60px; left: 0; right: 0;
      height: 52px; z-index: 1;
      pointer-events: none;
    }

    .road-surface {
      position: absolute; inset: 0;
      background: rgba(0,184,148,0.06);
      border-top: 2px solid rgba(0,184,148,0.22);
      border-bottom: 2px solid rgba(0,184,148,0.22);
    }

    .road-dash {
      position: absolute; top: 50%; left: 0;
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

    /* ── BUS ── */
    .bus-container {
      position: fixed; bottom: 68px; left: -220px;
      z-index: 2; pointer-events: none;
      animation: busDrive 13s linear infinite;
    }

    @keyframes busDrive {
      0%   { left: -220px; }
      100% { left: calc(100vw + 30px); }
    }

    .bus-svg-wrap { animation: busRock 0.4s ease-in-out infinite alternate; }

    @keyframes busRock {
      from { transform: translateY(0px); }
      to   { transform: translateY(-2.5px); }
    }

    .wheel {
      animation: spin 0.45s linear infinite;
      transform-box: fill-box;
      transform-origin: center;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to   { transform: rotate(360deg); }
    }

    .puff { position: absolute; left: -14px; bottom: 18px; }

    .puff span {
      display: block; border-radius: 50%;
      background: rgba(0,184,148,0.3);
      margin-bottom: 3px;
      animation: puffUp 0.9s ease-out infinite;
    }

    .puff span:nth-child(1) { width: 9px; height: 9px; }
    .puff span:nth-child(2) { width: 6px; height: 6px; animation-delay: 0.3s; opacity: 0.6; }
    .puff span:nth-child(3) { width: 4px; height: 4px; animation-delay: 0.6s; opacity: 0.4; }

    @keyframes puffUp {
      0%   { opacity: 0.6; transform: translateY(0) scale(1); }
      100% { opacity: 0;   transform: translateY(-20px) scale(1.8); }
    }

    /* ── CARD ── */
    .register-wrap {
      position: relative; z-index: 10;
      width: 100%; max-width: 520px;
      padding: 1rem;
      animation: fadeUp 0.65s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .register-card {
      background: var(--card-bg);
      border: 1.5px solid var(--border);
      border-radius: 20px;
      padding: 2.5rem 2.5rem;
      box-shadow: 0 8px 32px rgba(0,184,148,0.08);
    }

    /* brand */
    .brand-top {
      display: flex; align-items: center; gap: 0.65rem;
      margin-bottom: 1.75rem;
    }

    .brand-top img { height: 34px; width: auto; }

    .brand-top .name {
      font-family: 'Syne', sans-serif;
      font-size: 1.2rem; font-weight: 800;
      color: var(--navy); letter-spacing: 0.04em;
    }

    .brand-top .name span { color: var(--emerald); }

    .register-card h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.7rem; font-weight: 800;
      color: var(--navy); margin-bottom: 0.3rem;
    }

    .register-card .sub {
      font-size: 0.86rem;
      color: var(--slate);
      margin-bottom: 1.75rem;
    }

    /* back link */
    .back-link {
      display: inline-flex; align-items: center; gap: 0.4rem;
      font-size: 0.82rem; font-weight: 600;
      color: var(--emerald);
      text-decoration: none;
      margin-bottom: 1.5rem;
      transition: color 0.2s;
    }

    .back-link:hover { color: var(--emerald-dk); }

    /* section title */
    .form-section-title {
      font-family: 'Syne', sans-serif;
      font-size: 1rem; font-weight: 700;
      color: var(--emerald);
      margin-bottom: 1.25rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid rgba(0,184,148,0.2);
      display: flex; align-items: center; gap: 0.5rem;
    }

    /* labels */
    label {
      font-size: 0.78rem; font-weight: 600;
      color: var(--slate) !important;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 0.4rem;
      display: block;
    }

    /* inputs */
    .input-wrap { position: relative; margin-bottom: 1.1rem; }

    .input-wrap i.icon-left {
      position: absolute; left: 0.9rem; top: 50%;
      transform: translateY(-50%);
      color: #a0aab4; font-size: 0.95rem;
      pointer-events: none;
    }

    .input-wrap input {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid #dde3ea;
      border-radius: 10px;
      padding: 0.7rem 1rem 0.7rem 2.5rem;
      color: var(--navy);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.92rem;
      transition: border-color 0.2s, background 0.2s;
      outline: none;
    }

    .input-wrap input::placeholder { color: #a0aab4; }

    .input-wrap input:focus {
      border-color: var(--emerald);
      background: var(--emerald-lt);
    }

    .toggle-pw {
      position: absolute; right: 0.9rem; top: 50%;
      transform: translateY(-50%);
      color: #a0aab4;
      cursor: pointer; font-size: 0.95rem;
      transition: color 0.2s;
    }

    .toggle-pw:hover { color: var(--emerald); }

    /* file upload */
    .file-wrap { position: relative; margin-bottom: 1.1rem; }

    .file-label-box {
      display: flex; align-items: center;
      background: #f0f4f8;
      border: 1.5px dashed rgba(0,184,148,0.4);
      border-radius: 10px;
      padding: 0.7rem 1rem;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      gap: 0.65rem;
    }

    .file-label-box:hover {
      border-color: var(--emerald);
      background: var(--emerald-lt);
    }

    .file-label-box i {
      color: var(--emerald); font-size: 1.1rem; flex-shrink: 0;
    }

    .file-label-box .file-text {
      font-size: 0.88rem; color: #a0aab4;
      overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    .file-label-box .file-text.selected { color: var(--navy); }

    .file-wrap input[type="file"] {
      position: absolute; inset: 0;
      opacity: 0; cursor: pointer;
      width: 100%; height: 100%;
    }

    /* error */
    .err-text { font-size: 0.78rem; color: #e74c3c; margin-top: 0.3rem; }

    /* alert */
    .alert-ssts {
      background: rgba(231,76,60,0.08);
      border: 1px solid rgba(231,76,60,0.25);
      border-radius: 10px;
      padding: 0.8rem 1rem;
      font-size: 0.84rem; color: #e74c3c;
      margin-bottom: 1.4rem;
      display: flex; align-items: flex-start; gap: 0.6rem;
    }

    .alert-ssts i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

    /* submit */
    .btn-submit {
      width: 100%;
      background: var(--emerald);
      color: var(--white);
      font-family: 'Syne', sans-serif;
      font-size: 0.97rem; font-weight: 700;
      border: none; border-radius: 10px;
      padding: 0.82rem;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(0,184,148,0.25);
      letter-spacing: 0.03em;
      margin-top: 0.5rem;
    }

    .btn-submit:hover {
      background: var(--emerald-dk);
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(0,184,148,0.35);
    }

    /* divider & login link */
    .divider {
      display: flex; align-items: center; gap: 0.75rem;
      margin: 1.4rem 0;
    }

    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px;
      background: #e8ecf0;
    }

    .divider span { font-size: 0.73rem; color: #a0aab4; white-space: nowrap; }

    .login-row {
      text-align: center;
      font-size: 0.86rem;
      color: var(--slate);
    }

    .login-row a {
      color: var(--emerald); font-weight: 600;
      text-decoration: none; transition: color 0.2s;
    }

    .login-row a:hover { color: var(--emerald-dk); }

    /* ── MOBILE ── */
    @media (max-width: 576px) {
      body { padding: 1.5rem 0 6rem; }
      .register-card { padding: 1.75rem 1.25rem; }
      .register-wrap { padding: 0.75rem; }
      .register-card h2 { font-size: 1.4rem; }
      .register-card .sub { font-size: 0.82rem; }
      .brand-top img { height: 28px; }
      .brand-top .name { font-size: 1rem; }
      .input-wrap input { font-size: 0.86rem; padding: 0.65rem 0.9rem 0.65rem 2.4rem; }
      .btn-submit { font-size: 0.9rem; padding: 0.75rem; }
      .bus-svg-wrap svg { width: 150px; height: 60px; }
      .bus-container { bottom: 70px; }
      .road-strip { bottom: 60px; height: 44px; }
    }

    @media (max-width: 360px) {
      .register-card { padding: 1.5rem 1rem; }
      .register-card h2 { font-size: 1.25rem; }
      .bus-svg-wrap svg { width: 120px; height: 48px; }
    }
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

<!-- Animated Bus -->
<div class="bus-container">
  <div class="puff"><span></span><span></span><span></span></div>
  <div class="bus-svg-wrap">
    <svg xmlns="http://www.w3.org/2000/svg" width="210" height="82" viewBox="0 0 210 82">
      <rect x="12" y="6" width="178" height="54" rx="8" fill="#0d2318" stroke="#00b894" stroke-width="1.8"/>
      <rect x="16" y="6" width="170" height="10" rx="5" fill="#00b894" opacity="0.15"/>
      <rect x="166" y="9" width="21" height="48" rx="5" fill="#091c12" stroke="#00b894" stroke-width="1.5"/>
      <rect x="169" y="13" width="15" height="22" rx="3" fill="#00b894" opacity="0.22"/>
      <line x1="172" y1="14" x2="169" y2="34" stroke="#80ffd4" stroke-width="1" opacity="0.25"/>
      <rect x="169" y="40" width="15" height="9" rx="2" fill="#00b894" opacity="0.65"/>
      <rect x="171" y="41.5" width="11" height="6" rx="1" fill="#80ffd4" opacity="0.45"/>
      <rect x="22"  y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="52"  y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="82"  y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="112" y="13" width="23" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <rect x="142" y="13" width="20" height="19" rx="3" fill="#00b894" opacity="0.18"/>
      <line x1="25"  y1="15" x2="23"  y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="55"  y1="15" x2="53"  y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="85"  y1="15" x2="83"  y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="115" y1="15" x2="113" y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <line x1="145" y1="15" x2="143" y2="30" stroke="#80ffd4" stroke-width="1" opacity="0.28"/>
      <rect x="22" y="37" width="18" height="21" rx="2" fill="#081510" stroke="#00b894" stroke-width="1"/>
      <line x1="31" y1="39" x2="31" y2="57" stroke="#00b894" stroke-width="0.8" opacity="0.5"/>
      <rect x="28" y="47" width="5" height="2" rx="1" fill="#00b894" opacity="0.5"/>
      <rect x="12" y="37" width="178" height="3" rx="1" fill="#00b894" opacity="0.3"/>
      <text x="98" y="55" text-anchor="middle" font-family="'Syne',sans-serif" font-size="8.5" font-weight="800" fill="#00b894" opacity="0.8" letter-spacing="2.5">SSTS</text>
      <rect x="9" y="52" width="5" height="7" rx="1" fill="#00b894" opacity="0.4"/>
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

<!-- Register Card -->
<div class="register-wrap">
  <div class="register-card">

    <!-- Brand -->
    <div class="brand-top">
      <img src="{{ asset('assets/img/photo_2024-10-22_11-35-22-Photoroom.png') }}" alt="SSTS Logo">
      <span class="name">SS<span>TS</span></span>
    </div>

    <!-- Back link -->
    <a href="{{ route('register') }}" class="back-link">
      <i class="bi bi-arrow-left"></i> Back to Parent Registration
    </a>

    <h2>Driver Registration</h2>
    <p class="sub">Create your driver account to get started with SSTS.</p>

    <!-- Error Alert -->
    @if ($errors->any())
      <div class="alert-ssts">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
          @foreach ($errors->all() as $error)
            {{ $error }}{{ !$loop->last ? ' · ' : '' }}
          @endforeach
        </div>
      </div>
    @endif

    <div class="form-section-title">
      <i class="bi bi-person-badge"></i> Driver Details
    </div>

    <form action="{{ route('driver-register') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf
      <input type="hidden" name="role" value="D">

      <div>
        <label>Full Name <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-person icon-left"></i>
          <input type="text" name="fullname" placeholder="Enter full name" value="{{ old('fullname') }}">
        </div>
        @error('fullname')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label>Username <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-at icon-left"></i>
          <input type="text" name="username" placeholder="Enter username" value="{{ old('username') }}">
        </div>
        @error('username')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label>Email Address <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-envelope icon-left"></i>
          <input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}">
        </div>
        @error('email')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label>Password <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-lock icon-left"></i>
          <input type="password" id="d-password" name="password" placeholder="••••••••">
          <i class="bi bi-eye toggle-pw" onclick="togglePw('d-password', this)"></i>
        </div>
        @error('password')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label>Confirm Password <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-lock-fill icon-left"></i>
          <input type="password" id="d-confirm" name="password_confirmation" placeholder="••••••••">
          <i class="bi bi-eye toggle-pw" onclick="togglePw('d-confirm', this)"></i>
        </div>
      </div>

      <div>
        <label>Vehicle Number <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-truck icon-left"></i>
          <input type="text" name="vrn" placeholder="e.g. JDT 1234" value="{{ old('vrn') }}">
        </div>
        @error('vrn')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div class="form-section-title" style="margin-top:1.5rem;">
        <i class="bi bi-bank"></i> Bank Details
      </div>

      <div>
        <label>Bank Name <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-bank icon-left"></i>
          <select name="bank_name" style="width:100%;background:var(--input-bg);border:1.5px solid #dde3ea;border-radius:10px;padding:0.7rem 1rem 0.7rem 2.5rem;color:var(--navy);font-family:'DM Sans',sans-serif;font-size:0.92rem;outline:none;appearance:none;" required>
            <option value="" disabled {{ old('bank_name') ? '' : 'selected' }}>Select bank…</option>
            @foreach([
              'Maybank','CIMB Bank','Public Bank','RHB Bank','Hong Leong Bank',
              'AmBank','Bank Islam','Bank Rakyat','Affin Bank','Alliance Bank',
              'BSN (Bank Simpanan Nasional)','Agrobank','OCBC Bank','Standard Chartered',
              'HSBC Bank'
            ] as $bank)
              <option value="{{ $bank }}" {{ old('bank_name') === $bank ? 'selected' : '' }}>{{ $bank }}</option>
            @endforeach
          </select>
        </div>
        @error('bank_name')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label>Bank Account Number <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-credit-card icon-left"></i>
          <input type="text" name="bank_account_number" placeholder="e.g. 1234567890" value="{{ old('bank_account_number') }}" maxlength="50">
        </div>
        @error('bank_account_number')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div class="form-section-title" style="margin-top:1.5rem;">
        <i class="bi bi-file-earmark-pdf"></i> Documents
      </div>

      <div>
        <label>Upload License <span style="color:#a0aab4; font-size:0.7rem">(PDF only)</span> <span style="color:#e74c3c">*</span></label>
        <div class="file-wrap">
          <div class="file-label-box" id="license-box">
            <i class="bi bi-file-earmark-pdf"></i>
            <span class="file-text" id="license-text">Choose PDF file…</span>
          </div>
          <input type="file" name="license" accept="application/pdf"
                 onchange="updateFileLabel(this, 'license-text')" required>
        </div>
        @error('license')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label>Upload SPAD Document <span style="color:#a0aab4; font-size:0.7rem">(PDF only)</span> <span style="color:#e74c3c">*</span></label>
        <div class="file-wrap">
          <div class="file-label-box" id="spad-box">
            <i class="bi bi-file-earmark-pdf"></i>
            <span class="file-text" id="spad-text">Choose PDF file…</span>
          </div>
          <input type="file" name="spad" accept="application/pdf"
                 onchange="updateFileLabel(this, 'spad-text')" required>
        </div>
        @error('spad')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <button type="submit" class="btn-submit">Create Driver Account</button>
    </form>

    <div class="divider"><span>Already have an account?</span></div>
    <p class="login-row">
      <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Go to Login</a>
    </p>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function togglePw(id, icon) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    icon.className = isText ? 'bi bi-eye toggle-pw' : 'bi bi-eye-slash toggle-pw';
  }

  function updateFileLabel(input, textId) {
    const name = input.value.split('\\').pop();
    const el = document.getElementById(textId);
    el.textContent = name || 'Choose PDF file…';
    el.classList.toggle('selected', !!name);
  }
</script>
</body>
</html>