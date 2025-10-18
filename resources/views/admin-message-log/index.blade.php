@extends('layouts.app')

@section('title', 'Admin Message Log - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Admin Message Log</h3>
                </div>
                <div class="panel-body">
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($adminMessageLogs->total() > 0)
                            Showing <b>{{ $adminMessageLogs->firstItem() }}-{{ $adminMessageLogs->lastItem() }}</b> of <b>{{ $adminMessageLogs->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('admin-message-log.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <form method="GET" id="filters-form"></form>
                        <table class="table table-striped table-bordered detail-view">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Object Type</th>
                                    <th>Object ID</th>
                                    <th>Message</th>
                                    <th>Sent</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search messages..." value="{{ request('search') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="objType" class="form-control" form="filters-form" placeholder="Object Type" value="{{ request('objType') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="objId" class="form-control" form="filters-form" placeholder="Object ID" value="{{ request('objId') }}">
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="d-flex" style="gap:6px;">
                                            <input type="date" name="created_at_from" class="form-control" form="filters-form" value="{{ request('created_at_from') }}">
                                            <input type="date" name="created_at_to" class="form-control" form="filters-form" value="{{ request('created_at_to') }}">
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adminMessageLogs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->obj_type ?? '-' }}</td>
                                        <td>{{ $log->obj_id ?? '-' }}</td>
                                        <td>{{ $log->body }}</td>
                                        <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No admin message logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $adminMessageLogs->appends(request()->query())->links() }}
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
.detail-view {
    border-collapse: collapse;
    width: 100%;
}
.detail-view th,
.detail-view td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
.detail-view th {
    background-color: #f2f2f2;
    font-weight: bold;
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