@extends('web.layouts.app')

@section('title', 'Email Pool Archive #' . $emailPoolArchive->id . ' - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Email Pool Archive Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">ID</th>
                                        <td>{{ $emailPoolArchive->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $emailPoolArchive->status }}</td>
                                    </tr>
                                    <tr>
                                        <th>To</th>
                                        <td>{{ $emailPoolArchive->to }}</td>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <td>{{ $emailPoolArchive->from }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reply</th>
                                        <td>{{ $emailPoolArchive->reply ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>BCC</th>
                                        <td>{{ $emailPoolArchive->bcc ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subject</th>
                                        <td>{{ $emailPoolArchive->subject }}</td>
                                    </tr>
                                    <tr>
                                        <th>Body Plain</th>
                                        <td>{{ $emailPoolArchive->bodyPlain ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $emailPoolArchive->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $emailPoolArchive->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($emailPoolArchive->attachments->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Attachments</h4>
                            <div class="panel-body">
                                @foreach($emailPoolArchive->attachments as $attachment)
                                    <a href="{{ route('web.email-pool-archive.attachment', $attachment) }}" class="btn btn-sm btn-outline-primary mr-2 mb-2">
                                        <i class="fas fa-paperclip"></i> {{ $attachment->file_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Email Body</h4>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="{{ route('web.email-pool-archive.view-body', $emailPoolArchive) }}"></iframe>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('web.email-pool-archive.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back to Archive
                            </a>
                            <form method="POST" action="{{ route('web.email-pool-archive.destroy', $emailPoolArchive) }}" style="display: inline;">
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
.embed-responsive {
    position: relative;
    display: block;
    width: 100%;
    padding: 0;
    overflow: hidden;
}
.embed-responsive::before {
    display: block;
    content: "";
}
.embed-responsive-16by9::before {
    padding-top: 56.25%;
}
.embed-responsive-item {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
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
.btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
    background-color: transparent;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
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
.btn-outline-primary:hover {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
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
