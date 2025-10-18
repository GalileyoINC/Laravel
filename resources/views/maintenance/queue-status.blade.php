@extends('layouts.app')

@section('title', 'Queue Status - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Queue Status</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('maintenance.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Maintenance
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Queue Configuration</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Queue Driver</th>
                                        <td>{{ $queueInfo['driver'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Connection</th>
                                        <td>{{ $queueInfo['connection']['driver'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Host</th>
                                        <td>{{ $queueInfo['connection']['host'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Database</th>
                                        <td>{{ $queueInfo['connection']['database'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Table</th>
                                        <td>{{ $queueInfo['connection']['table'] ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Queue Statistics</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Pending Jobs</th>
                                        <td>
                                            <span class="label label-{{ $queueInfo['jobs_count'] > 0 ? 'warning' : 'success' }}">
                                                {{ $queueInfo['jobs_count'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Failed Jobs</th>
                                        <td>
                                            <span class="label label-{{ $queueInfo['failed_jobs_count'] > 0 ? 'danger' : 'success' }}">
                                                {{ $queueInfo['failed_jobs_count'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Queue Status</th>
                                        <td>
                                            @if($queueInfo['jobs_count'] > 0)
                                                <span class="label label-warning">Has Pending Jobs</span>
                                            @elseif($queueInfo['failed_jobs_count'] > 0)
                                                <span class="label label-danger">Has Failed Jobs</span>
                                            @else
                                                <span class="label label-success">Healthy</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h4>Queue Management</h4>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('maintenance.queue-status') }}" class="btn btn-info">
                                            <i class="fas fa-sync"></i> Refresh Status
                                        </a>
                                        @if($queueInfo['failed_jobs_count'] > 0)
                                            <button type="button" class="btn btn-warning" onclick="retryFailedJobs()">
                                                <i class="fas fa-redo"></i> Retry Failed Jobs
                                            </button>
                                        @endif
                                        @if($queueInfo['jobs_count'] > 0)
                                            <button type="button" class="btn btn-success" onclick="processQueue()">
                                                <i class="fas fa-play"></i> Process Queue
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($queueInfo['failed_jobs_count'] > 0)
                        <div class="row">
                            <div class="col-12">
                                <h4>Failed Jobs</h4>
                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Failed Jobs ({{ $queueInfo['failed_jobs_count'] }})</h4>
                                    </div>
                                    <div class="panel-body">
                                        <p>There are {{ $queueInfo['failed_jobs_count'] }} failed jobs in the queue.</p>
                                        <p>You can retry them or clear them from the database.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($queueInfo['jobs_count'] > 0)
                        <div class="row">
                            <div class="col-12">
                                <h4>Pending Jobs</h4>
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Pending Jobs ({{ $queueInfo['jobs_count'] }})</h4>
                                    </div>
                                    <div class="panel-body">
                                        <p>There are {{ $queueInfo['jobs_count'] }} pending jobs in the queue.</p>
                                        <p>You can process them manually or wait for the queue worker.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.panel-heading-actions {
    display: flex;
    gap: 10px;
}
.panel-body {
    padding: 15px;
}
.panel-danger {
    border: 1px solid #d9534f;
}
.panel-danger .panel-heading {
    background-color: #f2dede;
    border-bottom: 1px solid #d9534f;
}
.panel-warning {
    border: 1px solid #f0ad4e;
}
.panel-warning .panel-heading {
    background-color: #fcf8e3;
    border-bottom: 1px solid #f0ad4e;
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
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
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
.label-success {
    background-color: #28a745;
    color: #fff;
}
.label-warning {
    background-color: #ffc107;
    color: #212529;
}
.label-danger {
    background-color: #dc3545;
    color: #fff;
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
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn-warning {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    text-decoration: none;
}
.btn:hover {
    text-decoration: none;
}
.btn-info:hover {
    color: #fff;
    background-color: #138496;
    border-color: #117a8b;
}
.btn-warning:hover {
    color: #212529;
    background-color: #e0a800;
    border-color: #d39e00;
}
.btn-success:hover {
    color: #fff;
    background-color: #218838;
    border-color: #1e7e34;
}
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
}
</style>

<script>
function retryFailedJobs() {
    if (confirm('Are you sure you want to retry all failed jobs?')) {
        // This would typically make an AJAX call to retry failed jobs
        alert('Failed jobs retry functionality would be implemented here');
    }
}

function processQueue() {
    if (confirm('Are you sure you want to process the queue manually?')) {
        // This would typically make an AJAX call to process the queue
        alert('Queue processing functionality would be implemented here');
    }
}
</script>
@endsection
