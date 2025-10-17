@extends('web.layouts.app')

@section('title', 'IEX Webhook Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">IEX Webhook: {{ $webhook->name }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.iex.webhooks') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Webhook Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $webhook->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>IEX ID</th>
                                        <td>{{ $webhook->iex_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event</th>
                                        <td>
                                            <span class="label label-info">{{ $webhook->event }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Set</th>
                                        <td>{{ $webhook->set }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $webhook->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $webhook->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $webhook->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- Additional Info -->
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Webhook Information</h4>
                                </div>
                                <div class="panel-body">
                                    <p><strong>Event Type:</strong> {{ $webhook->event }}</p>
                                    <p><strong>Data Set:</strong> {{ $webhook->set }}</p>
                                    <p><strong>Webhook Name:</strong> {{ $webhook->name }}</p>
                                    <p><strong>IEX ID:</strong> {{ $webhook->iex_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Webhook Data -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Webhook Data</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="json-data">{{ json_encode($webhook->data, JSON_PRETTY_PRINT) }}</pre>
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
</style>
@endsection
