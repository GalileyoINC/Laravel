@extends('layouts.app')

@section('title', 'API Logs - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">API Logs</h3>
                </div>
                <div class="panel-body">
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($apiLogs->total() > 0)
                            Showing <b>{{ $apiLogs->firstItem() }}-{{ $apiLogs->lastItem() }}</b> of <b>{{ $apiLogs->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('api-log.export', request()->query()) }}" class="btn btn-success">
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
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th>Created At</th>
                                    <th class="action-column-2">Actions</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ request('search') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="key" class="form-control" form="filters-form" placeholder="Key" value="{{ request('key') }}">
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="d-flex" style="gap:6px;">
                                            <input type="date" name="created_at_from" class="form-control" form="filters-form" value="{{ request('created_at_from') }}">
                                            <input type="date" name="created_at_to" class="form-control" form="filters-form" value="{{ request('created_at_to') }}">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                        <a href="{{ route('api-log.index') }}" class="btn btn-default ml-2">Clear</a>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($apiLogs as $apiLog)
                                    <tr>
                                        <td>{{ $apiLog->id }}</td>
                                        <td>{{ $apiLog->key }}</td>
                                        <td>
                                            @if(in_array($apiLog->key, ['Tsunami_NTWC', 'Tsunami_PTWC']))
                                                {{ $apiLog->value }}
                                            @elseif(stripos($apiLog->key, 'Weather') !== false && stripos($apiLog->key, 'Predict') === false)
                                                @include('api-log._weather_gov', ['json' => json_decode($apiLog->value, true)])
                                            @else
                                                {{ Str::limit($apiLog->value, 100) }}
                                            @endif
                                        </td>
                                        <td>{{ $apiLog->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('api-log.show', $apiLog) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye fa-fw"></i>
                                                </a>
                                                @if(auth()->user()->isSuper())
                                                    <form method="POST" action="{{ route('api-log.delete-by-key', $apiLog) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-admin" onclick="return confirm('Are you sure you want to delete all logs with this key?')">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No API logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $apiLogs->appends(request()->query())->links() }}
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
.btn-admin {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
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
