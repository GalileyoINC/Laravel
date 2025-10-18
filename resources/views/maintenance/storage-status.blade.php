@extends('layouts.app')

@section('title', 'Storage Status - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Storage Status</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('maintenance.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Maintenance
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Storage Configuration</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Default Disk</th>
                                        <td>{{ $storageInfo['disk'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Public Path</th>
                                        <td><code>{{ $storageInfo['public_path'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Storage Path</th>
                                        <td><code>{{ $storageInfo['storage_path'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Logs Path</th>
                                        <td><code>{{ $storageInfo['logs_path'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Cache Path</th>
                                        <td><code>{{ $storageInfo['cache_path'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Sessions Path</th>
                                        <td><code>{{ $storageInfo['sessions_path'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Views Path</th>
                                        <td><code>{{ $storageInfo['views_path'] }}</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Directory Status</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Public Directory</th>
                                        <td>
                                            @if(is_dir($storageInfo['public_path']))
                                                <span class="label label-success">Exists</span>
                                            @else
                                                <span class="label label-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Storage Directory</th>
                                        <td>
                                            @if(is_dir($storageInfo['storage_path']))
                                                <span class="label label-success">Exists</span>
                                            @else
                                                <span class="label label-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Logs Directory</th>
                                        <td>
                                            @if(is_dir($storageInfo['logs_path']))
                                                <span class="label label-success">Exists</span>
                                            @else
                                                <span class="label label-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Cache Directory</th>
                                        <td>
                                            @if(is_dir($storageInfo['cache_path']))
                                                <span class="label label-success">Exists</span>
                                            @else
                                                <span class="label label-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sessions Directory</th>
                                        <td>
                                            @if(is_dir($storageInfo['sessions_path']))
                                                <span class="label label-success">Exists</span>
                                            @else
                                                <span class="label label-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Views Directory</th>
                                        <td>
                                            @if(is_dir($storageInfo['views_path']))
                                                <span class="label label-success">Exists</span>
                                            @else
                                                <span class="label label-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h4>Storage Management</h4>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('maintenance.storage-status') }}" class="btn btn-info">
                                            <i class="fas fa-sync"></i> Refresh Status
                                        </a>
                                        <button type="button" class="btn btn-warning" onclick="clearStorage()">
                                            <i class="fas fa-trash"></i> Clear Storage
                                        </button>
                                        <button type="button" class="btn btn-success" onclick="createDirectories()">
                                            <i class="fas fa-folder-plus"></i> Create Directories
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h4>Directory Permissions</h4>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Directory</th>
                                                <th>Readable</th>
                                                <th>Writable</th>
                                                <th>Executable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach([
                                                'public_path' => 'Public',
                                                'storage_path' => 'Storage',
                                                'logs_path' => 'Logs',
                                                'cache_path' => 'Cache',
                                                'sessions_path' => 'Sessions',
                                                'views_path' => 'Views'
                                            ] as $key => $label)
                                                <tr>
                                                    <td><strong>{{ $label }}</strong></td>
                                                    <td>
                                                        @if(is_readable($storageInfo[$key]))
                                                            <span class="label label-success">Yes</span>
                                                        @else
                                                            <span class="label label-danger">No</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_writable($storageInfo[$key]))
                                                            <span class="label label-success">Yes</span>
                                                        @else
                                                            <span class="label label-danger">No</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_executable($storageInfo[$key]))
                                                            <span class="label label-success">Yes</span>
                                                        @else
                                                            <span class="label label-danger">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
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
code {
    padding: 0.2rem 0.4rem;
    font-size: 87.5%;
    color: #e83e8c;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}
</style>

<script>
function clearStorage() {
    if (confirm('Are you sure you want to clear all storage? This will delete all cached files, sessions, and logs.')) {
        // This would typically make an AJAX call to clear storage
        alert('Storage clearing functionality would be implemented here');
    }
}

function createDirectories() {
    if (confirm('Are you sure you want to create all required directories?')) {
        // This would typically make an AJAX call to create directories
        alert('Directory creation functionality would be implemented here');
    }
}
</script>
@endsection
