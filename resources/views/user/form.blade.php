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
        <div class="col-lg-4">
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="form-group">
                <label for="first_name">First Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="form-group">
                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $user->country ?? '') }}">
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state ?? '') }}">
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="form-group">
                <label for="zip">ZIP</label>
                <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip', $user->zip ?? '') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_influencer" value="1" {{ old('is_influencer', $user->is_influencer ?? false) ? 'checked' : '' }}>
                    Is Influencer
                </label>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_test" value="1" {{ old('is_test', $user->is_test ?? false) ? 'checked' : '' }}>
                    Is Test
                </label>
            </div>
        </div>
    </div>
</div>
