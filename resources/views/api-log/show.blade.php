@extends('layouts.app')

@section('title', 'API Log #' . $apiLog->id . ' - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">API Log Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">ID</th>
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
                    </div>

                    @if(stripos($apiLog->key, 'Weather') !== false && stripos($apiLog->key, 'Predict') === false)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Weather Data</h4>
                                <div class="panel-body">
                                    @php $json = json_decode($apiLog->value, true); @endphp
                                    @if(!empty($json['features']))
                                        @foreach($json['features'] as $feature)
                                            @php $zips = \App\Services\WeatherGov::getZips($feature); @endphp
                                            
                                            <div class="weather-feature">
                                                <strong>{{ $feature['properties']['event'] ?? 'N/A' }}:</strong>
                                                
                                                @if(empty($feature['geometry']['type']) || $feature['geometry']['type'] != 'Polygon')
                                                    <span class="text-danger">NO Polygon coordinates</span>
                                                @endif
                                                
                                                @if(empty($zips))
                                                    No zips found
                                                @else
                                                    {{ implode(' ', $zips) }}
                                                @endif
                                                <hr>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Value</h4>
                            @if(in_array($apiLog->key, ['Tsunami_NTWC', 'Tsunami_PTWC']))
                                <div class="tsunami-data">
                                    {{ $apiLog->value }}
                                </div>
                            @else
                                <pre class="json-data">{{ json_encode(json_decode($apiLog->value), JSON_PRETTY_PRINT) }}</pre>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('api-log.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back to API Logs
                            </a>
                            @if(auth()->user()->isSuper())
                                <form method="POST" action="{{ route('api-log.delete-by-key', $apiLog) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete all logs with this key?')">
                                        <i class="fas fa-trash-alt"></i> Delete All by Key
                                    </button>
                                </form>
                            @endif
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
}
.panel-body {
    padding: 15px;
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
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
.weather-feature {
    margin-bottom: 1rem;
}
.text-danger {
    color: #dc3545;
}
.tsunami-data {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
    white-space: pre-wrap;
    font-family: monospace;
}
.json-data {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
    white-space: pre-wrap;
    font-family: monospace;
    font-size: 0.875rem;
    max-height: 500px;
    overflow-y: auto;
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
    margin-right: 0.5rem;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn:hover {
    text-decoration: none;
}
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
}
.btn-danger:hover {
    color: #fff;
    background-color: #c82333;
    border-color: #bd2130;
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
