@extends('layouts.app')

@section('title', 'Create Promocode - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Create Promocode</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('promocode.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="promocode" method="POST" action="{{ route('promocode.store') }}" class="JS__load_in_modal">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select name="type" id="promocode-type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="discount" {{ old('type') == 'discount' ? 'selected' : '' }}>Discount</option>
                                        <option value="trial" {{ old('type') == 'trial' ? 'selected' : '' }}>Trial</option>
                                        <option value="influencer" {{ old('type') == 'influencer' ? 'selected' : '' }}>Influencer</option>
                                        <option value="test" {{ old('type') == 'test' ? 'selected' : '' }}>Test</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="text">Text</label>
                                    <input type="text" name="text" id="text" class="form-control" value="{{ old('text') }}" required>
                                    @error('text')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="discount">Discount</label>
                                    <input type="number" name="discount" id="promocode-discount" class="form-control" value="{{ old('discount', 0) }}" min="0" max="100" step="0.01">
                                    @error('discount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-1 text-center">
                                <b>- OR -</b>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="trial_period">Trial Period</label>
                                    <input type="number" name="trial_period" id="promocode-trial_period" class="form-control" value="{{ old('trial_period', 0) }}" min="0">
                                    @error('trial_period')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="active_from">Active From</label>
                                    <input type="date" name="active_from" id="active_from" class="form-control" value="{{ old('active_from', now()->format('Y-m-d')) }}" required>
                                    @error('active_from')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="active_to">Active To</label>
                                    <input type="date" name="active_to" id="active_to" class="form-control" value="{{ old('active_to', now()->addMonth()->format('Y-m-d')) }}" required>
                                    @error('active_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">Is Active</label>
                                    </div>
                                    @error('is_active')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="show_on_frontend" id="show_on_frontend" class="form-check-input" value="1" {{ old('show_on_frontend') ? 'checked' : '' }}>
                                        <label for="show_on_frontend" class="form-check-label">Show on Frontend</label>
                                    </div>
                                    @error('show_on_frontend')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <button type="submit" form="promocode" class="btn btn-success">
                        <i class="far fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('promocode-type');
    var discountInput = document.getElementById('promocode-discount');
    var trialPeriodInput = document.getElementById('promocode-trial_period');
    
    function readonlyManagement() {
        var type = select.value;
        var trialPeriod = trialPeriodInput.value;
        
        if (type === 'influencer') {
            discountInput.value = 100;
            discountInput.readOnly = true;
        } else if (type === 'test') {
            discountInput.value = 0;
            discountInput.readOnly = true;
        } else if (trialPeriod > 0) {
            discountInput.value = 0;
            discountInput.readOnly = true;
        } else {
            discountInput.readOnly = false;
        }
    }
    
    select.addEventListener('change', readonlyManagement);
    trialPeriodInput.addEventListener('change', readonlyManagement);
    
    // Initial call
    readonlyManagement();
});
</script>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.panel-heading-actions {
    display: flex;
    gap: 10px;
}
.panel-body {
    padding: 15px;
}
.panel-footer {
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    padding: 10px 15px;
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
.form-control[readonly] {
    background-color: #e9ecef;
    opacity: 1;
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
.text-danger {
    color: #dc3545;
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
</style>
@endsection
