@extends('web.layouts.app')

@section('title', 'API Log Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">API Log #{{ $apiLog->id }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.logs.api-logs') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- API Log Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $apiLog->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Key</th>
                                        <td>{{ $apiLog->key }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $apiLog->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- Actions -->
                            @if(auth()->user()->isSuper())
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Admin Actions</h4>
                                    </div>
                                    <div class="panel-body">
                                        <form method="POST" action="{{ route('web.logs.delete-by-key', $apiLog->key) }}" class="mb-2">
                                            @csrf
                                            <button type="submit" class="btn btn-admin" onclick="return confirm('Are you sure you want to delete all logs with this key?')">
                                                <i class="fas fa-trash"></i> Delete by Key
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Value Content -->
                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Value Content</h4>
                                </div>
                                <div class="panel-body">
                                    @if(in_array($apiLog->key, ['Tsunami_NTWC', 'Tsunami_PTWC']))
                                        <div class="tsunami-data">
                                            {!! $apiLog->value !!}
                                        </div>
                                    @elseif(stripos($apiLog->key, 'Weather') !== false && stripos($apiLog->key, 'Predict') === false)
                                        @if($apiLog->value)
                                            @php
                                                $weatherData = json_decode($apiLog->value, true);
                                            @endphp
                                            @if($weatherData)
                                                <div class="weather-data">
                                                    <h5>{{ $weatherData['properties']['areaDesc'] ?? 'Weather Data' }}</h5>
                                                    <p><strong>Headline:</strong> {{ $weatherData['properties']['headline'] ?? '' }}</p>
                                                    <p><strong>Description:</strong> {{ $weatherData['properties']['description'] ?? '' }}</p>
                                                    <p><strong>Severity:</strong> {{ $weatherData['properties']['severity'] ?? '' }}</p>
                                                    <p><strong>Urgency:</strong> {{ $weatherData['properties']['urgency'] ?? '' }}</p>
                                                    <p><strong>Areas:</strong> {{ $weatherData['properties']['areas'] ?? '' }}</p>
                                                </div>
                                            @else
                                                <pre class="json-content">{{ $apiLog->value }}</pre>
                                            @endif
                                        @else
                                            <p class="text-muted">No value content</p>
                                        @endif
                                    @else
                                        <pre class="json-content">{{ $apiLog->value }}</pre>
                                    @endif
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
.panel-warning {
    border: 1px solid #faebcc;
}
.panel-warning .panel-heading {
    background-color: #fcf8e3;
    border-bottom: 1px solid #faebcc;
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
.btn-admin {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    text-decoration: none;
}
.btn:hover {
    text-decoration: none;
}
.btn-admin:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
}
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
}
.tsunami-data {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 0.25rem;
    padding: 1rem;
}
.weather-data {
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    border-radius: 0.25rem;
    padding: 1rem;
}
.json-content {
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
.text-muted {
    color: #6c757d;
}
</style>
@endsection
