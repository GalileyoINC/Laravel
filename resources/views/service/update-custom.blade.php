@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Update Custom Service #{{ $service->id }}</h3>
    </div>
    
    <form action="{{ route('service.update-custom-store', $service) }}" method="POST">
        @csrf
        
        <div class="box-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> This is a custom service with special configuration options.
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="price">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $service->price) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="custom_config">Custom Configuration (JSON)</label>
                        <textarea class="form-control" id="custom_config" name="custom_config" rows="5">{{ old('custom_config', $service->custom_config ?? '{}') }}</textarea>
                        <small class="text-muted">Enter custom configuration in JSON format</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save"></i> Save
            </button>
            <a href="{{ route('service.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
