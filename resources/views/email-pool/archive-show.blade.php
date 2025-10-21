@extends('layouts.app')

@section('title', 'Email Pool Archive Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Email Pool Archive #{{ $emailPool->id }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('email-pool-archive.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Archive
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Email Pool Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $emailPool->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($emailPool->status === 'sent')
                                                <span class="badge bg-success">Sent</span>
                                            @elseif($emailPool->status === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @elseif($emailPool->status === 'cancelled')
                                                <span class="badge bg-default">Cancelled</span>
                                            @else
                                                <span class="badge bg-info">{{ ucfirst($emailPool->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>To</th>
                                        <td>{{ $emailPool->to }}</td>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <td>{{ $emailPool->from }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reply</th>
                                        <td>{{ $emailPool->reply ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>BCC</th>
                                        <td>{{ $emailPool->bcc ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subject</th>
                                        <td>{{ $emailPool->subject }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $emailPool->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $emailPool->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- Archive Info -->
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Archive Information</h4>
                                </div>
                                <div class="panel-body">
                                    <p><strong>Status:</strong> Archived</p>
                                    <p><strong>Archive Date:</strong> {{ $emailPool->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p class="text-muted">This email has been moved to the archive and cannot be modified.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Plain Text Body -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Plain Text Body</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="email-body">{{ $emailPool->bodyPlain }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($emailPool->attachments && count($emailPool->attachments) > 0)
                        <div class="row">
                            <div class="col-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Attachments</h4>
                                    </div>
                                    <div class="panel-body">
                                        @foreach($emailPool->attachments as $attachment)
                                            <a href="{{ route('email-pool.attachment', $attachment->id) }}" class="btn btn-sm btn-outline-primary mr-2 mb-2">
                                                <i class="fas fa-paperclip"></i> {{ $attachment->file_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- HTML Body Preview -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">HTML Body Preview</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="{{ route('email-pool.view-body', $emailPool) }}"></iframe>
                                    </div>
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
.panel-warning {
    border: 1px solid #faebcc;
}
.panel-warning .panel-heading {
    background-color: #fcf8e3;
    border-bottom: 1px solid #faebcc;
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
.label-default {
    background-color: #6c757d;
    color: #fff;
}
.label-info {
    background-color: #17a2b8;
    color: #fff;
}
.text-muted {
    color: #6c757d;
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
.btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
}
.btn-outline-primary:hover {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.email-body {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    padding: 1rem;
    font-size: 0.875rem;
    line-height: 1.4;
    color: #495057;
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 300px;
    overflow-y: auto;
}
.embed-responsive {
    position: relative;
    display: block;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
}
.embed-responsive-item {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}
</style>
@endsection
