<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Driver Key – SSTS</title>
  @vite(['resources/sass/app.scss', 'resources/sass/icons.scss', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
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
      padding: 2.5rem 0 6rem;
    }

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

    .road-strip {
      position: fixed; bottom: 60px; left: 0; right: 0;
      height: 52px; z-index: 1; pointer-events: none;
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

    .wrap {
      position: relative; z-index: 10;
      width: 100%; max-width: 500px;
      padding: 1rem;
      animation: fadeUp 0.65s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .card {
      background: var(--card-bg);
      border: 1.5px solid var(--border);
      border-radius: 20px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(0,184,148,0.08);
    }

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

    h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.7rem; font-weight: 800;
      color: var(--navy); margin-bottom: 0.3rem;
    }

    .sub {
      font-size: 0.86rem; color: var(--slate);
      margin-bottom: 1.75rem;
    }

    .section-title {
      font-family: 'Syne', sans-serif;
      font-size: 1rem; font-weight: 700;
      color: var(--emerald);
      margin-bottom: 1.25rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid rgba(0,184,148,0.2);
      display: flex; align-items: center; gap: 0.5rem;
    }

    label {
      font-size: 0.78rem; font-weight: 600;
      color: var(--slate) !important;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 0.4rem;
      display: block;
    }

    .input-wrap { position: relative; margin-bottom: 1.1rem; }

    .input-wrap i.icon-left {
      position: absolute; left: 0.9rem; top: 50%;
      transform: translateY(-50%);
      color: #a0aab4; font-size: 0.95rem;
      pointer-events: none;
    }

    .input-wrap input[type="text"],
    .input-wrap input[type="email"],
    .input-wrap input[type="tel"] {
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

    .input-wrap input:focus {
      border-color: var(--emerald);
      background: var(--emerald-lt);
    }

    .file-drop {
      border: 2px dashed rgba(0,184,148,0.4);
      border-radius: 12px;
      background: var(--emerald-lt);
      padding: 1.5rem;
      text-align: center;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      margin-bottom: 1.1rem;
    }

    .file-drop:hover, .file-drop.drag-over {
      border-color: var(--emerald);
      background: #d4f5ee;
    }

    .file-drop i {
      font-size: 2rem;
      color: var(--emerald);
      margin-bottom: 0.5rem;
      display: block;
    }

    .file-drop p {
      font-size: 0.84rem;
      color: var(--slate);
      margin: 0;
    }

    .file-drop .file-name {
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--emerald-dk);
      margin-top: 0.4rem;
    }

    .file-drop input[type="file"] {
      display: none;
    }

    .err-text { font-size: 0.78rem; color: #e74c3c; margin-top: 0.3rem; }

    .alert-success {
      background: rgba(0,184,148,0.08);
      border: 1px solid rgba(0,184,148,0.35);
      border-radius: 10px;
      padding: 0.8rem 1rem;
      font-size: 0.84rem; color: var(--emerald-dk);
      margin-bottom: 1.4rem;
      display: flex; align-items: flex-start; gap: 0.6rem;
    }

    .alert-error {
      background: rgba(231,76,60,0.08);
      border: 1px solid rgba(231,76,60,0.25);
      border-radius: 10px;
      padding: 0.8rem 1rem;
      font-size: 0.84rem; color: #e74c3c;
      margin-bottom: 1.4rem;
      display: flex; align-items: flex-start; gap: 0.6rem;
    }

    .alert-error i, .alert-success i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

    .btn-submit {
      width: 100%;
      background: var(--emerald);
      color: var(--white);
      font-family: 'Syne', sans-serif;
      font-size: 0.97rem; font-weight: 700;
      border: none; border-radius: 10px;
      padding: 0.82rem; cursor: pointer;
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

    .divider {
      display: flex; align-items: center; gap: 0.75rem;
      margin: 1.4rem 0;
    }

    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px; background: #e8ecf0;
    }

    .divider span { font-size: 0.73rem; color: #a0aab4; white-space: nowrap; }

    .bottom-links {
      text-align: center; font-size: 0.86rem;
      color: var(--slate);
    }

    .bottom-links a {
      color: var(--emerald); font-weight: 600;
      text-decoration: none; transition: color 0.2s;
    }

    .bottom-links a:hover { color: var(--emerald-dk); }

    .info-note {
      background: rgba(0,184,148,0.06);
      border: 1px solid rgba(0,184,148,0.2);
      border-radius: 10px;
      padding: 0.8rem 1rem;
      font-size: 0.82rem;
      color: var(--slate);
      margin-bottom: 1.4rem;
      display: flex; gap: 0.6rem; align-items: flex-start;
    }

    .info-note i { color: var(--emerald); font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

    @media (max-width: 576px) {
      body { padding: 1.5rem 0 5rem; }
      .card { padding: 1.75rem 1.25rem; }
      .wrap { padding: 0.75rem; }
      h2 { font-size: 1.4rem; }
    }
  </style>
</head>
<body>

<div class="glow-tr"></div>
<div class="glow-bl"></div>

<div class="road-strip">
  <div class="road-surface"></div>
  <div class="road-dash"></div>
</div>

<div class="wrap">
  <div class="card">

    <div class="brand-top">
      <img src="{{ asset('assets/img/photo_2024-10-22_11-35-22-Photoroom.png') }}" alt="SSTS Logo">
      <span class="name">SS<span>TS</span></span>
    </div>

    <h2>Request a Driver Key</h2>
    <p class="sub">Submit your details and driving license. The manager will review and email your registration key.</p>

    @if(session('success'))
      <div class="alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
      </div>
    @endif

    @if($errors->any())
      <div class="alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
          @foreach($errors->all() as $error)
            {{ $error }}{{ !$loop->last ? ' · ' : '' }}
          @endforeach
        </div>
      </div>
    @endif

    <div class="info-note">
      <i class="bi bi-info-circle-fill"></i>
      <span>Your driving license will only be used to verify your eligibility. The manager will review it before issuing your key.</span>
    </div>

    <div class="section-title">
      <i class="bi bi-person-badge"></i> Your Details
    </div>

    <form action="{{ route('driver-key.request') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf

      <div>
        <label>Full Name <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-person icon-left"></i>
          <input type="text" name="name" placeholder="Enter your full name" value="{{ old('name') }}">
        </div>
        @error('name')<p class="err-text">{{ $message }}</p>@enderror
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
        <label>Contact Number <span style="color:#e74c3c">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-telephone icon-left"></i>
          <input type="tel" name="contact" placeholder="e.g. 0123456789" value="{{ old('contact') }}">
        </div>
        @error('contact')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label>Driving License (PDF) <span style="color:#e74c3c">*</span></label>
        <div class="file-drop" id="fileDrop" onclick="document.getElementById('licenseFile').click()">
          <i class="bi bi-file-earmark-pdf"></i>
          <p>Click to upload your driving license</p>
          <p style="font-size:0.75rem; color:#a0aab4; margin-top:0.25rem;">PDF only · Max 5 MB</p>
          <p class="file-name" id="fileName" style="display:none;"></p>
          <input type="file" id="licenseFile" name="license" accept=".pdf,application/pdf">
        </div>
        @error('license')<p class="err-text">{{ $message }}</p>@enderror
      </div>

      <button type="submit" class="btn-submit">
        <i class="bi bi-send-fill me-2"></i> Submit Request
      </button>
    </form>

    <div class="divider"><span>Already have a key?</span></div>

    <div class="bottom-links">
      <span><a href="{{ route('register') }}"><i class="bi bi-arrow-left"></i> Back to Registration</a></span>
    </div>

  </div>
</div>

<script>
  const fileDrop = document.getElementById('fileDrop');
  const fileInput = document.getElementById('licenseFile');
  const fileName  = document.getElementById('fileName');

  fileInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
      fileName.textContent = this.files[0].name;
      fileName.style.display = 'block';
      fileDrop.querySelector('p').style.display = 'none';
    }
  });

  fileDrop.addEventListener('dragover', function(e) {
    e.preventDefault();
    fileDrop.classList.add('drag-over');
  });

  fileDrop.addEventListener('dragleave', function() {
    fileDrop.classList.remove('drag-over');
  });

  fileDrop.addEventListener('drop', function(e) {
    e.preventDefault();
    fileDrop.classList.remove('drag-over');
    const files = e.dataTransfer.files;
    if (files && files[0]) {
      fileInput.files = files;
      fileName.textContent = files[0].name;
      fileName.style.display = 'block';
      fileDrop.querySelector('p').style.display = 'none';
    }
  });
</script>
</body>
</html>
