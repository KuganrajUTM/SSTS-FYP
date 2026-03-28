<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduTransit - School Transportation System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* General Styles */
    body {
      font-family: 'Arial', sans-serif;
      color: #333;
    }
    h1, h2, h3, h5 {
      font-weight: bold;
    }

    /* Hero Section */
    .jumbotron {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.5)), url('/assets/img/school-3596680_1280.jpg') no-repeat center center / cover;
      color: white;
      padding: 100px 0;
    }
    .jumbotron h1 {
      font-size: 3.5rem;
      text-shadow: 2px 2px 4px #000;
    }

    /* Features Section */
    .feature-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 2px solid #f0f0f0;
      border-radius: 10px;
      background: #fff;
    }
    .feature-card:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-color: #007bff;
    }

    /* About Section */
    #about {
      background-color: #fffae6;
    }

    /* Contact Section */
    #contact .contact-link {
      display: inline-flex;
      align-items: center;
      font-size: 1.1rem;
      margin: 10px 0;
      color: #007bff;
      text-decoration: none;
    }
    #contact .contact-link:hover {
      color: #0056b3;
    }
    #contact i {
      margin-right: 10px;
    }

    /* Footer */
    footer {
      background-color: #003366;
      color: white;
    }
    footer a {
      color: #ffcc00;
      text-decoration: none;
    }
    footer a:hover {
      color: white;
    }
      /* FAQ Section Styling */
      #faq .accordion-button {
      background-color: #f8f9fa;
      color: #212529;
      font-weight: bold;
    }

    #faq .accordion-button:focus {
      box-shadow: none;
    }

    #faq .accordion-button:not(.collapsed) {
      background-color: #007bff;
      color: white;
    }

    .navbar-brand img {
      height: 40px;
      width: auto;
      margin-right: 10px;
    }

       /* Contact Us Section */
       #contact-icons i {
      font-size: 1.8rem;
      margin: 0 15px;
      color: white;
      transition: transform 0.2s ease-in-out;

    }

    #contact-icons i:hover {
      transform: scale(1.2);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="">
      <img src="assets/img/photo_2024-10-22_11-35-22-Photoroom.png" alt="Logo">
    </a>
    <a class="navbar-brand fw-bold" href="#">EduTransit</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
      </ul>
      <a href="{{ route('register') }}" class="btn btn-warning ms-3 fw-bold">Register</a>
      <a href="{{ route('login') }}" class="btn btn-outline-light ms-2">Login</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="jumbotron text-center">
  <div class="container">
    <h1 class="display-4">Reliable School Transportation Made Easy</h1>
    <p class="lead">Streamline school bus management, improve safety, and keep parents informed effortlessly.</p>
    <a href="#features" class="btn btn-lg btn-warning text-dark fw-bold">Learn More</a>
  </div>
</div>

<!-- About Section -->
<section id="about" class="py-5">
  <div class="container text-center">
    <h2 class="mb-4">About EduTransit</h2>
    <p class="lead">EduTransit is a reliable platform designed to simplify school transportation management. From tracking bus routes to ensuring driver verification and notifying parents about their child's whereabouts, we streamline the entire process to ensure safety and efficiency.</p>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">Key Features</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card feature-card text-center p-4">
          <i class="bi bi-wallet2 display-4 text-warning"></i>
          <h5 class="mt-3">Efficient Fee Collection</h5>
          <p>Simplify and automate school bus fee payments with secure methods.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card feature-card text-center p-4">
          <i class="bi bi-shield-check display-4 text-warning"></i>
          <h5 class="mt-3">Driver Verification</h5>
          <p>Ensure every driver is verified for the safety of all students.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card feature-card text-center p-4">
          <i class="bi bi-chat-dots display-4 text-warning"></i>
          <h5 class="mt-3">Real-Time Notifications</h5>
          <p>Keep parents informed with timely updates and alerts.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-5">
  <div class="container">
    <h2 class="text-center mb-4">Frequently Asked Questions</h2>
    <div class="accordion" id="faqAccordion">
      <!-- Question 1 -->
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#question1">
            How do I register for the service?
          </button>
        </h2>
        <div id="question1" class="accordion-collapse collapse show">
          <div class="accordion-body">
            You can register by clicking the "Register" button on the homepage and following the instructions.
          </div>
        </div>
      </div>
      <!-- Question 2 -->
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#question2">
            Are the drivers verified?
          </button>
        </h2>
        <div id="question2" class="accordion-collapse collapse">
          <div class="accordion-body">
            Yes, all drivers undergo a strict verification process to ensure the safety of students.
          </div>
        </div>
      </div>
      <!-- Question 3 -->
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#question3">
            How can I contact support?
          </button>
        </h2>
        <div id="question3" class="accordion-collapse collapse">
          <div class="accordion-body">
            You can reach us via Facebook, WhatsApp, or email provided in the Contact Us section.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="text-center py-3">
  <div class="container">
    <p>&copy; 2024 EduTransit. All Rights Reserved.</p>
    <div class="container text-center mt-2">
      <div id="contact-icons">
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-whatsapp"></i></a>
        <a href="mailto:info@edutransit.com"><i class="bi bi-envelope"></i></a>
      </div>
    </div>
  

  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>