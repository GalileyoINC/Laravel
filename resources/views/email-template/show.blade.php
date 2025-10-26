@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Email Template #{{ $emailTemplate->id }}</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('email-template.edit', $emailTemplate) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i> Edit
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $emailTemplate->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $emailTemplate->name }}</td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td>{{ $emailTemplate->subject }}</td>
                </tr>
                <tr>
                    <th>From</th>
                    <td>{{ $emailTemplate->from ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Body</th>
                    <td>
                        <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9;">
                            {!! $emailTemplate->body !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $emailTemplate->created_at?->format('M d, Y h:i a') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $emailTemplate->updated_at?->format('M d, Y h:i a') ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="box-footer">
        <a href="{{ route('email-template.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection
