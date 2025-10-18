@extends('layouts.app')

@section('title', '{{ $isUnfinishedSignup == 1 ? "Unfinished Signups" : "Subscribed for newsletters" }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $isUnfinishedSignup == 1 ? 'Unfinished Signups' : 'Subscribed for newsletters' }}</h1>
        <div>
            <a href="{{ route('register.index-unique', ['signup' => $isUnfinishedSignup]) }}" class="btn btn-default">
                Show Unique
            </a>
            <a href="{{ route('register.to-csv', array_merge(['signup' => $isUnfinishedSignup], request()->query())) }}" class="btn btn-default">
                to .CSV
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Register Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('register.index') }}" method="GET" class="mb-4">
                <input type="hidden" name="is_unfinished_signup" value="{{ $isUnfinishedSignup }}">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by email, first name, or last name" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="date" name="created_at" class="form-control" value="{{ $filters['created_at'] ?? '' }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('register.index', ['is_unfinished_signup' => $isUnfinishedSignup]) }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Created At</th>
                        <th class="action-column-1">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($registers as $register)
                        <tr>
                            <td>{{ $register->id }}</td>
                            <td>{{ $register->email }}</td>
                            <td>{{ $register->first_name }}</td>
                            <td>{{ $register->last_name }}</td>
                            <td>{{ $register->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <div class="btn-group">
                                    <!-- No actions for register records -->
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No registers found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $registers->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.grid__id {
    width: 60px;
}
.action-column-1 {
    width: 100px;
}
</style>
@endsection
