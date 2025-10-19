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
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($emailPools instanceof \Illuminate\Contracts\Pagination\Paginator && $emailPools->total() > 0)
                            Showing <b>{{ $emailPools->firstItem() }}-{{ $emailPools->lastItem() }}</b> of <b>{{ $emailPools->total() }}</b> items.
                        @elseif(is_array($emailPools) && count($emailPools) > 0)
                            Showing <b>1-{{ count($emailPools) }}</b> of <b>{{ count($emailPools) }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('email-pool.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <form method="GET" id="filters-form"></form>
                        <table class="table table-striped table-bordered">
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
                                <tr class="filters">
                                    <td>
                                        <input type="text" class="form-control" name="id" form="filters-form" value="{{ request('id') }}">
                                    </td>
                                    <td>
                                        <select name="type" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            <option value="immediate" {{ request('type') == 'immediate' ? 'selected' : '' }}>Immediate</option>
                                            <option value="later" {{ request('type') == 'later' ? 'selected' : '' }}>Later</option>
                                            <option value="background" {{ request('type') == 'background' ? 'selected' : '' }}>Background</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="status" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="from" form="filters-form" value="{{ request('from') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="to_addr" form="filters-form" value="{{ request('to_addr') }}" placeholder="To">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="reply" form="filters-form" value="{{ request('reply') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="bcc" form="filters-form" value="{{ request('bcc') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="subject" form="filters-form" value="{{ request('subject') }}">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="created_at" form="filters-form" value="{{ request('created_at') }}">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                        <a href="{{ route('email-pool.index') }}" class="btn btn-default ml-2">Clear</a>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emailPools as $emailPool)
                                    <tr>
                                        <td>{{ is_array($emailPool) ? ($emailPool['id'] ?? '') : ($emailPool->id ?? '') }}</td>
                                        <td>
                                            @php $type = is_array($emailPool) ? ($emailPool['type'] ?? '') : ($emailPool->type ?? ''); @endphp
                                            @if($type === 'immediate')
                                                <span class="label label-success">Immediate</span>
                                            @elseif($type === 'later')
                                                <span class="label label-warning">Later</span>
                                            @elseif($type === 'background')
                                                <span class="label label-info">Background</span>
                                            @else
                                                <span class="label label-default">{{ ucfirst($type) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php $status = is_array($emailPool) ? ($emailPool['status'] ?? '') : ($emailPool->status ?? ''); @endphp
                                            @if($status === 'sent')
                                                <span class="label label-success">Sent</span>
                                            @elseif($status === 'pending')
                                                <span class="label label-warning">Pending</span>
                                            @elseif($status === 'failed')
                                                <span class="label label-danger">Failed</span>
                                            @elseif($status === 'cancelled')
                                                <span class="label label-default">Cancelled</span>
                                            @else
                                                <span class="label label-info">{{ ucfirst($status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ is_array($emailPool) ? ($emailPool['from'] ?? '') : ($emailPool->from ?? '') }}</td>
                                        <td>
                                            @php
                                                $toRaw = is_array($emailPool) ? ($emailPool['to'] ?? '') : ($emailPool->to ?? '');
                                                $toArray = is_string($toRaw) ? json_decode($toRaw, true) : (is_array($toRaw) ? $toRaw : []);
                                                $toEmails = is_array($toArray) ? array_keys($toArray) : [];
                                            @endphp
                                            {{ implode(', ', array_slice($toEmails, 0, 3)) }}
                                            @if(count($toEmails) > 3)
                                                <span class="text-muted">(+{{ count($toEmails) - 3 }} more)</span>
                                            @endif
                                        </td>
                                        <td>{{ (is_array($emailPool) ? ($emailPool['reply'] ?? '') : ($emailPool->reply ?? '')) ?: '-' }}</td>
                                        <td>{{ (is_array($emailPool) ? ($emailPool['bcc'] ?? '') : ($emailPool->bcc ?? '')) ?: '-' }}</td>
                                        <td>
                                            @php $subjectVal = is_array($emailPool) ? ($emailPool['subject'] ?? '') : ($emailPool->subject ?? ''); @endphp
                                            {{ Str::limit($subjectVal, 50) }}
                                        </td>
                                        <td>
                                            @php $created = is_array($emailPool) ? ($emailPool['created_at'] ?? null) : ($emailPool->created_at ?? null); @endphp
                                            @if($created instanceof \Illuminate\Support\Carbon)
                                                {{ $created->format('M d, Y') }}
                                            @elseif(!empty($created))
                                                {{ \Illuminate\Support\Carbon::parse($created)->format('M d, Y') }}
                                            @else
                                                
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @php $poolId = is_array($emailPool) ? ($emailPool['id'] ?? null) : ($emailPool->id ?? null); @endphp
                                                @if($poolId)
                                                <a href="{{ route('email-pool.show', ['email_pool' => $poolId]) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <form method="POST" action="{{ route('email-pool.destroy', ['email_pool' => $poolId]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                                @endif
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
                    @if($emailPools instanceof \Illuminate\Contracts\Pagination\Paginator)
                        <div class="d-flex justify-content-center">
                            {{ $emailPools->appends(request()->query())->links() }}
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
