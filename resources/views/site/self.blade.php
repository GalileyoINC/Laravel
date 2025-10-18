@extends('layouts.app')

@section('title', 'Profile - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-4 text-gray-800">Profile Settings</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('site.self') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           id="username" 
                                           name="username" 
                                           value="{{ old('username', $staff->username ?? '') }}" 
                                           required>
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $staff->email ?? '') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" 
                                           class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name', $staff->first_name ?? '') }}">
                                    @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" 
                                           class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name', $staff->last_name ?? '') }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                            <a href="{{ route('site.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-5x text-gray-300"></i>
                        </div>
                        <h5>{{ $staff->first_name ?? '' }} {{ $staff->last_name ?? '' }}</h5>
                        <p class="text-muted">{{ $staff->username ?? '' }}</p>
                        <p class="text-muted">{{ $staff->email ?? '' }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-2">
                        <strong>Role:</strong> 
                        <span class="badge badge-{{ $staff->role === 1 ? 'danger' : 'info' }}">
                            {{ $staff->role === 1 ? 'Super Admin' : 'Admin' }}
                        </span>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Status:</strong> 
                        <span class="badge badge-success">Active</span>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Last Login:</strong><br>
                        <small class="text-muted">{{ $staff->updated_at->format('M d, Y H:i') }}</small>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Member Since:</strong><br>
                        <small class="text-muted">{{ $staff->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
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
</style>
@endsection
