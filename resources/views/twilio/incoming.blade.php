@extends('layouts.app')

@section('title', 'Twilio Incoming Messages - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Incomings</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('twilio.create-incoming') }}" class="btn btn-success btn-sm JS__load_in_modal me-1">
                            <i class="fas fa-plus"></i> Create
                        </a>
                        <a href="{{ route('twilio.export-incoming', request()->query()) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="number" class="form-control form-control-sm" placeholder="Phone Number" value="{{ request('number') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="created_at_from" class="form-control form-control-sm" placeholder="From" value="{{ request('created_at_from') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="created_at_to" class="form-control form-control-sm" placeholder="To" value="{{ request('created_at_to') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm me-1">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('twilio.incoming') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>Number</th>
                                    <th>Body</th>
                                    <th>Created At</th>
                                    <th class="action-column-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incoming as $message)
                                    <tr>
                                        <td>{{ $message->id }}</td>
                                        <td>
                                            @if($message->number)
                                                {{ $message->number }}
                                            @elseif($message->message && isset($message->message['From']))
                                                {{ $message->message['From'] }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($message->body)
                                                {{ Str::limit($message->body, 50) }}
                                            @elseif($message->message && isset($message->message['Body']))
                                                {{ Str::limit($message->message['Body'], 50) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $message->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('twilio.incoming-show', $message) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No incoming messages found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $incoming->appends(request()->query())->links() }}
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
