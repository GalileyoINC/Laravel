@extends('web.layouts.app')

@section('title', 'Twilio Incoming Message Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Twilio Incoming Message #{{ $incoming->id }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.twilio.incoming') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Message Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $incoming->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Number</th>
                                        <td>{{ $incoming->number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Body</th>
                                        <td>{{ $incoming->body }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $incoming->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $incoming->updated_at->format('Y-m-d H:i:s') }}</td>
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
                                    <a href="{{ route('web.twilio.edit-incoming', $incoming) }}" class="btn btn-primary JS__load_in_modal mb-2">
                                        <i class="fas fa-pen-fancy"></i> Update
                                    </a>
                                    <form method="POST" action="{{ route('web.twilio.delete-incoming', $incoming) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Data -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Message Data</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="json-data">{{ json_encode($incoming->message, JSON_PRETTY_PRINT) }}</pre>
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
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
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
