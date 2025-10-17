@extends('web.layouts.app')

@section('title', 'Send Push - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Send Push Notification</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('web.device.send-push', $device) }}" id="push">
                        @csrf
                        
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="body" class="control-label">Body</label>
                                    <input type="text" name="body" id="body" class="form-control" value="{{ old('body') }}" required>
                                    @error('body')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="token" class="control-label">Token</label>
                                    <input type="text" name="token" id="token" class="form-control" value="{{ $device->push_token }}" disabled>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="data" class="control-label">Data (JSON)</label>
                                    <textarea name="data" id="data" class="form-control" rows="8" placeholder='{"key": "value"}'>{{ old('data') }}</textarea>
                                    @error('data')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="far fa-paper-plane"></i> Send Push
                                </button>
                                <a href="{{ route('web.device.show', $device) }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Back to Device
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
.form-control:disabled {
    background-color: #e9ecef;
    opacity: 1;
}
.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
textarea.form-control {
    height: auto;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate JSON in data field
    const dataField = document.getElementById('data');
    if (dataField) {
        dataField.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && value !== '') {
                try {
                    JSON.parse(value);
                    this.style.borderColor = '#28a745';
                } catch (e) {
                    this.style.borderColor = '#dc3545';
                }
            } else {
                this.style.borderColor = '#ced4da';
            }
        });
    }
});
</script>
@endsection
