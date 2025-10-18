@extends('layouts.app')

@section('title', 'Staff - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Staff</h1>
        <a href="{{ route('staff.create') }}" class="btn btn-success JS__load_in_modal">
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
        <div class="card-body">
            <!-- Summary -->
            <div class="summary" style="margin-bottom:10px;">
                @if($staff->total() > 0)
                    Showing <b>{{ $staff->firstItem() }}-{{ $staff->lastItem() }}</b> of <b>{{ $staff->total() }}</b> items.
                @else
                    Showing <b>0-0</b> of <b>0</b> items.
                @endif
            </div>

            <div class="table-responsive">
                <form action="{{ route('staff.index') }}" method="GET" id="filters-form"></form>
                <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                    <tr class="filters">
                        @if(Auth::user()->isSuper())
                            <td>
                                <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                            </td>
                        @else
                            <td>
                                <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                            </td>
                        @endif
                        <td></td>
                        <td>
                            <select name="role" class="form-control" form="filters-form">
                                <option value=""></option>
                                <option value="1" {{ ($filters['role'] ?? '') == '1' ? 'selected' : '' }}>Super Admin</option>
                                <option value="10" {{ ($filters['role'] ?? '') == '10' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </td>
                        <td>
                            <select name="status" class="form-control" form="filters-form">
                                <option value=""></option>
                                <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        <td>
                            <input type="date" name="created_at" class="form-control" form="filters-form" value="{{ $filters['created_at'] ?? '' }}">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                            <a href="{{ route('staff.index') }}" class="btn btn-default ml-2">Clear</a>
                        </td>
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
                            <td>{{ $member->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('staff.show', $member) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('staff.edit', $member) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                    @if(Auth::user()->isSuper())
                                        <form action="{{ route('staff.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-admin">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                        @if(!$member->isSuper())
                                            <a href="{{ route('staff.login-as', $member) }}" class="btn btn-sm btn-admin" title="Login as {{ $member->username }}">
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
