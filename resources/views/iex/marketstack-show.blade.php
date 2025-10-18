@extends('layouts.app')

@section('title', 'Marketstack Index Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Marketstack Index: {{ $index->name }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('iex.marketstack') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Index Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $index->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $index->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Symbol</th>
                                        <td>{{ $index->symbol }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>{{ $index->country }}</td>
                                    </tr>
                                    <tr>
                                        <th>Currency</th>
                                        <td>{{ $index->currency }}</td>
                                    </tr>
                                    <tr>
                                        <th>Has Intraday</th>
                                        <td>
                                            @if($index->has_intraday)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Has EOD</th>
                                        <td>
                                            @if($index->has_eod)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Is Active</th>
                                        <td>
                                            @if($index->is_active)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- Actions -->
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Actions</h4>
                                </div>
                                <div class="panel-body">
                                    <a href="{{ route('iex.edit-marketstack', $index) }}" class="btn btn-primary JS__load_in_modal">
                                        <i class="fas fa-pen-fancy"></i> Update
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Data -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Full Data</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="json-data">{{ json_encode($index->full, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
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
.panel-info {
    border: 1px solid #bce8f1;
}
.panel-info .panel-heading {
    background-color: #d9edf7;
    border-bottom: 1px solid #bce8f1;
}
.text-success {
    color: #28a745;
}
.text-danger {
    color: #dc3545;
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
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
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
.json-data {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    padding: 1rem;
    font-size: 0.875rem;
    line-height: 1.4;
    color: #495057;
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 400px;
    overflow-y: auto;
}
</style>
@endsection
