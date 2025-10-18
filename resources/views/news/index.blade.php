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
        <div class="summary" style="margin: 0 0 10px 0;">
            @if($news->total() > 0)
                Showing <b>{{ $news->firstItem() }}-{{ $news->lastItem() }}</b> of <b>{{ $news->total() }}</b> items.
            @else
                Showing <b>0-0</b> of <b>0</b> items.
            @endif
        </div>
        <div class="table-responsive">
            <form method="GET" id="filters-form"></form>
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
                    <tr class="filters">
                        <td><input type="text" class="form-control" name="id" value="{{ request('id') }}" form="filters-form"></td>
                        <td><input type="text" class="form-control" name="name" value="{{ request('name') }}" form="filters-form"></td>
                        <td><input type="text" class="form-control" name="title" value="{{ request('title') }}" form="filters-form"></td>
                        <td>
                            <select class="form-control" name="status" form="filters-form">
                                <option value=""></option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </td>
                        <td>
                            <input type="date" class="form-control" name="created_at" value="{{ request('created_at') }}" form="filters-form">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                            <a href="{{ route('news.index') }}" class="btn btn-default ml-2">Clear</a>
                        </td>
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
