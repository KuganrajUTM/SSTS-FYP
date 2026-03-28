@extends('layout.main-template')

@section('content')
    <div class="content">
        <h2>Users</h2>

        {{-- Main Table --}}
        <table class="table" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px;">Category</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Details</th>
                </tr>
            </thead>
            <tbody>
                {{-- Parent Table --}}
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><h3>Parents</h3></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">
                        <table class="table" style="border-collapse: collapse; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd; padding: 8px;">No</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">User Name</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($parents->count() > 0)
                                    @foreach($parents as $key => $parent)
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $key + 1 }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $parent->name }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">
                                                <a href="{{ route('user.details', $parent->id) }}" class="view-details">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align: center; border: 1px solid #ddd; padding: 8px;">No Parents Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>

                {{-- Driver Table --}}
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><h3>Drivers</h3></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">
                        <table class="table" style="border-collapse: collapse; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd; padding: 8px;">No</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">User Name</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($drivers->count() > 0)
                                    @foreach($drivers as $key => $driver)
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $key + 1 }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $driver->name }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">
                                                <a href="{{ route('user.details', $driver->id) }}" class="view-details">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align: center; border: 1px solid #ddd; padding: 8px;">No Drivers Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
