@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Service Settings</h3>
    </div>
    
    <form action="{{ route('service.settings-store') }}" method="POST">
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

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Configure global service settings here.
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="default_tax_rate">Default Tax Rate (%)</label>
                        <input type="number" step="0.01" class="form-control" id="default_tax_rate" name="default_tax_rate" value="{{ old('default_tax_rate', 0) }}">
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="default_currency">Default Currency</label>
                        <select class="form-control" id="default_currency" name="default_currency">
                            <option value="USD" {{ old('default_currency') == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('default_currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="enable_auto_renewal" value="1" {{ old('enable_auto_renewal') ? 'checked' : '' }}>
                            Enable Auto Renewal
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save"></i> Save Settings
            </button>
            <a href="{{ route('service.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
