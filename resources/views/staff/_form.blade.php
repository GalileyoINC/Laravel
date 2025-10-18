<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" {{ !Auth::user()->can('action.staff.edit') ? 'disabled' : '' }}>
                <option value="">...</option>
                <option value="1" {{ old('status', $staff->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $staff->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" {{ !Auth::user()->can('action.staff.edit') || (isset($staff) && $staff->isAdmin()) ? 'disabled' : '' }}>
                <option value="">...</option>
                <option value="1" {{ old('role', $staff->role ?? '') == '1' ? 'selected' : '' }}>Super Admin</option>
                <option value="10" {{ old('role', $staff->role ?? '') == '10' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" 
                   class="form-control @error('username') is-invalid @enderror" 
                   id="username" 
                   name="username" 
                   value="{{ old('username', $staff->username ?? '') }}" 
                   maxlength="255">
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $staff->email ?? '') }}" 
                   maxlength="255">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   maxlength="255"
                   {{ !isset($staff) ? 'required' : '' }}>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>

@if(Auth::user()->isSuper())
    <div class="row">
        <div class="col-lg-4 col-md-6 bg-admin">
            <div class="form-group form-check">
                <input type="checkbox" 
                       class="form-check-input @error('is_superlogin') is-invalid @enderror" 
                       id="is_superlogin" 
                       name="is_superlogin" 
                       value="1" 
                       {{ old('is_superlogin', $staff->is_superlogin ?? '') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_superlogin">Is Super Login</label>
                @error('is_superlogin')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
@endif

<style>
.bg-admin {
    background-color: #f8d7da !important;
    padding: 15px;
    border-radius: 5px;
}
</style>
