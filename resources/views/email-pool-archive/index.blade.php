@extends('layouts.app')

@section('title', 'Email Pools Archive - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Email Pools Archive</h3>
                </div>
                <div class="panel-body">
                    <!-- Archive/Common Toggle -->
                    <div class="mb-3">
                        <a href="{{ route('email-pool.index') }}" class="btn btn-info">
                            <i class="fas fa-archive"></i> Common
                        </a>
                    </div>

                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($emailPoolArchives->total() > 0)
                            Showing <b>{{ $emailPoolArchives->firstItem() }}-{{ $emailPoolArchives->lastItem() }}</b> of <b>{{ $emailPoolArchives->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('email-pool-archive.export', request()->query()) }}" class="btn btn-success">
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
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Subject</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th class="action-column-2">Actions</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" name="search" class="form-control" form="filters-form" value="{{ request('search') }}" placeholder="Search...">
                                    </td>
                                    <td>
                                        <select name="type" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            @foreach($sendingTypes as $key => $value)
                                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="status" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            @foreach($statuses as $key => $value)
                                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="email" name="from" class="form-control" form="filters-form" value="{{ request('from') }}">
                                    </td>
                                    <td>
                                        <input type="email" name="to" class="form-control" form="filters-form" value="{{ request('to') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="subject" class="form-control" form="filters-form" value="{{ request('subject') }}">
                                    </td>
                                    <td>
                                        <input type="date" name="created_at_from" class="form-control" form="filters-form" value="{{ request('created_at_from') }}">
                                    </td>
                                    <td>
                                        <input type="date" name="updated_at_from" class="form-control" form="filters-form" value="{{ request('updated_at_from') }}">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                        <a href="{{ route('email-pool-archive.index') }}" class="btn btn-default ml-2">Clear</a>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emailPoolArchives as $emailPoolArchive)
                                    <tr>
                                        <th class="grid__id">ID</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Subject</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th class="action-column-2">Actions</th>
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                        <i class="fas fa-trash fa-fw"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No archived email pools found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $emailPoolArchives->appends(request()->query())->links() }}
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
.action-column-2 {
    width: 150px;
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
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
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
