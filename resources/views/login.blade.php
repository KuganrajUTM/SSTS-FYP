<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('assets/img/malaysia.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
            background-color: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        .login-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .login-form h2 {
            font-weight: bold;
            font-size: 1.75rem;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .text-muted:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        .forgot-password {
            text-align: left;
            display: block;
            color: #007bff;
            margin-top: -15px;
            margin-bottom: 15px;
        }

        .login-form a {
            color: #007bff;
        }

        .alert {
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-form">
                  <!-- Error Alert -->
                @if (session('failed') || $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <span>
                            @if (session('failed'))
                                {{ session('failed') }}
                            @endif
                            @foreach ($errors->all() as $error)
                                {{ $error }}{{ !$loop->last ? ' | ' : '' }}
                            @endforeach
                        </span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                    <h2 class="text-center mb-4">Login</h2>
                    <form action="{{ route('user-login') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            @error('password')
                                <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <a href="{{ route('forgot-password') }}" class="forgot-password mt-1">Forgot Password?</a>
                        <button type="submit" class="btn btn-primary btn-block mt-4">Login</button>
                    </form>
                    <p class="text-center mt-3">Don't have an account? <a href="{{ route('register') }}" class="text-muted">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
