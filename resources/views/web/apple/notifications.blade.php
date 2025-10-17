@extends('web.layouts.app')

@section('title', 'Apple Notifications - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Apple Notifications</h3>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="notification_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="INITIAL_BUY" {{ request('notification_type') == 'INITIAL_BUY' ? 'selected' : '' }}>INITIAL_BUY</option>
                                <option value="CANCEL" {{ request('notification_type') == 'CANCEL' ? 'selected' : '' }}>CANCEL</option>
                                <option value="RENEWAL" {{ request('notification_type') == 'RENEWAL' ? 'selected' : '' }}>RENEWAL</option>
                                <option value="INTERACTIVE_RENEWAL" {{ request('notification_type') == 'INTERACTIVE_RENEWAL' ? 'selected' : '' }}>INTERACTIVE_RENEWAL</option>
                                <option value="DID_CHANGE_RENEWAL_PREF" {{ request('notification_type') == 'DID_CHANGE_RENEWAL_PREF' ? 'selected' : '' }}>DID_CHANGE_RENEWAL_PREF</option>
                                <option value="DID_CHANGE_RENEWAL_STATUS" {{ request('notification_type') == 'DID_CHANGE_RENEWAL_STATUS' ? 'selected' : '' }}>DID_CHANGE_RENEWAL_STATUS</option>
                                <option value="DID_FAIL_TO_RENEW" {{ request('notification_type') == 'DID_FAIL_TO_RENEW' ? 'selected' : '' }}>DID_FAIL_TO_RENEW</option>
                                <option value="DID_RENEW" {{ request('notification_type') == 'DID_RENEW' ? 'selected' : '' }}>DID_RENEW</option>
                                <option value="REFUND" {{ request('notification_type') == 'REFUND' ? 'selected' : '' }}>REFUND</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="transaction_id" class="form-control" placeholder="Transaction ID" value="{{ request('transaction_id') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="original_transaction_id" class="form-control" placeholder="Original Transaction ID" value="{{ request('original_transaction_id') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="is_process" class="form-control">
                                <option value="">All Process Status</option>
                                <option value="1" {{ request('is_process') == '1' ? 'selected' : '' }}>Processed</option>
                                <option value="0" {{ request('is_process') == '0' ? 'selected' : '' }}>Not Processed</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_from" class="form-control" value="{{ request('created_at_from') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="created_at_to" class="form-control" value="{{ request('created_at_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('web.apple.notifications') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('web.apple.export-notifications', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>Notification Type</th>
                                    <th>Transaction ID</th>
                                    <th>Original Transaction ID</th>
                                    <th>Is Process</th>
                                    <th>Created At</th>
                                    <th class="action-column-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->id }}</td>
                                        <td>
                                            <span class="label label-info">{{ $notification->notification_type }}</span>
                                        </td>
                                        <td>{{ $notification->transaction_id }}</td>
                                        <td>{{ $notification->original_transaction_id }}</td>
                                        <td>
                                            @if($notification->is_process)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td>{{ $notification->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('web.apple.notification-show', $notification) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No notifications found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $notifications->appends(request()->query())->links() }}
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
.grid__id {
    width: 60px;
}
.action-column-1 {
    width: 100px;
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
