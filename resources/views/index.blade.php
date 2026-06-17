<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SSTS – Smart School Transportation System</title>
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

    * { box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      color: var(--navy);
      background: var(--white);
      overflow-x: hidden;
    }

    h1, h2, h3, h4, h5, .brand-name {
      font-family: 'Syne', sans-serif;
    }

    /* ── NAVBAR ─────────────────────────────────── */
    .navbar {
      background: var(--navy) !important;
      padding: 0.75rem 0;
      border-bottom: 3px solid var(--emerald);
    }

    .navbar-brand img {
      height: 40px;
      width: auto;
    }

    .brand-name {
      font-size: 1.4rem;
      font-weight: 800;
      color: var(--white) !important;
      letter-spacing: 0.04em;
    }

    .brand-name span {
      color: var(--emerald);
    }

    .nav-link {
      color: rgba(255,255,255,0.75) !important;
      font-weight: 500;
      font-size: 0.92rem;
      letter-spacing: 0.03em;
      position: relative;
      padding: 0.5rem 0.9rem !important;
      transition: color 0.2s;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0; left: 50%;
      width: 0; height: 2px;
      background: var(--emerald);
      transition: width 0.25s, left 0.25s;
    }

    .nav-link:hover, .nav-link.active {
      color: var(--white) !important;
    }

    .nav-link:hover::after, .nav-link.active::after {
      width: 60%; left: 20%;
    }

    .btn-register {
      background: var(--emerald);
      color: var(--white) !important;
      border: none;
      font-weight: 700;
      border-radius: 6px;
      padding: 0.45rem 1.2rem;
      font-size: 0.88rem;
      transition: background 0.2s, transform 0.15s;
    }

    .btn-register:hover {
      background: var(--emerald-dk);
      transform: translateY(-1px);
    }

    .btn-login {
      border: 1.5px solid rgba(255,255,255,0.35);
      color: var(--white) !important;
      border-radius: 6px;
      padding: 0.45rem 1.1rem;
      font-size: 0.88rem;
      background: transparent;
      transition: border-color 0.2s, background 0.2s;
    }

    .btn-login:hover {
      border-color: var(--emerald);
      background: rgba(0,184,148,0.1);
    }

    /* ── HERO ────────────────────────────────────── */
    .hero {
      position: relative;
      min-height: 92vh;
      display: flex;
      align-items: center;
      background: var(--navy);
      overflow: hidden;
    }

    /* animated road lines */
    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        repeating-linear-gradient(
          105deg,
          transparent 0px,
          transparent 38px,
          rgba(0,184,148,0.06) 38px,
          rgba(0,184,148,0.06) 40px
        );
      animation: roadLines 6s linear infinite;
    }

    @keyframes roadLines {
      from { background-position: 0 0; }
      to   { background-position: 200px 0; }
    }

    /* soft radial glow */
    .hero::after {
      content: '';
      position: absolute;
      width: 650px; height: 650px;
      top: -100px; right: -100px;
      background: radial-gradient(circle, rgba(0,184,148,0.18) 0%, transparent 70%);
      pointer-events: none;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero-badge {
      display: inline-block;
      background: rgba(0,184,148,0.15);
      border: 1px solid var(--emerald);
      color: var(--emerald);
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      padding: 0.35rem 0.9rem;
      border-radius: 50px;
      margin-bottom: 1.5rem;
      animation: fadeDown 0.6s ease both;
    }

    .hero h1 {
      font-size: clamp(2.4rem, 5vw, 4rem);
      color: var(--white);
      line-height: 1.12;
      margin-bottom: 1.5rem;
      animation: fadeDown 0.7s 0.1s ease both;
    }

    .hero h1 em {
      font-style: normal;
      color: var(--emerald);
    }

    .hero p.lead {
      color: rgba(255,255,255,0.65);
      font-size: 1.1rem;
      max-width: 520px;
      line-height: 1.7;
      margin-bottom: 2.2rem;
      animation: fadeDown 0.7s 0.2s ease both;
    }

    .hero-btns {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      animation: fadeDown 0.7s 0.3s ease both;
    }

    .btn-hero-primary {
      background: var(--emerald);
      color: var(--white);
      font-weight: 700;
      font-size: 0.95rem;
      padding: 0.75rem 1.8rem;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(0,184,148,0.35);
    }

    .btn-hero-primary:hover {
      background: var(--emerald-dk);
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0,184,148,0.45);
      color: var(--white);
    }

    .btn-hero-ghost {
      background: transparent;
      color: var(--white);
      font-weight: 600;
      font-size: 0.95rem;
      padding: 0.75rem 1.8rem;
      border-radius: 8px;
      border: 1.5px solid rgba(255,255,255,0.3);
      cursor: pointer;
      text-decoration: none;
      transition: border-color 0.2s, background 0.2s;
    }

    .btn-hero-ghost:hover {
      border-color: var(--emerald);
      background: rgba(0,184,148,0.08);
      color: var(--white);
    }

    /* hero stats */
    .hero-stats {
      display: flex;
      gap: 2.5rem;
      margin-top: 3.5rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(255,255,255,0.1);
      animation: fadeDown 0.7s 0.4s ease both;
    }

    .stat-item .num {
      font-family: 'Syne', sans-serif;
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--emerald);
    }

    .stat-item .lbl {
      font-size: 0.78rem;
      color: rgba(255,255,255,0.5);
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    /* hero illustration panel */
    .hero-visual {
      position: relative;
      z-index: 2;
      animation: fadeLeft 0.8s 0.3s ease both;
    }

    .bus-card {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(0,184,148,0.25);
      border-radius: 20px;
      padding: 2rem;
      backdrop-filter: blur(10px);
    }

    .bus-icon-wrap {
      width: 80px; height: 80px;
      background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      font-size: 2.2rem;
      color: white;
      box-shadow: 0 8px 25px rgba(0,184,148,0.4);
    }

    .bus-card h4 {
      color: white;
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }

    .bus-card p {
      color: rgba(255,255,255,0.5);
      font-size: 0.85rem;
      margin-bottom: 1.5rem;
    }

    .status-row {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      margin-bottom: 0.75rem;
    }

    .dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .dot-green  { background: #00e676; box-shadow: 0 0 6px #00e676; }
    .dot-amber  { background: var(--amber); box-shadow: 0 0 6px var(--amber); }
    .dot-grey   { background: rgba(255,255,255,0.25); }

    .status-row span {
      font-size: 0.82rem;
      color: rgba(255,255,255,0.7);
    }

    .progress-bar-wrap {
      background: rgba(255,255,255,0.08);
      border-radius: 4px;
      height: 5px;
      margin-top: 1.5rem;
      overflow: hidden;
    }

    .progress-bar-inner {
      height: 100%;
      background: linear-gradient(90deg, var(--emerald), #80ffd4);
      border-radius: 4px;
      width: 72%;
      animation: growBar 1.5s 1s ease both;
    }

    @keyframes growBar {
      from { width: 0; }
      to   { width: 72%; }
    }

    .progress-label {
      display: flex;
      justify-content: space-between;
      margin-top: 0.4rem;
    }

    .progress-label span {
      font-size: 0.72rem;
      color: rgba(255,255,255,0.4);
    }

    /* ── ABOUT ────────────────────────────────────── */
    #about {
      background: var(--emerald-lt);
      padding: 5rem 0;
      position: relative;
      overflow: hidden;
    }

    #about::before {
      content: 'SSTS';
      position: absolute;
      font-family: 'Syne', sans-serif;
      font-size: 14rem;
      font-weight: 800;
      color: rgba(0,184,148,0.05);
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      white-space: nowrap;
      pointer-events: none;
    }

    .about-pill {
      display: inline-block;
      background: var(--emerald);
      color: white;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 0.3rem 0.85rem;
      border-radius: 50px;
      margin-bottom: 1.2rem;
    }

    #about h2 {
      font-size: 2.2rem;
      color: var(--navy);
      margin-bottom: 1rem;
    }

    #about p.lead {
      color: var(--slate);
      font-size: 1.05rem;
      line-height: 1.75;
      max-width: 680px;
      margin: 0 auto;
    }

    /* ── FEATURES ──────────────────────────────────── */
    #features {
      padding: 5rem 0;
      background: var(--light);
    }

    .section-label {
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      color: var(--emerald);
      margin-bottom: 0.6rem;
    }

    #features h2 {
      font-size: 2.2rem;
      color: var(--navy);
      margin-bottom: 0.5rem;
    }

    #features .sub {
      color: var(--slate);
      font-size: 1rem;
      max-width: 500px;
      margin: 0 auto 3.5rem;
    }

    .feature-card {
      background: var(--white);
      border-radius: 16px;
      padding: 2rem 1.75rem;
      border: 1.5px solid #e8ecf0;
      transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
      height: 100%;
    }

    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(0,184,148,0.12);
      border-color: var(--emerald);
    }

    .feat-icon {
      width: 56px; height: 56px;
      background: var(--emerald-lt);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: var(--emerald);
      margin-bottom: 1.25rem;
      transition: background 0.3s, color 0.3s;
    }

    .feature-card:hover .feat-icon {
      background: var(--emerald);
      color: white;
    }

    .feature-card h5 {
      font-size: 1.05rem;
      color: var(--navy);
      margin-bottom: 0.6rem;
    }

    .feature-card p {
      font-size: 0.88rem;
      color: var(--slate);
      line-height: 1.65;
      margin: 0;
    }

    /* ── FAQ ──────────────────────────────────────── */
    #faq {
      padding: 5rem 0;
      background: var(--white);
    }

    #faq h2 {
      font-size: 2.2rem;
      color: var(--navy);
      margin-bottom: 0.5rem;
    }

    #faq .sub {
      color: var(--slate);
      font-size: 1rem;
      margin-bottom: 3rem;
    }

    .accordion-item {
      border: 1.5px solid #e8ecf0 !important;
      border-radius: 12px !important;
      margin-bottom: 0.75rem;
      overflow: hidden;
    }

    .accordion-button {
      font-family: 'Syne', sans-serif;
      font-size: 0.97rem;
      font-weight: 700;
      color: var(--navy) !important;
      background: var(--white) !important;
      padding: 1.1rem 1.4rem;
    }

    .accordion-button:focus {
      box-shadow: none !important;
    }

    .accordion-button:not(.collapsed) {
      background: var(--emerald) !important;
      color: var(--white) !important;
      box-shadow: none !important;
    }

    .accordion-button::after {
      filter: none;
    }

    .accordion-button:not(.collapsed)::after {
      filter: brightness(0) invert(1);
    }

    .accordion-body {
      font-size: 0.9rem;
      color: var(--slate);
      line-height: 1.7;
      padding: 1rem 1.4rem 1.2rem;
    }

    /* ── FOOTER ───────────────────────────────────── */
    footer {
      background: var(--navy-mid);
      color: rgba(255,255,255,0.55);
      padding: 2.5rem 0;
      border-top: 3px solid var(--emerald);
    }

    footer p {
      font-size: 0.85rem;
      margin-bottom: 0.75rem;
    }

    #contact-icons a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px; height: 40px;
      border-radius: 50%;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.12);
      color: rgba(255,255,255,0.6);
      font-size: 1.1rem;
      margin: 0 5px;
      transition: background 0.2s, color 0.2s, transform 0.2s;
      text-decoration: none;
    }

    #contact-icons a:hover {
      background: var(--emerald);
      color: var(--white);
      border-color: var(--emerald);
      transform: translateY(-3px);
    }

    /* ── ANIMATIONS ───────────────────────────────── */
    @keyframes fadeDown {
      from { opacity: 0; transform: translateY(-18px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeLeft {
      from { opacity: 0; transform: translateX(30px); }
      to   { opacity: 1; transform: translateX(0); }
    }

    /* scroll reveal */
    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: none;
    }
        /* ── MOBILE RESPONSIVE ── */
    @media (max-width: 576px) {

      /* Navbar */
      .brand-name { font-size: 1.1rem; }
      .navbar-brand img { height: 30px; }
      .btn-register, .btn-login { font-size: 0.82rem; padding: 0.4rem 0.9rem; }

      /* Hero */
      .hero { min-height: auto; padding: 60px 0 40px; }
      .hero h1 { font-size: 2rem; }
      .hero p.lead { font-size: 0.95rem; }
      .hero-badge { font-size: 0.7rem; }
      .hero-stats { gap: 1.2rem; margin-top: 2rem; padding-top: 1.5rem; }
      .stat-item .num { font-size: 1.3rem; }
      .stat-item .lbl { font-size: 0.7rem; }
      .btn-hero-primary, .btn-hero-ghost { font-size: 0.88rem; padding: 0.65rem 1.3rem; }

      /* About */
      #about h2 { font-size: 1.7rem; }
      #about p.lead { font-size: 0.92rem; }
      #about::before { font-size: 6rem; }

      /* Features */
      #features h2 { font-size: 1.7rem; }
      .feature-card { padding: 1.5rem 1.25rem; }
      .feat-icon { width: 48px; height: 48px; font-size: 1.25rem; }

      /* FAQ */
      #faq h2 { font-size: 1.7rem; }
      .accordion-button { font-size: 0.88rem; padding: 0.9rem 1rem; }
      .accordion-body { font-size: 0.85rem; padding: 0.85rem 1rem; }

      /* Footer */
      #contact-icons a { width: 36px; height: 36px; font-size: 1rem; }
    }

    @media (max-width: 360px) {
      .hero h1 { font-size: 1.75rem; }
      .hero-stats { flex-wrap: wrap; gap: 1rem; }
      .brand-name { font-size: 1rem; }
    }
    .dropdown-menu {
      background: var(--white);
      border: 1.5px solid var(--border, rgba(0,184,148,0.25));
      border-radius: 10px;
      padding: 0.4rem;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      min-width: 160px;
    }

    .dropdown-menu .dropdown-item {
      font-family: 'Syne', sans-serif;
      font-size: 0.88rem;
      font-weight: 700;
      color: var(--navy);
      border-radius: 7px;
      padding: 0.55rem 0.9rem;
      transition: background 0.2s, color 0.2s;
    }

    .dropdown-menu .dropdown-item:hover {
      background: var(--emerald-lt);
      color: var(--emerald);
    }

    .dropdown-menu .dropdown-item i {
      color: var(--emerald);
    }

    @media (max-width: 576px) {
      .dropdown-menu {
        min-width: 140px;
      }
    }

    
  </style>
</head>
<body>

<!-- ── NAVBAR ──────────────────────────────────────── -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <img src="assets/img/photo_2024-10-22_11-35-22-Photoroom.png" alt="SSTS Logo">
      <span class="brand-name">SS<span>TS</span></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
      </ul>
      <div class="dropdown ms-3">
        <button class="btn-register dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Register
        </button>
        <ul class="dropdown-menu dropdown-menu-end mt-2">
          <li>
            <a class="dropdown-item" href="{{ route('register') }}">
              <i class="bi bi-person-heart me-2"></i> Parent
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#" id="driverRegisterBtn">
              <i class="bi bi-person-badge me-2"></i> Driver
            </a>
          </li>
        </ul>
      </div>
      <a href="{{ route('login') }}" class="btn-login ms-2">Login</a>
    </div>
  </div>
</nav>

<!-- ── HERO ────────────────────────────────────────── -->
<section class="hero">
  <div class="container">
    <div class="row align-items-center gy-5">
      <!-- left -->
      <div class="col-lg-6 hero-content">
        <div class="hero-badge">🚌 School Transport Reimagined</div>
        <h1>Smart School<br><em>Transportation</em><br>System</h1>
        <p class="lead">Effortless bus management, verified drivers, and real-time alerts — all in one platform built for Malaysian schools.</p>
        <div class="hero-btns">
          <a href="{{ route('register') }}" class="btn-hero-primary">Get Started Free</a>
          <a href="#features" class="btn-hero-ghost">Explore Features</a>
        </div>
        <div class="hero-stats">
          <div class="stat-item">
            <div class="num">100%</div>
            <div class="lbl">Driver Verified</div>
          </div>
          <div class="stat-item">
            <div class="num">Live</div>
            <div class="lbl">Notifications</div>
          </div>
          <div class="stat-item">
            <div class="num">Easy</div>
            <div class="lbl">Fee Payment</div>
          </div>
        </div>
      </div>

      <!-- right — mock dashboard card -->
      <div class="col-lg-5 offset-lg-1 hero-visual">
        <div class="bus-card">
          <div class="bus-icon-wrap">
            <i class="bi bi-bus-front-fill"></i>
          </div>
          <h4>Bus Route Tracker</h4>
          <p>Live status of all registered buses</p>

          <div class="status-row">
            <div class="dot dot-green"></div>
            <span>Bus A-07 &nbsp;·&nbsp; En route to school &nbsp;·&nbsp; 3 stops left</span>
          </div>
          <div class="status-row">
            <div class="dot dot-amber"></div>
            <span>Bus B-12 &nbsp;·&nbsp; Delayed — 5 min</span>
          </div>
          <div class="status-row">
            <div class="dot dot-grey"></div>
            <span>Bus C-03 &nbsp;·&nbsp; Waiting at depot</span>
          </div>

          <div class="progress-bar-wrap">
            <div class="progress-bar-inner"></div>
          </div>
          <div class="progress-label">
            <span>Route completion</span>
            <span>72%</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── ABOUT ──────────────────────────────────────── -->
<section id="about" class="py-5">
  <div class="container text-center reveal">
    <div class="about-pill">About the Platform</div>
    <h2>Built for Safety.<br>Designed for Simplicity.</h2>
    <p class="lead">SSTS (Smart School Transportation System) is a reliable platform designed to simplify school transportation management. From tracking bus routes to ensuring driver verification and notifying parents about their child's whereabouts, we streamline the entire process to ensure safety and efficiency.</p>
  </div>
</section>

<!-- ── FEATURES ───────────────────────────────────── -->
<section id="features" class="py-5">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label">What We Offer</div>
      <h2>Key Features</h2>
      <p class="sub">Everything a school, parent, and driver needs — in one seamless system.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="feature-card">
          <div class="feat-icon"><i class="bi bi-wallet2"></i></div>
          <h5>Efficient Fee Collection</h5>
          <p>Simplify and automate school bus fee payments with secure, trackable methods. No more manual receipts.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card">
          <div class="feat-icon"><i class="bi bi-shield-check"></i></div>
          <h5>Driver Verification</h5>
          <p>Every driver is thoroughly vetted before they step behind the wheel. Parents can rest assured.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card">
          <div class="feat-icon"><i class="bi bi-chat-dots"></i></div>
          <h5>Real-Time Notifications</h5>
          <p>Instant alerts when a bus departs, arrives, or is delayed. Parents are always in the loop.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card">
          <div class="feat-icon"><i class="bi bi-map"></i></div>
          <h5>Route Management</h5>
          <p>Plan and manage bus routes with ease. Assign stops, track schedules, and optimise daily trips.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card">
          <div class="feat-icon"><i class="bi bi-people"></i></div>
          <h5>Student Records</h5>
          <p>Maintain up-to-date student and parent records securely linked to their assigned bus and route.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card">
          <div class="feat-icon"><i class="bi bi-bar-chart-line"></i></div>
          <h5>Admin Dashboard</h5>
          <p>A centralised dashboard gives school administrators complete oversight of the entire transport operation.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── FAQ ────────────────────────────────────────── -->
<section id="faq" class="py-5">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label">Got Questions?</div>
      <h2 class="mb-2">Frequently Asked Questions</h2>
      <p class="sub mb-5">Quick answers to common queries about SSTS.</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8 reveal">
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                How do I register for the service?
              </button>
            </h2>
            <div id="q1" class="accordion-collapse collapse show">
              <div class="accordion-body">
                Click the <strong>Register</strong> button on the top navigation bar and follow the step-by-step instructions to set up your account.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                Are the drivers verified?
              </button>
            </h2>
            <div id="q2" class="accordion-collapse collapse">
              <div class="accordion-body">
                Yes. All drivers undergo a strict background check and verification process before they are approved to operate. You can view a driver's verification status in the app.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                How can I contact support?
              </button>
            </h2>
            <div id="q3" class="accordion-collapse collapse">
              <div class="accordion-body">
                You can reach us via Facebook, WhatsApp, or email through the contact icons in the footer below.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q4">
                Can parents track the bus in real time?
              </button>
            </h2>
            <div id="q4" class="accordion-collapse collapse">
              <div class="accordion-body">
                Yes. Once registered, parents receive live notifications on bus status and can track route progress through their account dashboard.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── FOOTER ──────────────────────────────────────── -->
<footer class="text-center">
  <div class="container">
    <p class="mb-1 fw-semibold" style="color:rgba(255,255,255,0.75); font-family:'Syne',sans-serif; font-size:1rem;">
      Smart School Transportation System
    </p>
    <p>&copy; 2024 SSTS. All Rights Reserved.</p>
    <div id="contact-icons" class="mt-2">
      <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
      <a href="#" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
      <a href="mailto:info@ssts.edu.my" title="Email"><i class="bi bi-envelope"></i></a>
    </div>
  </div>
</footer>

{{-- Driver Key Modal --}}
<div class="modal fade" id="driverKeyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px; overflow:hidden; border:1.5px solid rgba(0,184,148,0.25);">
      <div class="modal-header" style="background:#e6f9f5; border-bottom:1.5px solid rgba(0,184,148,0.25);">
        <h5 class="modal-title" id="dkModalTitle" style="font-family:'Syne',sans-serif; color:#0a1628; font-weight:700;">
          <i class="bi bi-key me-2" style="color:#00b894;"></i> Driver Registration Key
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">

        {{-- TAB 1: Enter Key --}}
        <div id="tabEnterKey">
          <p style="font-size:0.88rem; color:#4a5568; margin-bottom:1.2rem;">
            Driver accounts require a unique key authorised by our manager. Enter your key below to proceed.
          </p>
          <label style="font-size:0.75rem; font-weight:600; color:#4a5568; text-transform:uppercase; letter-spacing:0.06em; display:block; margin-bottom:0.5rem;">
            Enter 6-Digit Key
          </label>
          <input type="text" id="driverKeyInput" maxlength="6" placeholder="_ _ _ _ _ _"
                 style="width:100%; text-align:center; font-size:2rem; letter-spacing:0.6em;
                        font-family:'Syne',sans-serif; font-weight:700;
                        background:#f0f4f8; border:1.5px solid #dde3ea; border-radius:10px;
                        padding:0.65rem 1rem; color:#0a1628; outline:none; transition:border-color 0.2s;"
                 oninput="this.value=this.value.replace(/[^0-9]/g,''); clearKeyError();">
          <p id="keyError" style="display:none; font-size:0.8rem; color:#e74c3c; margin-top:0.45rem; margin-bottom:0;"></p>
          <p style="margin-top:1.1rem; margin-bottom:0; font-size:0.84rem; color:#4a5568; text-align:center;">
            Don't have a key?
            <a href="#" onclick="showRequestTab(); return false;"
               style="color:#00b894; font-weight:600; text-decoration:none;">Request one here</a>
          </p>
        </div>

        {{-- TAB 2: Request Key --}}
        <div id="tabRequestKey" style="display:none;">
          <p style="font-size:0.88rem; color:#4a5568; margin-bottom:1.2rem;">
            Submit your details and the manager will email your registration key to you.
          </p>
          <div style="margin-bottom:0.9rem;">
            <label style="font-size:0.75rem; font-weight:600; color:#4a5568; text-transform:uppercase; letter-spacing:0.06em; display:block; margin-bottom:0.4rem;">Full Name</label>
            <input type="text" id="reqName" placeholder="Your full name"
                   style="width:100%; background:#f0f4f8; border:1.5px solid #dde3ea; border-radius:9px;
                          padding:0.55rem 0.9rem; font-size:0.92rem; color:#0a1628; outline:none;">
          </div>
          <div style="margin-bottom:0.9rem;">
            <label style="font-size:0.75rem; font-weight:600; color:#4a5568; text-transform:uppercase; letter-spacing:0.06em; display:block; margin-bottom:0.4rem;">Email Address</label>
            <input type="email" id="reqEmail" placeholder="your@email.com"
                   style="width:100%; background:#f0f4f8; border:1.5px solid #dde3ea; border-radius:9px;
                          padding:0.55rem 0.9rem; font-size:0.92rem; color:#0a1628; outline:none;">
          </div>
          <div style="margin-bottom:0.9rem;">
            <label style="font-size:0.75rem; font-weight:600; color:#4a5568; text-transform:uppercase; letter-spacing:0.06em; display:block; margin-bottom:0.4rem;">Contact Number</label>
            <input type="text" id="reqContact" placeholder="e.g. 0123456789"
                   style="width:100%; background:#f0f4f8; border:1.5px solid #dde3ea; border-radius:9px;
                          padding:0.55rem 0.9rem; font-size:0.92rem; color:#0a1628; outline:none;">
          </div>
          <p id="reqError" style="display:none; font-size:0.8rem; color:#e74c3c; margin-top:0.3rem; margin-bottom:0;"></p>
          <p id="reqSuccess" style="display:none; font-size:0.84rem; color:#00b894; font-weight:600; margin-top:0.3rem; margin-bottom:0;"></p>
          <p style="margin-top:1rem; margin-bottom:0; font-size:0.84rem; color:#4a5568; text-align:center;">
            Already have a key?
            <a href="#" onclick="showEnterKeyTab(); return false;"
               style="color:#00b894; font-weight:600; text-decoration:none;">Enter it here</a>
          </p>
        </div>

      </div>
      <div class="modal-footer" style="border-top:1.5px solid rgba(0,184,148,0.25); gap:0.5rem;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="verifyKeyBtn" onclick="verifyDriverKey()"
                style="background:#00b894; color:#fff; border:none; border-radius:8px;
                       padding:0.5rem 1.6rem; font-weight:700; font-family:'Syne',sans-serif;
                       cursor:pointer; transition:background 0.2s; font-size:0.92rem;">
          Verify Key
        </button>
        <button type="button" id="submitReqBtn" onclick="submitKeyRequest()"
                style="display:none; background:linear-gradient(135deg,#f39c12,#d68910); color:#fff; border:none;
                       border-radius:8px; padding:0.5rem 1.6rem; font-weight:700; font-family:'Syne',sans-serif;
                       cursor:pointer; transition:opacity 0.2s; font-size:0.92rem;">
          Submit Request
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('driverRegisterBtn').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('driverKeyInput').value = '';
    document.getElementById('keyError').style.display = 'none';
    showEnterKeyTab();
    new bootstrap.Modal(document.getElementById('driverKeyModal')).show();
    setTimeout(() => document.getElementById('driverKeyInput').focus(), 400);
  });

  function showEnterKeyTab() {
    document.getElementById('tabEnterKey').style.display = '';
    document.getElementById('tabRequestKey').style.display = 'none';
    document.getElementById('verifyKeyBtn').style.display = '';
    document.getElementById('submitReqBtn').style.display = 'none';
    document.getElementById('dkModalTitle').innerHTML = '<i class="bi bi-key me-2" style="color:#00b894;"></i> Driver Registration Key';
  }

  function showRequestTab() {
    document.getElementById('tabEnterKey').style.display = 'none';
    document.getElementById('tabRequestKey').style.display = '';
    document.getElementById('verifyKeyBtn').style.display = 'none';
    document.getElementById('submitReqBtn').style.display = '';
    document.getElementById('reqError').style.display = 'none';
    document.getElementById('reqSuccess').style.display = 'none';
    document.getElementById('dkModalTitle').innerHTML = '<i class="bi bi-envelope me-2" style="color:#f39c12;"></i> Request a Driver Key';
  }

  function clearKeyError() {
    document.getElementById('keyError').style.display = 'none';
    document.getElementById('driverKeyInput').style.borderColor = '#dde3ea';
  }

  function verifyDriverKey() {
    const input = document.getElementById('driverKeyInput');
    const errEl = document.getElementById('keyError');
    const btn   = document.getElementById('verifyKeyBtn');
    const code  = input.value.trim();

    if (code.length !== 6) {
      errEl.textContent = 'Please enter the full 6-digit key.';
      errEl.style.display = 'block';
      input.style.borderColor = '#e74c3c';
      return;
    }

    btn.textContent = 'Verifying…';
    btn.disabled = true;

    fetch('{{ route("driver-key.validate") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ key_code: code })
    })
    .then(r => r.json())
    .then(data => {
      if (data.valid) {
        window.location.href = '{{ route("driver-register-page") }}';
      } else {
        errEl.textContent = data.message || 'Invalid key. Please contact the manager.';
        errEl.style.display = 'block';
        input.style.borderColor = '#e74c3c';
        btn.textContent = 'Verify Key';
        btn.disabled = false;
      }
    })
    .catch(() => {
      errEl.textContent = 'Connection error. Please try again.';
      errEl.style.display = 'block';
      btn.textContent = 'Verify Key';
      btn.disabled = false;
    });
  }

  function submitKeyRequest() {
    const name    = document.getElementById('reqName').value.trim();
    const email   = document.getElementById('reqEmail').value.trim();
    const contact = document.getElementById('reqContact').value.trim();
    const errEl   = document.getElementById('reqError');
    const sucEl   = document.getElementById('reqSuccess');
    const btn     = document.getElementById('submitReqBtn');

    errEl.style.display = 'none';
    sucEl.style.display = 'none';

    if (!name || !email || !contact) {
      errEl.textContent = 'Please fill in all fields.';
      errEl.style.display = 'block';
      return;
    }

    btn.textContent = 'Submitting…';
    btn.disabled = true;

    fetch('{{ route("driver-key.request") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ name, email, contact })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        sucEl.textContent = data.message;
        sucEl.style.display = 'block';
        document.getElementById('reqName').value = '';
        document.getElementById('reqEmail').value = '';
        document.getElementById('reqContact').value = '';
        btn.textContent = 'Request Sent ✓';
        setTimeout(() => {
          bootstrap.Modal.getInstance(document.getElementById('driverKeyModal')).hide();
          btn.textContent = 'Submit Request';
          btn.disabled = false;
        }, 2500);
      } else {
        errEl.textContent = data.message || 'Failed to submit request. Please try again.';
        errEl.style.display = 'block';
        btn.textContent = 'Submit Request';
        btn.disabled = false;
      }
    })
    .catch(() => {
      errEl.textContent = 'Connection error. Please try again.';
      errEl.style.display = 'block';
      btn.textContent = 'Submit Request';
      btn.disabled = false;
    });
  }
</script>
<script>
  // Scroll reveal
  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => e.target.classList.add('visible'), i * 80);
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });
  revealEls.forEach(el => io.observe(el));
</script>
</body>
</html>