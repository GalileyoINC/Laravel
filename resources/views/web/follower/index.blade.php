@extends('web.layouts.app')

@section('title', 'Followers - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Followers</h3>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="followerListName" class="form-control" placeholder="List Name" value="{{ request('followerListName') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="userLeaderName" class="form-control" placeholder="Leader Name" value="{{ request('userLeaderName') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="userFollowerName" class="form-control" placeholder="Follower Name" value="{{ request('userFollowerName') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="is_active" class="form-control">
                                <option value="">All Status</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_from" class="form-control" value="{{ request('created_at_from') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_to" class="form-control" value="{{ request('created_at_to') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="updated_at_from" class="form-control" value="{{ request('updated_at_from') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="updated_at_to" class="form-control" value="{{ request('updated_at_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('web.follower.index') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('web.follower.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>List</th>
                                    <th>Leader</th>
                                    <th>Follower</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($followers as $follower)
                                    <tr>
                                        <td>{{ $follower->id }}</td>
                                        <td>{{ $follower->followerList ? $follower->followerList->name : '-' }}</td>
                                        <td>
                                            @if($follower->userLeader)
                                                <a href="{{ route('web.user.show', $follower->userLeader) }}">
                                                    {{ $follower->userLeader->first_name }} {{ $follower->userLeader->last_name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($follower->userFollower)
                                                <a href="{{ route('web.user.show', $follower->userFollower) }}">
                                                    {{ $follower->userFollower->first_name }} {{ $follower->userFollower->last_name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $follower->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $follower->updated_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No followers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $followers->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
.panel-body {
    padding: 15px;
}
.grid__id {
    width: 60px;
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #f8f9fa;
    font-weight: 600;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-inline .form-group {
    display: flex;
    flex: 0 0 auto;
    flex-flow: row wrap;
    align-items: center;
    margin-bottom: 0;
}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
.text-center {
    text-align: center;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
@endsection
