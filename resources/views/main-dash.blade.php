@extends('layout.main-template')

@section('content')
    <div class="container mt-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;  background-size: cover; background-position: center;">
        <div class="text-center p-5 border border-primary rounded shadow-lg" style="border-width: 4px; border-color: #007bff; background: rgba(255, 255, 255, 0.85); transition: transform 0.3s ease-in-out;">
            <h1 class="display-4 mt-5 text-primary bounce-on-hover">Welcome, {{$userName}}!</h1>
            <p class="lead mt-3 text-dark fade-in">"The journey of a thousand miles begins with a single step."</p>
            <hr class="my-4" style="border-color: #007bff;">
            <p class="text-dark">We are glad to have you here. Let's make today productive!</p>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        @keyframes bounce {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }

        .bounce-on-hover:hover {
            animation: bounce 0.5s ease-in-out;
        }

        .text-center p {
            font-size: 1.2rem;
            color: #333;
        }

        .text-center h1 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #003366;
        }

        .text-center {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Hover effect for the main container */
        .text-center:hover {
            transform: scale(1.05);
        }
    </style>
@endsection
