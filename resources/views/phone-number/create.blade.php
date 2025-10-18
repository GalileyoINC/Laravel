@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Create Phone Number</h3>
    </div>
    
    <form action="{{ route('phone-number.store') }}" method="POST">
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
                        <label for="number">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="number" name="number" value="{{ old('number') }}" required>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="id_user">User</label>
                        <input type="number" class="form-control" id="id_user" name="id_user" value="{{ old('id_user') }}">
                        <small class="text-muted">Enter User ID</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Mobile</option>
                            <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Landline</option>
                            <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Satellite</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="imei">IMEI</label>
                        <input type="text" class="form-control" id="imei" name="imei" value="{{ old('imei') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_valid" value="1" {{ old('is_valid') ? 'checked' : '' }}>
                            Is Valid
                        </label>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}>
                            Is Primary
                        </label>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
            <a href="{{ route('phone-number.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
