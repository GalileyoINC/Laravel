@extends('layouts.app')

@section('title', 'Apple App Transaction Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Apple App Transaction #{{ $transaction->id }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('apple.app-transactions') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Transaction Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $transaction->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <td>{{ $transaction->transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $transaction->status === 'SUCCESS' ? 'success' : ($transaction->status === 'FAILED' ? 'danger' : 'warning') }}">
                                                {{ $transaction->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Error</th>
                                        <td>{{ $transaction->error ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>User ID</th>
                                        <td>{{ $transaction->id_user }}</td>
                                    </tr>
                                    <tr>
                                        <th>Is Process</th>
                                        <td>
                                            @if($transaction->is_process)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Apple Created At</th>
                                        <td>{{ $transaction->apple_created_at ? $transaction->apple_created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- Actions -->
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Actions</h4>
                                </div>
                                <div class="panel-body">
                                    @if(!$transaction->is_process)
                                        <form method="POST" action="{{ route('apple.process-transaction', $transaction) }}" class="mb-2">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Are you sure you want to process this transaction?')">
                                                <i class="fas fa-play"></i> Process Transaction
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('apple.retry-transaction', $transaction) }}" class="mb-2">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-block" onclick="return confirm('Are you sure you want to retry this transaction?')">
                                                <i class="fas fa-redo"></i> Retry Transaction
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Data -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Full Data</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="json-data">{{ json_encode($transaction->data, JSON_PRETTY_PRINT) }}</pre>
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
.label-success {
    background-color: #28a745;
    color: #fff;
}
.label-danger {
    background-color: #dc3545;
    color: #fff;
}
.label-warning {
    background-color: #ffc107;
    color: #212529;
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
.btn-block {
    display: block;
    width: 100%;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-warning {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
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
