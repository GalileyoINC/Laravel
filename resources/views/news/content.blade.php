@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Manage Content for: {{ $news->name }}</h3>
    </div>
    
    <div class="box-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Manage multilingual content for this news article.
        </div>

        @if($news->news_contents && $news->news_contents->count() > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Language</th>
                        <th>Title</th>
                        <th>Content Preview</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($news->news_contents as $content)
                        <tr>
                            <td>{{ strtoupper($content->language ?? 'en') }}</td>
                            <td>{{ $content->title ?? 'N/A' }}</td>
                            <td>{{ \Str::limit(strip_tags($content->body ?? ''), 100) }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">No content available yet.</p>
        @endif
    </div>
    
    <div class="box-footer">
        <button class="btn btn-success">
            <i class="fas fa-plus"></i> Add Content
        </button>
        <a href="{{ route('news.show', $news) }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to News
        </a>
    </div>
</div>
@endsection
