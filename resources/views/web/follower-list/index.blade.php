@extends('web.layouts.app')

@section('title', 'Private Feeds - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Private Feeds</h3>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request('name') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="userName" class="form-control" placeholder="User Name" value="{{ request('userName') }}">
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
                        <a href="{{ route('web.follower-list.index') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('web.follower-list.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th class="text-center">Image</th>
                                    <th>Name</th>
                                    <th>User</th>
                                    <th>Is Active</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($followerLists as $followerList)
                                    <tr>
                                        <td>{{ $followerList->id }}</td>
                                        <td class="text-center">
                                            @if($followerList->image)
                                                <img src="{{ $followerList->image }}" alt="Image" style="width: 48px; border-radius: 50%;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $followerList->name }}</td>
                                        <td>
                                            @if($followerList->user)
                                                <a href="{{ route('web.user.show', $followerList->user) }}">
                                                    {{ $followerList->user->first_name }} {{ $followerList->user->last_name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($followerList->is_active)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $followerList->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $followerList->updated_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No follower lists found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $followerLists->appends(request()->query())->links() }}
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
.text-center {
    text-align: center;
}
.badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}
.badge-success {
    color: #fff;
    background-color: #28a745;
}
.badge-danger {
    color: #fff;
    background-color: #dc3545;
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
