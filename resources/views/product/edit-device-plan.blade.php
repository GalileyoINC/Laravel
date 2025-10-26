@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Edit Device Plan: {{ $plan->name }}</h3>
    </div>
    
    <form action="{{ route('product.update-plan', $plan) }}" method="POST">
        @csrf
        @method('POST')
        
        <div class="box-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $plan->name) }}" required>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="price">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $plan->price) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $plan->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                                Is Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save"></i> Save
            </button>
            <a href="{{ route('product.plan') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection

