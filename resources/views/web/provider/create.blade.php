@extends('web.layouts.app')

@section('title', 'Create Provider - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Create Provider</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('web.provider.store') }}" id="provider">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required maxlength="255">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" maxlength="255">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="country" class="control-label">Country</label>
                            <input type="text" name="country" id="country" class="form-control" value="{{ old('country') }}" maxlength="2">
                            @error('country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_satellite" id="is_satellite" class="form-check-input" value="1" {{ old('is_satellite') ? 'checked' : '' }}>
                                <label for="is_satellite" class="form-check-label">Is Satellite</label>
                            </div>
                            @error('is_satellite')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="far fa-save"></i> Save
                                </button>
                                <a href="{{ route('web.provider.index') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Back to Providers
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
.panel-body {
    padding: 15px;
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: inline-block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
.form-check {
    position: relative;
    display: block;
    padding-left: 1.25rem;
}
.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: -1.25rem;
}
.form-check-label {
    margin-bottom: 0;
}
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    text-decoration: none;
    margin-right: 0.5rem;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn:hover {
    text-decoration: none;
}
.btn-success:hover {
    color: #fff;
    background-color: #218838;
    border-color: #1e7e34;
}
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
}
.text-danger {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>
@endsection
