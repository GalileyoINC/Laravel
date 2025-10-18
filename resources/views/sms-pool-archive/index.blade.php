@extends('layouts.app')

@section('title', 'Messages Pool Archive - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Messages Pool Archive</h3>
                </div>
                <div class="panel-body">
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($smsPoolArchives->total() > 0)
                            Showing <b>{{ $smsPoolArchives->firstItem() }}-{{ $smsPoolArchives->lastItem() }}</b> of <b>{{ $smsPoolArchives->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('sms-pool-archive.export', request()->query()) }}" class="btn btn-success">
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
                                    <th>Purpose</th>
                                    <th>Subscription</th>
                                    <th>Private Feed</th>
                                    <th>Sender</th>
                                    <th>Body</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th class="action-column-1">Actions</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ request('search') }}">
                                    </td>
                                    <td>
                                        <select name="purpose" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            @foreach($purposes as $key => $value)
                                                <option value="{{ $key }}" {{ request('purpose') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="id_subscription" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            @foreach($subscriptions as $id => $name)
                                                <option value="{{ $id }}" {{ request('id_subscription') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="followerListName" class="form-control" form="filters-form" value="{{ request('followerListName') }}" placeholder="Private Feed">
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <input type="date" name="created_at_from" class="form-control" form="filters-form" value="{{ request('created_at_from') }}">
                                    </td>
                                    <td>
                                        <input type="date" name="updated_at_from" class="form-control" form="filters-form" value="{{ request('updated_at_from') }}">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                        <a href="{{ route('sms-pool-archive.index') }}" class="btn btn-default ml-2">Clear</a>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($smsPoolArchives as $smsPoolArchive)
                                    <tr>
                                        <td>{{ $smsPoolArchive->id }}</td>
                                        <td>{{ $purposes[$smsPoolArchive->purpose] ?? $smsPoolArchive->purpose }}</td>
                                        <td>{{ $smsPoolArchive->subscription ? $smsPoolArchive->subscription->name : '-' }}</td>
                                        <td>{{ $smsPoolArchive->followerList ? $smsPoolArchive->followerList->name : '-' }}</td>
                                        <td>
                                            @if($smsPoolArchive->user)
                                                User: {{ $smsPoolArchive->user->first_name }} {{ $smsPoolArchive->user->last_name }}
                                            @elseif($smsPoolArchive->staff)
                                                Staff: {{ $smsPoolArchive->staff->username }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($smsPoolArchive->body, 50) }}</td>
                                        <td>{{ $smsPoolArchive->created_at->format('M d, Y') }}</td>
                                        <td>{{ $smsPoolArchive->updated_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('sms-pool-archive.show', $smsPoolArchive) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye fa-fw"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No archived SMS messages found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $smsPoolArchives->appends(request()->query())->links() }}
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
.action-column-1 {
    width: 100px;
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
.btn-group {
    display: inline-flex;
    vertical-align: middle;
}
.btn-group .btn {
    position: relative;
    flex: 1 1 auto;
}
.btn-group .btn + .btn {
    margin-left: -1px;
}
.btn-group .btn:first-child {
    margin-left: 0;
}
.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
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
