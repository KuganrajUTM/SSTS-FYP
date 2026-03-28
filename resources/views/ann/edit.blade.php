@extends('layout.main-template')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/np1u0ohyukhddl9vw7irelnoxtj7eduhx7ll02gfa76ha8ll/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content', // Target textarea with id="content"
            menubar: false, // Disable the top menu bar
            plugins: 'advlist autolink lists link charmap preview anchor', // Add useful plugins
            toolbar: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | preview', // Add bold, italic, underline
            height: 300, // Set editor height
            branding: false, // Remove branding
            entities: '160,nbsp', // Convert &nbsp; back to spaces
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave(); // Save content back to the textarea
                });
            }
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Announcement</h2>
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('ann.update', $announcement->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $announcement->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" rows="5" class="form-control" required>{{ $announcement->content }}</textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                    <span class="text-danger">*</span> <span class="text-danger">required field</span>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
