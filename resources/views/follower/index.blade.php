@extends('layouts.app')

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
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($followers->total() > 0)
                            Showing <b>{{ $followers->firstItem() }}-{{ $followers->lastItem() }}</b> of <b>{{ $followers->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('follower.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <form method="GET" id="filters-form"></form>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>List</th>
                                    <th>Leader</th>
                                    <th>Follower</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" class="form-control" name="id" form="filters-form" value="{{ request('id') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="followerListName" form="filters-form" value="{{ request('followerListName') }}" placeholder="List Name">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="userLeaderName" form="filters-form" value="{{ request('userLeaderName') }}" placeholder="Leader Name">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="userFollowerName" form="filters-form" value="{{ request('userFollowerName') }}" placeholder="Follower Name">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="created_at" form="filters-form" value="{{ request('created_at') }}">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="updated_at" form="filters-form" value="{{ request('updated_at') }}">
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($followers as $follower)
                                    <tr>
                                        <td>{{ $follower->id }}</td>
                                        <td>{{ $follower->followerList ? $follower->followerList->name : '-' }}</td>
                                        <td>
                                            @if($follower->userLeader)
                                                <a href="{{ route('user.show', $follower->userLeader) }}">
                                                    {{ $follower->userLeader->first_name }} {{ $follower->userLeader->last_name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($follower->userFollower)
                                                <a href="{{ route('user.show', $follower->userFollower) }}">
                                                    {{ $follower->userFollower->first_name }} {{ $follower->userFollower->last_name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $follower->created_at->format('M d, Y') }}</td>
                                        <td>{{ $follower->updated_at->format('M d, Y') }}</td>
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
