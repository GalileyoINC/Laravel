@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">News #{{ $news->id }}</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('news.edit', $news) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i> Edit
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $news->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $news->name }}</td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td>{{ $news->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td>{{ $news->slug ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Meta Keywords</th>
                    <td>{{ $news->meta_keywords ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Meta Description</th>
                    <td>{{ $news->meta_description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Image</th>
                    <td>
                        @if($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->name }}" style="max-width: 300px;">
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($news->status == 1)
                            <span class="label label-success">Published</span>
                        @else
                            <span class="label label-default">Draft</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $news->created_at->format('M d, Y h:i a') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $news->updated_at->format('M d, Y h:i a') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="box-footer">
        <a href="{{ route('news.content', $news) }}" class="btn btn-warning">
            <i class="fas fa-file-alt"></i> Manage Content
        </a>
        <a href="{{ route('news.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection
