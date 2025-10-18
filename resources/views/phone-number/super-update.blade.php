@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Super Update Phone Number #{{ $phoneNumber->id }}</h3>
    </div>
    
    <form action="{{ route('phone-number.super-update-post', $phoneNumber) }}" method="POST">
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
                <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> This is an advanced update form with additional options.
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="number">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="number" name="number" value="{{ old('number', $phoneNumber->number) }}" required>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="imei">IMEI</label>
                        <input type="text" class="form-control" id="imei" name="imei" value="{{ old('imei', $phoneNumber->imei) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="sim_number">SIM Number</label>
                        <input type="text" class="form-control" id="sim_number" name="sim_number" value="{{ old('sim_number', $phoneNumber->sim_number) }}">
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="device_model">Device Model</label>
                        <input type="text" class="form-control" id="device_model" name="device_model" value="{{ old('device_model', $phoneNumber->device_model) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="notes">Admin Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $phoneNumber->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_valid" value="1" {{ old('is_valid', $phoneNumber->is_valid) ? 'checked' : '' }}>
                            Is Valid
                        </label>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_primary" value="1" {{ old('is_primary', $phoneNumber->is_primary) ? 'checked' : '' }}>
                            Is Primary
                        </label>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $phoneNumber->is_active) ? 'checked' : '' }}>
                            Is Active
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save"></i> Save
            </button>
            <a href="{{ route('phone-number.show', $phoneNumber) }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
