@extends('layout.main-template')

@section('content')
<div class="container mt-5">
    <!-- Page Title -->
    <h2 class="text-justify mb-4">Announcements</h2>
    
    <!-- Add Announcement Button -->
    <div class="mb-3 text-end">
        <a href="{{ route('ann.create') }}" class="btn btn-primary">Add Announcement</a>
    </div>

    @if($userRole == 'P')
    <!-- Parent Announcements Table -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Your Announcements</strong>
            
            <!-- Filter Form -->
            <form method="GET" action="{{ route('ann') }}" class="d-flex align-items-center">
                <div class="me-2">
                    <select name="filter_option" class="form-select form-select-sm" id="filter_option">
                        <option value="">View all</option>
                        <option value="absence" {{ request('filter_option') == 'absence' ? 'selected' : '' }}>Absence</option>
                        <option value="delay" {{ request('filter_option') == 'delay' ? 'selected' : '' }}>Delay</option>
                    </select>
                </div>
                <div class="me-2">
                    <input type="date" name="date" class="form-control form-control-sm" placeholder="Filter by Date" value="{{ request('date') }}" id="date">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('ann') }}" class="btn btn-secondary btn-sm ms-2">Reset</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 35%;">Content</th>
                            <th style="width: 15%;">Created At</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parentAnnouncements as $index => $announcement)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at }}</td>
                                <td>
                                    <a href="{{ route('ann.edit', $announcement->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('ann.destroy', $announcement->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Driver Announcements Table -->
    <div class="card">
        <div class="card-header">
            <strong>Driver Announcements</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 25%;">Title</th>
                            <th style="width: 50%;">Content</th>
                            <th style="width: 20%;">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($driverAnnouncements as $index => $announcement)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    @elseif($userRole == 'D')
    
    <!-- Driver's Own Announcements Table -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Your Announcements</strong>
            
            <!-- Filter Form -->
            <form method="GET" action="{{ route('ann') }}" class="d-flex align-items-center">
                <div class="me-2">
                    <select name="filter_option" class="form-select form-select-sm" id="filter_option">
                        <option value="">View all</option>
                        <option value="absence" {{ request('filter_option') == 'absence' ? 'selected' : '' }}>Absence</option>
                        <option value="delay" {{ request('filter_option') == 'delay' ? 'selected' : '' }}>Delay</option>
                    </select>
                </div>
                <div class="me-2">
                    <input type="date" name="date" class="form-control form-control-sm" placeholder="Filter by Date" value="{{ request('date') }}" id="date">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('ann') }}" class="btn btn-secondary btn-sm ms-2">Reset</a>
            </form>
        </div>
        <div class="card-body">
            <!-- Add Announcement Button -->
            <div class="mb-3 text-end">
                <a href="{{ route('ann.create') }}" class="btn btn-primary">Add Announcement</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 35%;">Content</th>
                            <th style="width: 15%;">Created At</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($driverAnnouncements as $index => $announcement)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at }}</td>
                                <td>
                                    <a href="{{ route('ann.edit', $announcement->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('ann.destroy', $announcement->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Parent Announcements Table -->
    <div class="card">
        <div class="card-header">
            <strong>Parent Announcements</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 7%;">No.</th>
                            <th style="width: 20%;">Parent Name</th>
                            <th style="width: 15%;">Title</th>
                            <th style="width: 50%;">Content</th>
                            <th style="width: 20%;">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parentAnnouncements as $index => $announcement)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $announcement->user->name }}</td>
                                <td>{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif


@endsection
