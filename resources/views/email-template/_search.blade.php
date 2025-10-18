<form action="{{ route('email-template.index') }}" method="GET" class="form-inline mb-3">
    <div class="form-group mr-2">
        <input type="text" class="form-control" name="search" placeholder="Search templates..." value="{{ request('search') }}">
    </div>
    
    <div class="form-group mr-2">
        <select class="form-control" name="type">
            <option value="">All Types</option>
            <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Transactional</option>
            <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>Marketing</option>
            <option value="3" {{ request('type') == '3' ? 'selected' : '' }}>System</option>
        </select>
    </div>
    
    <div class="form-group mr-2">
        <select class="form-control" name="is_active">
            <option value="">All Status</option>
            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-search"></i> Search
    </button>
    
    <a href="{{ route('email-template.index') }}" class="btn btn-default">
        <i class="fas fa-redo"></i> Reset
    </a>
</form>
