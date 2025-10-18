@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">News</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('news.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create News
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->title ?? 'N/A' }}</td>
                            <td>
                                @if($item->status == 1)
                                    <span class="label label-success">Published</span>
                                @else
                                    <span class="label label-default">Draft</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('news.show', $item) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('news.edit', $item) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="{{ route('news.content', $item) }}" class="btn btn-sm btn-warning" title="Content">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    <form action="{{ route('news.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No news found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($news, 'links'))
            <div class="text-center">
                {{ $news->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
