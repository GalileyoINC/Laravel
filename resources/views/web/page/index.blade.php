@extends('web.layouts.app')

@section('title', 'Pages - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pages</h1>
        <a href="{{ route('web.page.create') }}" class="btn btn-success">
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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Page Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('web.page.index') }}" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, slug, or title" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="text" name="createTimeRange" class="form-control" placeholder="Date Range" value="{{ $filters['createTimeRange'] ?? '' }}" id="dateRangePicker">
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('web.page.index') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="action-column-1">Actions</th>
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
                            <td>{{ $page->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('web.page.edit', $page) }}" class="btn btn-sm btn-success">
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
