@extends('layout.main-template')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Detail</title>
    <style>
        .container {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .driver-detail {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .driver-detail h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .driver-detail label {
            display: block;
            margin-bottom: 5px;
        }
        .driver-detail input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        .driver-detail button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="driver-detail">
        <h1>Driver Detail</h1>

       @if ($driver)
    <p><strong>Name:</strong> {{ $driver->user->name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $driver->user->email ?? 'N/A' }}</p>
        
    @if ($child)
        <!-- Form to add the driver to a child -->
        <form action="{{ route('child.addDriver', ['child_id' => $child->id, 'driver_id' => $driver->id]) }}" method="POST">
            @csrf
            <button type="submit">Add Driver</button>
        </form>
    @else
        <p>No child selected. Please select a child first.</p>
    @endif
@else
    <p>Driver not found.</p>
@endif
    </div>
</div>
</body>
</html>
