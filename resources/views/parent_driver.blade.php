<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Choose Registration Type</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-image: url('https://source.unsplash.com/1600x900/?transportation,city');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
      font-family: Arial, sans-serif;
      position: relative;
      overflow: hidden;
    }
    
    /* Dark overlay */
    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: -1;
    }
    
    .container {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    h2 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 30px;
      color: #fff;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .btn {
      font-size: 1.2rem;
      padding: 15px 30px;
      border-radius: 8px;
      font-weight: bold;
      width: 100%;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s, box-shadow 0.3s;
      text-decoration: none;
    }

    .btn i {
      margin-right: 10px;
      font-size: 1.5rem;
    }

    .btn-parent {
      background: linear-gradient(45deg, #ff9a9e, #fad0c4);
      border: none;
    }

    .btn-parent:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(255, 154, 158, 0.3);
    }

    .btn-driver {
      background: linear-gradient(45deg, #4CAF50, #81C784);
      border: none;
    }

    .btn-driver:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(76, 175, 80, 0.3);
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Select Your Preferred Registration</h2>
    <div class="row mt-5">
      <div class="col-md-6 mb-3">
        <a href="#" class="btn btn-parent"><i class="fas fa-user"></i> Parent</a>
      </div>
      <div class="col-md-6 mb-3">
        <a href="{{ route('register')  }}" class="btn btn-driver"><i class="fas fa-car"></i> Driver</a>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome Icons -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
