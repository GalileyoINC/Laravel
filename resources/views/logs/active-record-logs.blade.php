@extends('layouts.app')

@section('title', 'Active Record Logs - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Active Record Logs</h3>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="action_type" class="form-control">
                                <option value="">All Actions</option>
                                <option value="create" {{ request('action_type') == 'create' ? 'selected' : '' }}>Create</option>
                                <option value="update" {{ request('action_type') == 'update' ? 'selected' : '' }}>Update</option>
                                <option value="delete" {{ request('action_type') == 'delete' ? 'selected' : '' }}>Delete</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="model" class="form-control" placeholder="Model" value="{{ request('model') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="number" name="id_user" class="form-control" placeholder="User ID" value="{{ request('id_user') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="number" name="id_staff" class="form-control" placeholder="Staff ID" value="{{ request('id_staff') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_from" class="form-control" value="{{ request('created_at_from') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_to" class="form-control" value="{{ request('created_at_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('logs.active-record-logs') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('logs.export-active-record-logs', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>Created At</th>
                                    <th>User</th>
                                    <th>Staff</th>
                                    <th>Action Type</th>
                                    <th>Model</th>
                                    <th>ID Model</th>
                                    <th>Field</th>
                                    <th>Changes</th>
                                    <th class="action-column-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            @if($log->user)
                                                <a href="{{ route('user.show', $log->user) }}">{{ $log->user->getFullName() }} ({{ $log->user->id }})</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->staff)
                                                {{ $log->staff->username }} ({{ $log->staff->id }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-info">{{ ucfirst($log->action_type) }}</span>
                                        </td>
                                        <td>{{ $log->model }}</td>
                                        <td>{{ $log->id_model }}</td>
                                        <td>{{ $log->field ?: '-' }}</td>
                                        <td>
                                            @if(!empty($log->changes))
                                                @if(count($log->changes) > 2)
                                                    <div class="shrink_field">
                                                @else
                                                    <div>
                                                @endif
                                                    <dl class="dl-horizontal" style="margin-bottom:0">
                                                        @foreach($log->changes as $key => $value)
                                                            <dt>{{ $key }}</dt>
                                                            <dd>{{ is_array($value) ? 'JSON' : $value }}</dd>
                                                        @endforeach
                                                    </dl>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <!-- No actions for active record logs -->
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No active record logs found.</td>
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
.action-column-2 {
    width: 150px;
}
.label {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}
.label-info {
    background-color: #17a2b8;
    color: #fff;
}
.shrink_field {
    cursor: pointer;
    max-height: 50px;
    overflow: hidden;
    transition: max-height 0.3s ease;
}
.shrink_field--open {
    max-height: none;
}
.dl-horizontal {
    margin-bottom: 0;
}
.dl-horizontal dt {
    float: left;
    width: 100px;
    clear: left;
    text-align: right;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.dl-horizontal dd {
    margin-left: 120px;
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
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.shrink_field').forEach(function(element) {
        element.addEventListener('click', function() {
            this.classList.toggle('shrink_field--open');
        });
    });
});
</script>
@endsection
