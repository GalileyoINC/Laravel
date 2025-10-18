<form action="{{ route('user.index') }}" method="GET" class="form-inline mb-3">
    <div class="form-group mr-2">
        <input type="text" class="form-control" name="search" placeholder="Search users..." value="{{ request('search') }}">
    </div>
    
    <div class="form-group mr-2">
        <select class="form-control" name="status">
            <option value="">All Status</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>
    
    <div class="form-group mr-2">
        <select class="form-control" name="is_influencer">
            <option value="">All Users</option>
            <option value="1" {{ request('is_influencer') == '1' ? 'selected' : '' }}>Influencers Only</option>
            <option value="0" {{ request('is_influencer') == '0' ? 'selected' : '' }}>Non-Influencers</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-search"></i> Search
    </button>
    
    <a href="{{ route('user.index') }}" class="btn btn-default">
        <i class="fas fa-redo"></i> Reset
    </a>
</form>
