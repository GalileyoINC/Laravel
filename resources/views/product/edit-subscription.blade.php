@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Edit Subscription: {{ $service->name }}</h3>
    </div>
    
    <form action="{{ route('product.update-subscription', $service) }}" method="POST">
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
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="special_price">Special Price</label>
                        <input type="number" step="0.01" class="form-control" id="special_price" name="special_price" value="{{ old('special_price', $service->special_price) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_special_price" value="1" {{ old('is_special_price', $service->is_special_price) ? 'checked' : '' }}>
                                Is Special Price
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
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
            <a href="{{ route('product.subscription') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection

