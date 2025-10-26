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
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $emailTemplate->name ?? '') }}" required>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="form-group">
                <label for="subject">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $emailTemplate->subject ?? '') }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="from">From</label>
                <input type="text" class="form-control" id="from" name="from" value="{{ old('from', $emailTemplate->from ?? '') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="body">Body <span class="text-danger">*</span></label>
                <textarea class="form-control" id="body" name="body" rows="10" required>{{ old('body', $emailTemplate->body ?? '') }}</textarea>
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
