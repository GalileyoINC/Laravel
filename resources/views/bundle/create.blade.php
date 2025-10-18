@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Create Bundle</h3>
    </div>
    
    <form action="{{ route('bundle.store') }}" method="POST">
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

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="total">Total Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="total" name="total" value="{{ old('total') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Standard</option>
                            <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Premium</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="pay_interval">Pay Interval</label>
                        <select class="form-control" id="pay_interval" name="pay_interval">
                            <option value="1" {{ old('pay_interval') == '1' ? 'selected' : '' }}>Monthly</option>
                            <option value="2" {{ old('pay_interval') == '2' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
            <a href="{{ route('bundle.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
