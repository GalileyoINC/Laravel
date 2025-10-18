@extends('layouts.app')

@section('title', 'View Staff - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Staff: {{ $staff->username }}</h1>
        <a href="{{ route('staff.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Staff
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>ID:</strong> {{ $staff->id }}</p>
                    <p><strong>Username:</strong> {{ $staff->username }}</p>
                    <p><strong>Email:</strong> {{ $staff->email }}</p>
                    <p><strong>Role:</strong> 
                        @if($staff->role === 1)
                            <span class="badge badge-danger">Super Admin</span>
                        @else
                            <span class="badge badge-info">Admin</span>
                        @endif
                    </p>
                    <p><strong>Status:</strong> 
                        @if($staff->status === 1)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-warning">Inactive</span>
                        @endif
                    </p>
                    <p><strong>Is Super Login:</strong> {{ $staff->is_superlogin ? 'Yes' : 'No' }}</p>
                    <p><strong>Created At:</strong> {{ $staff->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>Updated At:</strong> {{ $staff->updated_at ? $staff->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-gray-300"></i>
                    </div>
                    <h5>{{ $staff->username }}</h5>
                    <p class="text-muted">{{ $staff->email }}</p>
                </div>
            </div>
            <hr>
            <a href="{{ route('staff.edit', $staff) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Staff
            </a>
        </div>
    </div>
</div>

<style>
.text-gray-300 {
    color: #dddfeb !important;
}
.badge-danger {
    background-color: #e74a3b;
}
.badge-info {
    background-color: #36b9cc;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-warning {
    background-color: #f6c23e;
}
</style>
@endsection
