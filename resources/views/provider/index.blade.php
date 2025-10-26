@extends('layouts.app')

@section('title', 'Providers - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Providers</h3>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <x-table-filter 
                        :title="'Providers'" 
                        :data="$providers"
                        :columns="[
                            TableFilterHelper::textColumn('Name'),
                            TableFilterHelper::textColumn('Email'),
                            TableFilterHelper::selectColumn('Is Satellite', ['1' => 'Satellite', '0' => 'Not Satellite']),
                            TableFilterHelper::textColumn('Country'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-3'),
                        ]"
                    >
                        <x-slot name="headerActions">
                            <a href="{{ route('provider.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Create Provider
                            </a>
                            <a href="{{ route('provider.export', request()->query()) }}" class="btn btn-info">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        </x-slot>

                        @forelse($providers as $provider)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $provider->name }}</td>
                                <td @dataColumn(1)>{{ $provider->email ?? '-' }}</td>
                                <td @dataColumn(2) @dataValue($provider->is_satellite ? '1' : '0')>
                                    @if($provider->is_satellite)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td @dataColumn(3)>{{ $provider->country ?? '-' }}</td>
                                <td @dataColumn(4)>{{ $provider->created_at->format('M d, Y') }}</td>
                                <td @dataColumn(5)>
                                    <div class="btn-group">
                                        <a href="{{ route('provider.show', $provider) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </a>
                                        <a href="{{ route('provider.edit', $provider) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit fa-fw"></i>
                                        </a>
                                        <form method="POST" action="{{ route('provider.destroy', $provider) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this provider?')">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No providers found.</td>
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
.action-column-3 {
    width: 200px;
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
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
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
