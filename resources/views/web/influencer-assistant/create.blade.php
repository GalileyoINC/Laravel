@extends('web.layouts.app')

@section('title', 'Create Influencer Assistant - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Create Influencer Assistant</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('web.influencer-assistant.store') }}" id="influencer-assistant">
                        @csrf
                        
                        <div class="form-group">
                            <label for="id_influencer" class="control-label">Influencer</label>
                            <select name="id_influencer" id="id_influencer" class="form-control" required>
                                <option value="">Select Influencer</option>
                                @foreach($influencers as $id => $name)
                                    <option value="{{ $id }}" {{ old('id_influencer') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_influencer')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="id_assistant" class="control-label">Assistant</label>
                            <select name="id_assistant" id="id_assistant" class="form-control" required>
                                <option value="">Select Assistant</option>
                                @foreach($assistants as $id => $name)
                                    <option value="{{ $id }}" {{ old('id_assistant') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_assistant')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="far fa-save"></i> Save
                                </button>
                                <a href="{{ route('web.influencer-assistant.index') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Back to Assistants
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
