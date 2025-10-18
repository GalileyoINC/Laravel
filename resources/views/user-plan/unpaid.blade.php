@extends('layouts.app')

@section('title', 'Users who are overdue for their next payment - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ $title ?? 'Users who are overdue for their next payment' }}</span>
                        <div>
                            <!-- Search Form -->
                            <form method="GET" action="{{ route('user-plan.unpaid') }}" class="d-inline">
                                <div class="form-inline">
                                    <label class="mr-2">Unpaid bills for the last</label>
                                    <input type="number" name="exp_date" class="form-control mr-2" style="width: 70px;" value="{{ $expDate }}">
                                    <label class="mr-2">days</label>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <input type="hidden" name="exp_date" value="{{ $expDate }}">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="id_service" class="form-control">
                                <option value="">All Services</option>
                                @foreach($services as $id => $name)
                                    <option value="{{ $id }}" {{ request('id_service') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="pay_interval" class="form-control">
                                <option value="">All Pay Intervals</option>
                                <option value="1" {{ request('pay_interval') == '1' ? 'selected' : '' }}>Monthly</option>
                                <option value="3" {{ request('pay_interval') == '3' ? 'selected' : '' }}>Quarterly</option>
                                <option value="6" {{ request('pay_interval') == '6' ? 'selected' : '' }}>Semi-Annual</option>
                                <option value="12" {{ request('pay_interval') == '12' ? 'selected' : '' }}>Annual</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('user-plan.unpaid') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('user-plan.export-unpaid', array_merge(request()->query(), ['exp_date' => $expDate])) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
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
                                @forelse($userPlans as $userPlan)
                                    <tr>
                                        <td>{{ $userPlan->id }}</td>
                                        <td>{{ $userPlan->user ? $userPlan->user->first_name : '-' }}</td>
                                        <td>{{ $userPlan->user ? $userPlan->user->last_name : '-' }}</td>
                                        <td>
                                            @if($userPlan->user)
                                                <a href="mailto:{{ $userPlan->user->email }}">{{ $userPlan->user->email }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $userPlan->service ? $userPlan->service->name : '-' }}</td>
                                        <td>
                                            @switch($userPlan->pay_interval)
                                                @case(1)
                                                    Monthly
                                                    @break
                                                @case(3)
                                                    Quarterly
                                                    @break
                                                @case(6)
                                                    Semi-Annual
                                                    @break
                                                @case(12)
                                                    Annual
                                                    @break
                                                @default
                                                    {{ $userPlan->pay_interval }}
                                            @endswitch
                                        </td>
                                        <td>{{ $userPlan->exp_date->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('user.show', $userPlan->user) }}" class="btn btn-xs btn-info" target="_blank">
                                                    <i class="far fa-eye fa-fw"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No unpaid user plans found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $userPlans->appends(request()->query())->links() }}
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
.d-flex {
    display: flex;
}
.justify-content-between {
    justify-content: space-between;
}
.align-items-center {
    align-items: center;
}
.d-inline {
    display: inline;
}
</style>
@endsection
