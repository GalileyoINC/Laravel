@extends('layouts.app')

@section('title', 'Active Record Logs - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Active Record Logs</h3>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('active-record-log.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Active Record Logs'" 
                        :data="$activeRecordLogs"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::textColumn('User'),
                            TableFilterHelper::textColumn('Staff'),
                            TableFilterHelper::selectColumn('Action Type', $actionTypes),
                            TableFilterHelper::textColumn('Model'),
                            TableFilterHelper::textColumn('ID Model'),
                            TableFilterHelper::textColumn('Field'),
                            TableFilterHelper::textColumn('Changes'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-2'),
                        ]"
                    >
                        @forelse($activeRecordLogs as $log)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $log->id }}</td>
                                <td @dataColumn(1)>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                <td @dataColumn(2)>
                                    @if($log->user)
                                        <a href="{{ route('user.show', $log->user) }}">
                                            {{ $log->user->getFullName() }} ({{ $log->user->id }})
                                        </a>
                                    @endif
                                </td>
                                <td @dataColumn(3)>
                                    @if($log->staff)
                                        {{ $log->staff->username }} ({{ $log->staff->id }})
                                    @endif
                                </td>
                                <td @dataColumn(4) @dataValue((string) $log->action_type)>{{ $actionTypes[$log->action_type] ?? $log->action_type }}</td>
                                <td @dataColumn(5)>{{ $log->model }}</td>
                                <td @dataColumn(6)>{{ $log->id_model }}</td>
                                <td @dataColumn(7)>{{ $log->field }}</td>
                                <td @dataColumn(8)>
                                    @if(!empty($log->changes))
                                        @if(count($log->changes) > 2)
                                            <div class="shrink_field">
                                        @else
                                            <div>
                                        @endif
                                                <dl class="dl-horizontal" style="margin-bottom:0">
                                                    @foreach($log->changes as $key => $value)
                                                        <dt>{{ $key }}</dt>
                                                        <dd>{{ is_array($value) ? 'JSON' : $value }}</dd>
                                                    @endforeach
                                                </dl>
                                            </div>
                                    @endif
                                </td>
                                <td @dataColumn(9)>
                                    <div class="btn-group">
                                        <!-- No specific actions for active record logs -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No active record logs found.</td>
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
.action-column-2 {
    width: 150px;
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
.shrink_field {
    cursor: pointer;
    max-height: 100px;
    overflow: hidden;
    transition: max-height 0.3s ease;
}
.shrink_field--open {
    max-height: none;
}
.dl-horizontal {
    margin-bottom: 1rem;
}
.dl-horizontal dt {
    float: left;
    width: 160px;
    overflow: hidden;
    clear: left;
    text-align: right;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: 600;
}
.dl-horizontal dd {
    margin-left: 180px;
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

<script>
$(document).ready(function () {
    $(".shrink_field").click(function () {
        $(this).toggleClass('shrink_field--open')
    })
})
</script>
@endsection
