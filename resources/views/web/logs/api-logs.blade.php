@extends('web.layouts.app')

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
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="key" class="form-control" placeholder="Key" value="{{ request('key') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_from" class="form-control" value="{{ request('created_at_from') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_to" class="form-control" value="{{ request('created_at_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('web.logs.api-logs') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('web.logs.export-api-logs', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th>Created At</th>
                                    <th class="{{ auth()->user()->isSuper() ? 'action-column-2' : 'action-column-1' }}">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->key }}</td>
                                        <td>
                                            @if(in_array($log->key, ['Tsunami_NTWC', 'Tsunami_PTWC']))
                                                {!! $log->value !!}
                                            @elseif(stripos($log->key, 'Weather') !== false && stripos($log->key, 'Predict') === false)
                                                @if($log->value)
                                                    @php
                                                        $weatherData = json_decode($log->value, true);
                                                    @endphp
                                                    @if($weatherData)
                                                        <div class="weather-data">
                                                            <strong>{{ $weatherData['properties']['areaDesc'] ?? 'Weather Data' }}</strong><br>
                                                            <small>{{ $weatherData['properties']['headline'] ?? '' }}</small>
                                                        </div>
                                                    @else
                                                        {{ $log->value }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            @else
                                                {{ Str::limit($log->value, 100) }}
                                            @endif
                                        </td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('web.logs.show-api-log', $log) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye fa-fw"></i>
                                                </a>
                                                @if(auth()->user()->isSuper())
                                                    <form method="POST" action="{{ route('web.logs.delete-by-key', $log->key) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-admin" onclick="return confirm('Are you sure you want to delete this item?')">
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
                        {{ $logs->appends(request()->query())->links() }}
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
.action-column-2 {
    width: 150px;
}
.weather-data {
    background-color: #f8f9fa;
    padding: 5px;
    border-radius: 3px;
    font-size: 0.875rem;
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
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
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
</style>
@endsection
