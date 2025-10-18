@extends('layouts.app')

@section('title', 'SMS Pool Archive #' . $smsPoolArchive->id . ' - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">SMS Pool Archive Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">ID</th>
                                        <td>{{ $smsPoolArchive->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Purpose</th>
                                        <td>{{ $smsPoolArchive->purpose }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscription</th>
                                        <td>{{ $smsPoolArchive->subscription ? $smsPoolArchive->subscription->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Private Feed</th>
                                        <td>{{ $smsPoolArchive->followerList ? $smsPoolArchive->followerList->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sender</th>
                                        <td>
                                            @if($smsPoolArchive->user)
                                                User: {{ $smsPoolArchive->user->first_name }} {{ $smsPoolArchive->user->last_name }}
                                            @elseif($smsPoolArchive->staff)
                                                Staff: {{ $smsPoolArchive->staff->username }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $smsPoolArchive->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $smsPoolArchive->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Body</h4>
                            <div class="panel-body">
                                <pre>{{ $smsPoolArchive->body }}</pre>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('sms-pool-archive.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back to Archive
                            </a>
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
pre {
    display: block;
    padding: 9.5px;
    margin: 0 0 10px;
    font-size: 13px;
    line-height: 1.42857143;
    color: #333;
    word-break: break-all;
    word-wrap: break-word;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;
    white-space: pre-wrap;
    font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
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
.btn:hover {
    text-decoration: none;
}
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
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
