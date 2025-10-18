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
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $template->name ?? '') }}" required>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="form-group">
                <label for="subject">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $template->subject ?? '') }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="from_email">From Email</label>
                <input type="email" class="form-control" id="from_email" name="from_email" value="{{ old('from_email', $template->from_email ?? '') }}">
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="form-group">
                <label for="from_name">From Name</label>
                <input type="text" class="form-control" id="from_name" name="from_name" value="{{ old('from_name', $template->from_name ?? '') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="1" {{ old('type', $template->type ?? '') == '1' ? 'selected' : '' }}>Transactional</option>
                    <option value="2" {{ old('type', $template->type ?? '') == '2' ? 'selected' : '' }}>Marketing</option>
                    <option value="3" {{ old('type', $template->type ?? '') == '3' ? 'selected' : '' }}>System</option>
                </select>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="form-group">
                <label>&nbsp;</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}>
                        Is Active
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="body">Body <span class="text-danger">*</span></label>
                <textarea class="form-control" id="body" name="body" rows="10" required>{{ old('body', $template->body ?? '') }}</textarea>
                <small class="text-muted">You can use HTML and template variables like {user_name}, {email}, etc.</small>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <strong>Available Variables:</strong>
        <ul class="mb-0">
            <li><code>{user_name}</code> - User's full name</li>
            <li><code>{email}</code> - User's email</li>
            <li><code>{first_name}</code> - User's first name</li>
            <li><code>{last_name}</code> - User's last name</li>
            <li><code>{site_name}</code> - Site name</li>
            <li><code>{site_url}</code> - Site URL</li>
        </ul>
    </div>
</div>
