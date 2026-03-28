<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-image: url('assets/img/malaysia.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card-header h3 {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .card-body {
            padding: 2rem;
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

        a {
            color: #007bff;
        }

        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Custom styles for file input */
        .custom-file-input {
            cursor: pointer;
        }

        .custom-file-label::after {
            content: "Browse";
            background-color: #007bff;
            color: white;
            border-radius: 0 5px 5px 0;
        }

        .custom-file-label {
            border-radius: 5px;
        }

        .text-danger {
            font-size: 0.9rem;
        }
    </style>

</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs mt-3" id="registrationTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="parent-tab" data-toggle="tab" href="#parent" role="tab" aria-controls="parent" aria-selected="true">Parent</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="driver-tab" data-toggle="tab" href="#driver" role="tab" aria-controls="driver" aria-selected="false">Driver</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="registrationTabsContent">
                            <!-- Parent Registration Form -->
                            <div class="tab-pane fade show active" id="parent" role="tabpanel" aria-labelledby="parent-tab">
                                <h3 class="text-center" style="text-decoration:underline;"><strong>Parent Registration</strong></h3>
                                <form class="mt-2" action="{{ route('user-register') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="fullname">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter Full Name" required>
                                        @error('fullname')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                                        @error('username')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                        @error('email')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                        @error('password')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter location" required>
                                        @error('location')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                                    <p class="text-center mt-3">Have an account? <a href="{{ route('login') }}">Go to login</a></p>
                                </form>
                            </div>

                            <!-- Driver Registration Form -->
                            <div class="tab-pane fade" id="driver" role="tabpanel" aria-labelledby="driver-tab">
                                <h3 class="text-center" style="text-decoration:underline;"><strong>Driver Registration</strong></h3>
                                <form action="{{ route('driver-register') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="role" value="D">
                                    <div class="form-group">
                                        <label for="fullname">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter Name" required>
                                        @error('fullname')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                                        @error('username')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                        @error('email')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                        @error('password')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="vrn">Vehicle Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="vrn" name="vrn" placeholder="Enter Vehicle Details" required>
                                        @error('vrn')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="license">Upload License (PDF only) <span class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="license" name="license" accept="application/pdf" required>
                                            <label class="custom-file-label" for="license">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="spad">Upload SPAD Document (PDF only) <span class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="spad" name="spad" accept="application/pdf" required>
                                            <label class="custom-file-label" for="spad">Choose file</label>
                                            @error('spad')
                                                <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                                    <p class="text-center mt-3">Have an account? <a href="{{ route('login') }}">Go to login</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update the file input label on file selection
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    </script>
</body>
</html>