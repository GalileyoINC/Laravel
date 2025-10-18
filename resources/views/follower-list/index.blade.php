@extends('layouts.app')

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
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($followerLists->total() > 0)
                            Showing <b>{{ $followerLists->firstItem() }}-{{ $followerLists->lastItem() }}</b> of <b>{{ $followerLists->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('follower-list.export', request()->query()) }}" class="btn btn-success">
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
                                    <th class="text-center"></th>
                                    <th>Name</th>
                                    <th>User</th>
                                    <th>Active</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" class="form-control" name="id" form="filters-form" value="{{ request('id') }}">
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <input type="text" class="form-control" name="name" form="filters-form" value="{{ request('name') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="userName" form="filters-form" value="{{ request('userName') }}">
                                    </td>
                                    <td>
                                        <select class="form-control" name="is_active" form="filters-form">
                                            <option value=""></option>
                                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>No</option>
                                        </select>
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
                                                <a href="{{ route('user.show', $followerList->user) }}">
                                                    {{ $followerList->user->first_name }} {{ $followerList->user->last_name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($followerList->is_active)
                                                <span class="text-success"><i class="fas fa-check"></i></span>
                                            @else
                                                <span class="text-danger"><i class="fas fa-times"></i></span>
                                            @endif
                                        </td>
                                        <td>{{ $followerList->created_at->format('M d, Y') }}</td>
                                        <td>{{ $followerList->updated_at->format('M d, Y') }}</td>
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
