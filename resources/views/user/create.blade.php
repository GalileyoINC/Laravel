@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Create User</h3>
    </div>
    
    <form action="{{ route('user.store') }}" method="POST" id="user-form">
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
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone_type">Phone Type</label>
                        <select class="form-control" id="phone_type" name="phone_type">
                            <option value="">Select Type</option>
                            <option value="1" {{ old('phone_type') == '1' ? 'selected' : '' }}>Mobile</option>
                            <option value="2" {{ old('phone_type') == '2' ? 'selected' : '' }}>Landline</option>
                            <option value="3" {{ old('phone_type') == '3' ? 'selected' : '' }}>Satellite</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" disabled>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="country">Country <span class="text-danger">*</span></label>
                        <select class="form-control" id="country" name="country" required>
                            <option value="">Select Country</option>
                            <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="timezone">Timezone</label>
                        <select class="form-control" id="timezone" name="timezone">
                            <option value="">Select Timezone</option>
                            <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            <option value="America/Chicago" {{ old('timezone') == 'America/Chicago' ? 'selected' : '' }}>America/Chicago</option>
                            <option value="America/Denver" {{ old('timezone') == 'America/Denver' ? 'selected' : '' }}>America/Denver</option>
                            <option value="America/Los_Angeles" {{ old('timezone') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="zip">ZIP Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="name_as_referral">Name as Referral</label>
                        <input type="text" class="form-control" id="name_as_referral" name="name_as_referral" value="{{ old('name_as_referral') }}">
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">...</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_influencer" value="1" {{ old('is_influencer') ? 'checked' : '' }}>
                            Is Influencer
                        </label>
                    </div>
                    
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_test" value="1" {{ old('is_test') ? 'checked' : '' }}>
                            Is Test
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save"></i> Save
            </button>
            <a href="{{ route('user.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
