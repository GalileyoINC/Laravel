@extends('layouts.login')

@section('content')
<form method="POST" action="{{ route('site.login.submit') }}">
    @csrf
    
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
            </div>
            <input type="text" 
                   class="form-control @error('username') is-invalid @enderror" 
                   name="username" 
                   value="{{ old('username') }}" 
                   placeholder="Username" 
                   required 
                   autofocus>
        </div>
        @error('username')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
            </div>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" 
                   placeholder="Password" 
                   required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label" for="remember">
                Remember Me
            </label>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-login">
            <i class="fas fa-sign-in-alt"></i> Sign In
        </button>
    </div>
</form>

<div class="text-center mt-3">
    <small class="text-muted">
        Welcome to {{ config('app.name') }} Admin Panel
    </small>
</div>
@endsection
