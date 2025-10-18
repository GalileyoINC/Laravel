@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Email Template #{{ $template->id }}</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('email-template.edit', $template) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i> Edit
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $template->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $template->name }}</td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td>{{ $template->subject }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $template->type_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>From Email</th>
                    <td>{{ $template->from_email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>From Name</th>
                    <td>{{ $template->from_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Body</th>
                    <td>
                        <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9;">
                            {!! $template->body !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Is Active</th>
                    <td>
                        @if($template->is_active)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $template->created_at->format('M d, Y h:i a') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $template->updated_at->format('M d, Y h:i a') }}</td>
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
