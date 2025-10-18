@extends('layouts.app')

@section('title', 'Email Pools - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Email Pools</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('email-pool-archive.index') }}" class="btn btn-info">
                            <i class="fas fa-archive"></i> Archive
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="immediate" {{ request('type') == 'immediate' ? 'selected' : '' }}>Immediate</option>
                                <option value="later" {{ request('type') == 'later' ? 'selected' : '' }}>Later</option>
                                <option value="background" {{ request('type') == 'background' ? 'selected' : '' }}>Background</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_from" class="form-control" value="{{ request('created_at_from') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_to" class="form-control" value="{{ request('created_at_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('email-pool.index') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('email-pool.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Reply</th>
                                    <th>BCC</th>
                                    <th>Subject</th>
                                    <th>Created At</th>
                                    <th class="action-column-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emailPools as $emailPool)
                                    <tr>
                                        <td>{{ $emailPool->id }}</td>
                                        <td>
                                            @if($emailPool->type === 'immediate')
                                                <span class="label label-success">Immediate</span>
                                            @elseif($emailPool->type === 'later')
                                                <span class="label label-warning">Later</span>
                                            @elseif($emailPool->type === 'background')
                                                <span class="label label-info">Background</span>
                                            @else
                                                <span class="label label-default">{{ ucfirst($emailPool->type) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($emailPool->status === 'sent')
                                                <span class="label label-success">Sent</span>
                                            @elseif($emailPool->status === 'pending')
                                                <span class="label label-warning">Pending</span>
                                            @elseif($emailPool->status === 'failed')
                                                <span class="label label-danger">Failed</span>
                                            @elseif($emailPool->status === 'cancelled')
                                                <span class="label label-default">Cancelled</span>
                                            @else
                                                <span class="label label-info">{{ ucfirst($emailPool->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $emailPool->from }}</td>
                                        <td>
                                            @php
                                                $toArray = json_decode($emailPool->to, true);
                                                $toEmails = is_array($toArray) ? array_keys($toArray) : [];
                                            @endphp
                                            {{ implode(', ', array_slice($toEmails, 0, 3)) }}
                                            @if(count($toEmails) > 3)
                                                <span class="text-muted">(+{{ count($toEmails) - 3 }} more)</span>
                                            @endif
                                        </td>
                                        <td>{{ $emailPool->reply ?: '-' }}</td>
                                        <td>{{ $emailPool->bcc ?: '-' }}</td>
                                        <td>{{ Str::limit($emailPool->subject, 50) }}</td>
                                        <td>{{ $emailPool->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('email-pool.show', $emailPool) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <form method="POST" action="{{ route('email-pool.destroy', $emailPool) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No email pools found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $emailPools->appends(request()->query())->links() }}
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
.label-info {
    background-color: #17a2b8;
    color: #fff;
}
.label-default {
    background-color: #6c757d;
    color: #fff;
}
.text-muted {
    color: #6c757d;
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
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
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
