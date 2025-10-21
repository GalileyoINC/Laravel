@extends('layouts.app')

@section('title', 'System Information - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">System Information</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('maintenance.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Maintenance
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>PHP Information</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">PHP Version</th>
                                        <td>{{ $systemInfo['php_version'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Server Software</th>
                                        <td>{{ $systemInfo['server_software'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Server OS</th>
                                        <td>{{ $systemInfo['server_os'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Memory Limit</th>
                                        <td>{{ $systemInfo['memory_limit'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Max Execution Time</th>
                                        <td>{{ $systemInfo['max_execution_time'] }}s</td>
                                    </tr>
                                    <tr>
                                        <th>Upload Max Filesize</th>
                                        <td>{{ $systemInfo['upload_max_filesize'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Post Max Size</th>
                                        <td>{{ $systemInfo['post_max_size'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Laravel Information</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Laravel Version</th>
                                        <td>{{ $systemInfo['laravel_version'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Environment</th>
                                        <td>
                                            <span class="badge bg-{{ $systemInfo['environment'] === 'production' ? 'success' : 'warning' }}">
                                                {{ $systemInfo['environment'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Debug Mode</th>
                                        <td>
                                            <span class="badge bg-{{ $systemInfo['debug_mode'] ? 'danger' : 'success' }}">
                                                {{ $systemInfo['debug_mode'] ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Timezone</th>
                                        <td>{{ $systemInfo['timezone'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Database Driver</th>
                                        <td>{{ $systemInfo['database_driver'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h4>PHP Extensions</h4>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        @php
                                            $extensions = get_loaded_extensions();
                                            $chunks = array_chunk($extensions, ceil(count($extensions) / 4));
                                        @endphp
                                        @foreach($chunks as $chunk)
                                            <div class="col-md-3">
                                                <ul class="list-unstyled">
                                                    @foreach($chunk as $extension)
                                                        <li>
                                                            <span class="badge bg-success">{{ $extension }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h4>Server Variables</h4>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Variable</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($_SERVER as $key => $value)
                                                <tr>
                                                    <td><code>{{ $key }}</code></td>
                                                    <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
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
.label-warning {
    background-color: #ffc107;
    color: #212529;
}
.label-danger {
    background-color: #dc3545;
    color: #fff;
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
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
}
.list-unstyled {
    padding-left: 0;
    list-style: none;
}
code {
    padding: 0.2rem 0.4rem;
    font-size: 87.5%;
    color: #e83e8c;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}
</style>
@endsection
