@extends('web.layouts.app')

@section('title', 'Marketstack Indexes - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Marketstack Indexes</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.iex.create-marketstack') }}" class="btn btn-success JS__load_in_modal">
                            <i class="fas fa-plus"></i> Create Marketstack Index
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
                            <input type="text" name="country" class="form-control" placeholder="Country" value="{{ request('country') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="currency" class="form-control" placeholder="Currency" value="{{ request('currency') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="has_intraday" class="form-control">
                                <option value="">All Intraday</option>
                                <option value="1" {{ request('has_intraday') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('has_intraday') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="has_eod" class="form-control">
                                <option value="">All EOD</option>
                                <option value="1" {{ request('has_eod') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('has_eod') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="is_active" class="form-control">
                                <option value="">All Active</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('web.iex.marketstack') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('web.iex.export-marketstack', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>Name</th>
                                    <th>Symbol</th>
                                    <th>Country</th>
                                    <th>Currency</th>
                                    <th>Has Intraday</th>
                                    <th>Has EOD</th>
                                    <th>Is Active</th>
                                    <th class="action-column-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($indexes as $index)
                                    <tr>
                                        <td>{{ $index->id }}</td>
                                        <td>{{ $index->name }}</td>
                                        <td>{{ $index->symbol }}</td>
                                        <td>{{ $index->country }}</td>
                                        <td>{{ $index->currency }}</td>
                                        <td>
                                            @if($index->has_intraday)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($index->has_eod)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($index->is_active)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('web.iex.marketstack-show', $index) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('web.iex.edit-marketstack', $index) }}" class="btn btn-sm btn-primary JS__load_in_modal">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No indexes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $indexes->appends(request()->query())->links() }}
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
