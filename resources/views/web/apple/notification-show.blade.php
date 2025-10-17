@extends('web.layouts.app')

@section('title', 'Apple Notification Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Apple Notification #{{ $notification->id }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.apple.notifications') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Notification Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $notification->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notification Type</th>
                                        <td>
                                            <span class="label label-info">{{ $notification->notification_type }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <td>{{ $notification->transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Original Transaction ID</th>
                                        <td>{{ $notification->original_transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Is Process</th>
                                        <td>
                                            @if($notification->is_process)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $notification->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- Additional Info -->
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Additional Information</h4>
                                </div>
                                <div class="panel-body">
                                    @if($notification->subtype)
                                        <p><strong>Subtype:</strong> {{ $notification->subtype }}</p>
                                    @endif
                                    @if($notification->notification_uuid)
                                        <p><strong>Notification UUID:</strong> {{ $notification->notification_uuid }}</p>
                                    @endif
                                    @if($notification->environment)
                                        <p><strong>Environment:</strong> {{ $notification->environment }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Info -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Transaction Info</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="json-data">{{ json_encode(json_decode($notification->transaction_info), JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Renewal Info -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Renewal Info</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="json-data">{{ json_encode(json_decode($notification->renewal_info), JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payload -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Payload</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="json-data">{{ json_encode(json_decode($notification->payload), JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Raw Data -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Raw Data</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="raw-data">{{ $notification->data }}</div>
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
.panel-info {
    border: 1px solid #bce8f1;
}
.panel-info .panel-heading {
    background-color: #d9edf7;
    border-bottom: 1px solid #bce8f1;
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
.text-success {
    color: #28a745;
}
.text-danger {
    color: #dc3545;
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
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.json-data {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    padding: 1rem;
    font-size: 0.875rem;
    line-height: 1.4;
    color: #495057;
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 400px;
    overflow-y: auto;
}
.raw-data {
    border: 1px solid #ddd;
    overflow: auto;
    padding: 5px;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    font-family: monospace;
    font-size: 0.875rem;
    line-height: 1.4;
    color: #495057;
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 400px;
    overflow-y: auto;
}
</style>
@endsection
