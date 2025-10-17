@extends('web.layouts.app')

@section('title', 'Device: ' . $device->uuid . ' - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Device Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">ID</th>
                                        <td>{{ $device->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>User</th>
                                        <td>
                                            @if($device->user)
                                                <a href="{{ route('web.user.show', $device->user) }}">
                                                    {{ $device->user->email }} ({{ $device->user->id }})
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Push Turn On</th>
                                        <td>
                                            @if($device->push_turn_on)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>UUID</th>
                                        <td>{{ $device->uuid }}</td>
                                    </tr>
                                    <tr>
                                        <th>OS</th>
                                        <td>{{ ucfirst($device->os) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Push Token</th>
                                        <td>
                                            @if($device->push_token)
                                                <code>{{ $device->push_token }}</code>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Access Token</th>
                                        <td>
                                            @if($device->access_token)
                                                <code>{{ $device->access_token }}</code>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $device->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $device->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($device->params)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Device Parameters</h4>
                                <pre class="text-bg-light p-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.25rem;">{{ json_encode($device->params, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('web.device.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back to Devices
                            </a>
                            @if($device->push_token)
                                <a href="{{ route('web.device.push', $device) }}" class="btn btn-admin">
                                    <i class="far fa-paper-plane"></i> Send Push
                                </a>
                            @endif
                            <form method="POST" action="{{ route('web.device.destroy', $device) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this device?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
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
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
code {
    font-family: 'Courier New', Courier, monospace;
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    word-break: break-all;
}
pre {
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.875rem;
    line-height: 1.4;
    margin: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
}
.text-bg-light {
    background-color: #f8f9fa;
}
.text-success {
    color: #28a745;
}
.text-danger {
    color: #dc3545;
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
    margin-right: 0.5rem;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn-admin {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn:hover {
    text-decoration: none;
}
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
}
.btn-admin:hover {
    color: #fff;
    background-color: #545b62;
    border-color: #4e555b;
}
.btn-danger:hover {
    color: #fff;
    background-color: #c82333;
    border-color: #bd2130;
}
.d-inline {
    display: inline;
}
</style>
@endsection
