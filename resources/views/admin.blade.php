@extends('layout.main-template')

@section('content')
<style>
    :root {
        --emerald: #00b894; --emerald-dk: #007a63; --emerald-lt: #e6f9f5;
        --navy: #0a1628; --slate: #4a5568; --white: #ffffff; --bg: #f5f7fa;
        --border: rgba(0,184,148,0.25);
    }
    .admin-container { background: var(--bg); padding: 2rem 0; min-height: 100vh; }
    .admin-card { background: white; border-radius: 20px; border: 1.5px solid var(--border); box-shadow: 0 20px 60px rgba(0,184,148,0.15); margin: 2rem auto; max-width: 1200px; }
    .category-header { background: linear-gradient(135deg, var(--emerald), var(--emerald-dk)); color: white; padding: 1.5rem; border-radius: 16px 16px 0 0; font-weight: 700; font-size: 1.3rem; }
    .table-custom th, .table-custom td { border: 1px solid #e5e7eb; padding: 1rem; text-align: left; }
    .table-custom { width: 100%; margin-bottom: 0; }
    .view-details { 
        background: linear-gradient(135deg, var(--emerald), var(--emerald-dk)); 
        color: white; padding: 0.5rem 1.5rem; border-radius: 25px; 
        text-decoration: none; font-weight: 600; transition: all 0.3s ease;
        display: inline-block; font-size: 0.9rem;
    }
    .view-details:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,184,148,0.4); color: white !important; }
    .no-data { text-align: center; color: var(--slate); font-style: italic; padding: 2rem; }
    @media (max-width: 768px) { .table-custom th, .table-custom td { padding: 0.75rem 0.5rem; font-size: 0.9rem; } }
</style>

<div class="admin-container">
    <div class="container">
        <h1 class="text-center mb-4" style="color: var(--navy); font-weight: 800; font-size: 2.2rem;">👥 Manage Users</h1>
        
        <div class="admin-card">
            <div class="category-header">
                <i class="fas fa-users me-3"></i>Users Overview
            </div>
            <div class="p-4">
                {{-- Parents --}}
                <div class="mb-5">
                    <h3 style="color: var(--emerald); font-weight: 700; margin-bottom: 1.5rem;">
                        👨‍👩‍👧‍👦 Parents ({{ $parents->count() ?? 0 }}) 
                    </h3>
                    @if($parents->count() > 0)
                        <table class="table-custom">
                            <thead>
                                <tr style="background: var(--emerald-lt);">
                                    <th>#</th><th>Name</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parents as $key => $parent)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $parent->name }}</td>
                                        <td><a href="{{ route('user.details', $parent->id) }}" class="view-details">View Details</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">No Parents Found</div>
                    @endif
                </div>

                {{-- Drivers --}}
                <div>
                    <h3 style="color: var(--emerald); font-weight: 700; margin-bottom: 1.5rem;">
                        🚗 Drivers ({{ $drivers->count() ?? 0 }}) 
                    </h3>
                    @if($drivers->count() > 0)
                        <table class="table-custom">
                            <thead>
                                <tr style="background: var(--emerald-lt);">
                                    <th>#</th><th>Name</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drivers as $key => $driver)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $driver->name }}</td>
                                        <td><a href="{{ route('user.details', $driver->id) }}" class="view-details">View Details</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">No Drivers Found</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection