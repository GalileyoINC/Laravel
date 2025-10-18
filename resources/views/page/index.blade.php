@extends('layouts.app')

@section('title', 'Pages - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pages</h1>
        <a href="{{ route('page.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Create Page
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Summary -->
            <div class="summary" style="margin-bottom:10px;">
                @if($pages->total() > 0)
                    Showing <b>{{ $pages->firstItem() }}-{{ $pages->lastItem() }}</b> of <b>{{ $pages->total() }}</b> items.
                @else
                    Showing <b>0-0</b> of <b>0</b> items.
                @endif
            </div>

            <div class="table-responsive">
                <form method="GET" id="filters-form"></form>
                <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="grid__id">ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="action-column-1">Actions</th>
                        </tr>
                        <tr class="filters">
                            <td><input type="text" class="form-control" name="id" form="filters-form" value="{{ request('id') }}"></td>
                            <td><input type="text" class="form-control" name="name" form="filters-form" value="{{ request('name') }}"></td>
                            <td><input type="text" class="form-control" name="slug" form="filters-form" value="{{ request('slug') }}"></td>
                            <td>
                                <select name="status" class="form-control" form="filters-form">
                                    <option value=""></option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </td>
                            <td>
                                <input type="date" class="form-control" name="created_at" form="filters-form" value="{{ request('created_at') }}">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                <a href="{{ route('page.index') }}" class="btn btn-default ml-2">Clear</a>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>{{ $page->name }}</td>
                            <td>{{ $page->slug }}</td>
                            <td>
                                @if($page->status === 1)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </td>
                            <td>{{ $page->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('page.edit', $page) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-pen-fancy fa-fw"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No pages found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $pages->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.grid__id {
    width: 60px;
}
.action-column-1 {
    width: 100px;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-warning {
    background-color: #f6c23e;
}
</style>

<script>
// Date range picker functionality
document.addEventListener('DOMContentLoaded', function() {
    const dateRangePicker = document.getElementById('dateRangePicker');
    if (dateRangePicker) {
        // You can integrate a proper date range picker library here
        dateRangePicker.addEventListener('focus', function() {
            this.type = 'date';
        });
    }
});
</script>
@endsection
