@extends('layout.main-template')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New Announcement</h2>
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('addann') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                    <label for="title" class="form-label">
                            Title <span class="text-danger">*</span>
                    </label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                    <label for="title" class="form-label">
                            Content <span class="text-danger">*</span>
                    </label>
                        <!-- Textarea for TinyMCE -->
                        <textarea name="content" id="content" rows="5" class="form-control" placeholder="Enter content" required></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                    <span class="text-danger">*</span> <span class="text-danger">required field</span>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
