@extends('layouts.app')

@section('title', 'Message Schedules - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Message Schedules</h3>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('sms-schedule.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Message Schedules'" 
                        :data="$smsSchedules"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::selectColumn('Purpose', $purposes),
                            TableFilterHelper::textColumn('Sender'),
                            TableFilterHelper::textColumn('Subscription'),
                            TableFilterHelper::textColumn('Private Feed'),
                            TableFilterHelper::selectColumn('Status', $statuses),
                            TableFilterHelper::textColumn('Body'),
                            TableFilterHelper::textColumn('Sended At'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::textColumn('Updated At'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-1'),
                        ]"
                    >
                        @forelse($smsSchedules as $smsSchedule)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $smsSchedule->id }}</td>
                                <td @dataColumn(1) @dataValue((string) $smsSchedule->purpose)>{{ $purposes[$smsSchedule->purpose] ?? $smsSchedule->purpose }}</td>
                                <td @dataColumn(2)>
                                    @if($smsSchedule->user)
                                        User: {{ $smsSchedule->user->first_name }} {{ $smsSchedule->user->last_name }}
                                    @elseif($smsSchedule->staff)
                                        Staff: {{ $smsSchedule->staff->username }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td @dataColumn(3)>{{ $smsSchedule->subscription ? $smsSchedule->subscription->name : '-' }}</td>
                                <td @dataColumn(4)>{{ $smsSchedule->followerList ? $smsSchedule->followerList->name : '-' }}</td>
                                <td @dataColumn(5) @dataValue((string) $smsSchedule->status)>
                                    @if($smsSchedule->id_sms_pool)
                                        <a href="{{ route('sms-pool.show', $smsSchedule->id_sms_pool) }}">
                                            {{ $statuses[$smsSchedule->status] ?? $smsSchedule->status }}
                                        </a>
                                    @else
                                        {{ $statuses[$smsSchedule->status] ?? $smsSchedule->status }}
                                    @endif
                                </td>
                                <td @dataColumn(6)>{{ Str::limit($smsSchedule->body, 50) }}</td>
                                <td @dataColumn(7)>{{ $smsSchedule->sended_at ? $smsSchedule->sended_at->format('M d, Y H:i') : '-' }}</td>
                                <td @dataColumn(8)>{{ $smsSchedule->created_at->format('M d, Y') }}</td>
                                <td @dataColumn(9)>{{ $smsSchedule->updated_at->format('M d, Y') }}</td>
                                <td @dataColumn(10)>
                                    <div class="btn-group">
                                        <a href="{{ route('sms-schedule.show', $smsSchedule) }}" class="btn btn-xs btn-info">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No SMS schedules found.</td>
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
    background-color: #f8f9fa;
    font-weight: 600;
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
.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
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
.text-center {
    text-align: center;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
@endsection
