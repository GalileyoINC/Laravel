@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\User\User::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\User\User::where('status', 1)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Contacts
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Communication\Contact::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Bundles
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Finance\Bundle::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('device.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-mobile-alt"></i> View Devices
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('email-pool.index') }}" class="btn btn-success btn-block">
                                <i class="fas fa-envelope"></i> Email Pool
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('credit-card.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-credit-card"></i> Credit Cards
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('api-log.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-list"></i> API Logs
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('user.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('phone-number.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-phone"></i> Phone Numbers
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentUsers = \App\Models\User\User::latest()->take(5)->get();
                                    $recentContacts = \App\Models\Communication\Contact::latest()->take(5)->get();
                                    $recentActivity = collect();
                                    
                                    foreach($recentUsers as $user) {
                                        $recentActivity->push([
                                            'type' => 'User',
                                            'description' => 'New user: ' . $user->first_name . ' ' . $user->last_name,
                                            'date' => $user->created_at
                                        ]);
                                    }
                                    
                                    foreach($recentContacts as $contact) {
                                        $recentActivity->push([
                                            'type' => 'Contact',
                                            'description' => 'New contact: ' . $contact->name,
                                            'date' => $contact->created_at
                                        ]);
                                    }
                                    
                                    $recentActivity = $recentActivity->sortByDesc('date')->take(5);
                                @endphp
                                
                                @forelse($recentActivity as $activity)
                                    <tr>
                                        <td>
                                            <span class="badge badge-{{ $activity['type'] === 'User' ? 'primary' : 'info' }}">
                                                {{ $activity['type'] }}
                                            </span>
                                        </td>
                                        <td>{{ $activity['description'] }}</td>
                                        <td>{{ $activity['date']->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No recent activity</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Laravel Version:</strong><br>
                            {{ app()->version() }}
                        </div>
                        <div class="col-md-3">
                            <strong>PHP Version:</strong><br>
                            {{ PHP_VERSION }}
                        </div>
                        <div class="col-md-3">
                            <strong>Environment:</strong><br>
                            {{ app()->environment() }}
                        </div>
                        <div class="col-md-3">
                            <strong>Last Login:</strong><br>
                            {{ Auth::user()->updated_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-xs {
    font-size: 0.7rem;
}
.font-weight-bold {
    font-weight: 700 !important;
}
.text-primary {
    color: #4e73df !important;
}
.text-success {
    color: #1cc88a !important;
}
.text-info {
    color: #36b9cc !important;
}
.text-warning {
    color: #f6c23e !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection
