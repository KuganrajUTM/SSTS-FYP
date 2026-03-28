@extends('layout.main-template')

@section('title', 'Driver Details')

@section('content')
<div class="container mt-5">
    <div class="drivers">Driver Details</div>
    <div class="driver-item">
        <p><span class="driver-name">Name:</span> {{ $driver->name }}</p>
        <p><span class="driver-name">Vehicle Info:</span> {{ $driver->vehicle_info }}</p>
        <p><span class="driver-name">Schedule:</span> {{ $driver->schedule }}</p>
    </div>
    <a href="{{ route('list') }}" class="view-more-details">Back to Driver List</a>
</div>
@endsection

<style>
    .container {
        background-color: #f2f2f2;
        padding: 20px;
        border-radius: 10px;
    }

    .drivers {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    .driver-item {
        background-color: #fff;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .driver-name {
        font-size: 18px;
        font-weight: bold;
    }

    .view-more-details {
        background-color: #007bff;
        color: #fff;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
    }

    .view-more-details:hover {
        background-color: #0056b3;
    }
</style>
