@extends('web.layouts.app')

@section('title', 'Staff - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Staff</h1>
        <a href="{{ route('web.staff.create') }}" class="btn btn-success JS__load_in_modal">
            <i class="fas fa-plus"></i> Create Staff
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Staff Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('web.staff.index') }}" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by username or email" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="role" class="form-control">
                            <option value="">Select Role</option>
                            <option value="1" {{ ($filters['role'] ?? '') == '1' ? 'selected' : '' }}>Super Admin</option>
                            <option value="10" {{ ($filters['role'] ?? '') == '10' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="date" name="created_at" class="form-control" value="{{ $filters['created_at'] ?? '' }}">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('web.staff.index') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        @if(Auth::user()->isSuper())
                            <th class="grid__id bg-admin">ID</th>
                        @endif
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="{{ Auth::user()->isSuper() ? 'action-column-4' : 'action-column-2' }}">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($staff as $member)
                        <tr>
                            @if(Auth::user()->isSuper())
                                <td class="bg-admin">{{ $member->id }}</td>
                            @endif
                            <td>{{ $member->username }}</td>
                            <td>{{ $member->email }}</td>
                            <td>
                                @if($member->role === 1)
                                    <span class="badge badge-danger">Super Admin</span>
                                @else
                                    <span class="badge badge-info">Admin</span>
                                @endif
                            </td>
                            <td>
                                @if($member->status === 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $member->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('web.staff.show', $member) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('web.staff.edit', $member) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                    @if(Auth::user()->isSuper())
                                        <form action="{{ route('web.staff.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-admin">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                        @if(!$member->isSuper())
                                            <a href="{{ route('web.staff.login-as', $member) }}" class="btn btn-sm btn-admin" title="Login as {{ $member->username }}">
                                                <i class="fas fa-sign-in-alt fa-fw"></i>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isSuper() ? '7' : '6' }}" class="text-center">No staff found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $staff->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.bg-admin {
    background-color: #f8d7da !important;
}
.action-column-2 {
    width: 120px;
}
.action-column-4 {
    width: 200px;
}
.btn-admin {
    background-color: #d73925;
    border-color: #d73925;
    color: white;
}
.btn-admin:hover {
    background-color: #c23321;
    border-color: #c23321;
    color: white;
}
.grid__id {
    width: 60px;
}
.badge-danger {
    background-color: #e74a3b;
}
.badge-info {
    background-color: #36b9cc;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-warning {
    background-color: #f6c23e;
}
</style>
@endsection
