@extends('layouts.app')

@section('title', 'Promocodes - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar for Filters -->
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Filters</h4>
                </div>
                <div class="panel-body">
                    <form method="GET" action="{{ route('promocode.index') }}">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="discount" {{ request('type') == 'discount' ? 'selected' : '' }}>Discount</option>
                                <option value="trial" {{ request('type') == 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="influencer" {{ request('type') == 'influencer' ? 'selected' : '' }}>Influencer</option>
                                <option value="test" {{ request('type') == 'test' ? 'selected' : '' }}>Test</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="">All Status</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="active_from_from">Active From (From)</label>
                            <input type="date" name="active_from_from" id="active_from_from" class="form-control" value="{{ request('active_from_from') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="active_from_to">Active From (To)</label>
                            <input type="date" name="active_from_to" id="active_from_to" class="form-control" value="{{ request('active_from_to') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="active_to_from">Active To (From)</label>
                            <input type="date" name="active_to_from" id="active_to_from" class="form-control" value="{{ request('active_to_from') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="active_to_to">Active To (To)</label>
                            <input type="date" name="active_to_to" id="active_to_to" class="form-control" value="{{ request('active_to_to') }}">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        
                        <div class="form-group">
                            <a href="{{ route('promocode.index') }}" class="btn btn-default btn-block">Clear</a>
                        </div>
                        
                        <div class="form-group">
                            <a href="{{ route('promocode.export', request()->query()) }}" class="btn btn-success btn-block">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Promocodes</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('promocode.create') }}" class="btn btn-success JS__load_in_modal">
                            <i class="fas fa-plus"></i> Create New Promocode
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp
                    <x-table-filter 
                        :title="'Promocodes'" 
                        :data="$promocodes"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::selectColumn('Type', [
                                'discount' => 'Discount',
                                'trial' => 'Trial',
                                'influencer' => 'Influencer',
                                'test' => 'Test',
                            ]),
                            TableFilterHelper::textColumn('Text'),
                            TableFilterHelper::textColumn('Discount'),
                            TableFilterHelper::selectColumn('Is Active', ['1' => 'Active', '0' => 'Inactive']),
                            TableFilterHelper::textColumn('Active From'),
                            TableFilterHelper::textColumn('Active To'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-2'),
                        ]"
                    >
                        @forelse($promocodes as $promocode)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $promocode->id }}</td>
                                <td @dataColumn(1) @dataValue($promocode->type)>
                                    @if($promocode->type === 'discount')
                                        <span class="label label-primary">Discount</span>
                                    @elseif($promocode->type === 'trial')
                                        <span class="label label-info">Trial</span>
                                    @elseif($promocode->type === 'influencer')
                                        <span class="label label-warning">Influencer</span>
                                    @elseif($promocode->type === 'test')
                                        <span class="label label-default">Test</span>
                                    @else
                                        <span class="label label-default">{{ ucfirst($promocode->type) }}</span>
                                    @endif
                                </td>
                                <td @dataColumn(2)>{{ $promocode->text }}</td>
                                <td @dataColumn(3)>{{ $promocode->discount }}%</td>
                                <td @dataColumn(4) @dataValue($promocode->is_active ? '1' : '0')>
                                    @if($promocode->is_active)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td @dataColumn(5)>{{ $promocode->active_from->format('M d, Y') }}</td>
                                <td @dataColumn(6)>{{ $promocode->active_to->format('M d, Y') }}</td>
                                <td @dataColumn(7)>
                                    <div class="btn-group">
                                        <a href="{{ route('promocode.edit', $promocode) }}" class="btn btn-xs btn-success JS__load_in_modal">
                                            <i class="fas fa-pen-fancy fa-fw"></i>
                                        </a>
                                        <form method="POST" action="{{ route('promocode.destroy', $promocode) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this promocode?')">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No promocodes found.</td>
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
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}
.panel-heading-actions {
    display: flex;
    gap: 10px;
}
.panel-body {
    padding: 15px;
}
.panel-title {
    margin: 0;
    font-size: 16px;
    color: #333;
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
.label-primary {
    background-color: #007bff;
    color: #fff;
}
.label-info {
    background-color: #17a2b8;
    color: #fff;
}
.label-warning {
    background-color: #ffc107;
    color: #212529;
}
.label-default {
    background-color: #6c757d;
    color: #fff;
}
.label-success {
    background-color: #28a745;
    color: #fff;
}
.label-danger {
    background-color: #dc3545;
    color: #fff;
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
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
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
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: inline-block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}
.btn-block {
    display: block;
    width: 100%;
}
.text-center {
    text-align: center;
}
</style>
@endsection