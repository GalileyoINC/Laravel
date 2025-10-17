@extends('web.layouts.app')

@section('title', 'Users who are overdue for their next payment - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>{{ $title }}</span>
                    <div class="pull-right">
                        @include('web.contract-line._unpaid_search', ['expDateDays' => $expDateDays])
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="id_service" class="form-control">
                                <option value="">All Services</option>
                                @foreach($services as $key => $value)
                                    <option value="{{ $key }}" {{ request('id_service') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="pay_interval" class="form-control">
                                <option value="">All Intervals</option>
                                @foreach($payIntervals as $key => $value)
                                    <option value="{{ $key }}" {{ request('pay_interval') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('web.contract-line.unpaid') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('web.contract-line.export-unpaid', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">User ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Service</th>
                                    <th>Pay Interval</th>
                                    <th>Exp Date</th>
                                    <th class="action-column-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contractLines as $contractLine)
                                    <tr>
                                        <td>{{ $contractLine->id_user }}</td>
                                        <td>{{ $contractLine->first_name }}</td>
                                        <td>{{ $contractLine->last_name }}</td>
                                        <td>{{ $contractLine->email }}</td>
                                        <td>{{ $contractLine->service_name }}</td>
                                        <td>
                                            @if($contractLine->pay_interval)
                                                {{ \App\Services\DateUtils::intervalToString($contractLine->pay_interval) }}
                                            @endif
                                        </td>
                                        <td>{{ $contractLine->exp_date ? $contractLine->exp_date->format('Y-m-d') : '' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('web.user.show', $contractLine->id_user) }}" class="btn btn-xs btn-info" target="_blank">
                                                    <i class="far fa-eye fa-fw"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No unpaid contract lines found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $contractLines->appends(request()->query())->links() }}
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
.pull-right {
    float: right;
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