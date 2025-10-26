@extends('layouts.app')

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
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('apple.export-notifications', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Apple Notifications'" 
                        :data="$notifications"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::selectColumn('Notification Type', [
                                'INITIAL_BUY' => 'INITIAL_BUY',
                                'CANCEL' => 'CANCEL',
                                'RENEWAL' => 'RENEWAL',
                                'INTERACTIVE_RENEWAL' => 'INTERACTIVE_RENEWAL',
                                'DID_CHANGE_RENEWAL_PREF' => 'DID_CHANGE_RENEWAL_PREF',
                                'DID_CHANGE_RENEWAL_STATUS' => 'DID_CHANGE_RENEWAL_STATUS',
                                'DID_FAIL_TO_RENEW' => 'DID_FAIL_TO_RENEW',
                                'DID_RENEW' => 'DID_RENEW',
                                'REFUND' => 'REFUND',
                            ]),
                            TableFilterHelper::textColumn('Transaction ID'),
                            TableFilterHelper::textColumn('Original Transaction ID'),
                            TableFilterHelper::textColumn('Is Process'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-1'),
                        ]"
                    >
                        @forelse($notifications as $notification)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $notification->id }}</td>
                                <td @dataColumn(1) @dataValue($notification->notification_type)>
                                    <span class="badge bg-info">{{ $notification->notification_type }}</span>
                                </td>
                                <td @dataColumn(2)>{{ $notification->transaction_id ?? '-' }}</td>
                                <td @dataColumn(3)>{{ $notification->original_transaction_id ?? '-' }}</td>
                                <td @dataColumn(4)>
                                    @if($notification->is_process)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td @dataColumn(5)>{{ $notification->created_at->format('Y-m-d H:i:s') }}</td>
                                <td @dataColumn(6)>
                                    <div class="btn-group">
                                        <a href="{{ route('apple.notification-show', ['notification' => $notification->id]) }}" class="btn btn-sm btn-info">
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
                    </x-table-filter>
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
