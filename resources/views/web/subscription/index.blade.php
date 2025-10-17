@extends('web.layouts.app')

@section('title', $title . ' - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>{{ $title }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-lg-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Categories
                </div>
                <div class="panel-body">
                    @include('web.subscription._categories', [
                        'categories' => $categories,
                        'idCategory' => null,
                        'idActive' => $selectedCategory ? $selectedCategory->id : null
                    ])
                </div>
            </div>
        </div>

        <div class="col-md-9 col-lg-10">
            @if($subscriptions->count() > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Alerts
                    </div>
                    <div class="panel-body">
                        <!-- Filters -->
                        <form method="GET" class="form-inline mb-3">
                            @if($selectedCategory)
                                <input type="hidden" name="idCategory" value="{{ $selectedCategory->id }}">
                            @endif
                            <div class="form-group mr-2">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                            </div>
                            <div class="form-group mr-2">
                                <select name="is_active" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <select name="is_custom" class="form-control">
                                    <option value="">All Custom</option>
                                    <option value="1" {{ request('is_custom') == '1' ? 'selected' : '' }}>Custom</option>
                                    <option value="0" {{ request('is_custom') == '0' ? 'selected' : '' }}>Not Custom</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <select name="show_reactions" class="form-control">
                                    <option value="">All Reactions</option>
                                    <option value="1" {{ request('show_reactions') == '1' ? 'selected' : '' }}>Show Reactions</option>
                                    <option value="0" {{ request('show_reactions') == '0' ? 'selected' : '' }}>Hide Reactions</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <select name="show_comments" class="form-control">
                                    <option value="">All Comments</option>
                                    <option value="1" {{ request('show_comments') == '1' ? 'selected' : '' }}>Show Comments</option>
                                    <option value="0" {{ request('show_comments') == '0' ? 'selected' : '' }}>Hide Comments</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <input type="date" name="sended_at_from" class="form-control" value="{{ request('sended_at_from') }}">
                            </div>
                            <div class="form-group mr-2">
                                <input type="date" name="sended_at_to" class="form-control" value="{{ request('sended_at_to') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('web.subscription.index') }}" class="btn btn-default ml-2">Clear</a>
                        </form>

                        <!-- Export Button -->
                        <div class="mb-3">
                            <a href="{{ route('web.subscription.export', request()->query()) }}" class="btn btn-success">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th class="grid__id">ID</th>
                                        @if(auth()->user()->isSuper() && !in_array($selectedCategory ? $selectedCategory->id : null, [1]))
                                            <th class="bg-admin">Name</th>
                                        @endif
                                        @if($selectedCategory && in_array($selectedCategory->id, [1]))
                                            <th>User</th>
                                        @endif
                                        <th>Title</th>
                                        @if($selectedCategory && in_array($selectedCategory->id, [2, 3, 4]))
                                            <th>Index</th>
                                        @endif
                                        @if($selectedCategory && in_array($selectedCategory->id, [5]))
                                            <th>Ticker</th>
                                        @endif
                                        <th>Description</th>
                                        @if($selectedCategory && in_array($selectedCategory->id, [2, 3, 4, 5]))
                                            <th>Percent</th>
                                        @endif
                                        <th>Is Active</th>
                                        @if($selectedCategory && in_array($selectedCategory->id, [2, 5]))
                                            <th>Is Custom</th>
                                        @endif
                                        <th>Show Reactions</th>
                                        <th>Show Comments</th>
                                        @if($selectedCategory && in_array($selectedCategory->id, [2, 3, 4, 5]))
                                            <th>Sended At</th>
                                        @endif
                                        <th>Followers*</th>
                                        <th class="action-column-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscriptions as $subscription)
                                        <tr>
                                            <td>
                                                @if($subscription->influencerPage && $subscription->influencerPage->image)
                                                    <img src="{{ Storage::url($subscription->influencerPage->image) }}" alt="{{ $subscription->title }}" style="width: 48px; border-radius: 50%;">
                                                @else
                                                    <div class="no-image-placeholder" style="width: 48px; height: 48px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 12px;">
                                                        No Image
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $subscription->id }}</td>
                                            @if(auth()->user()->isSuper() && !in_array($selectedCategory ? $selectedCategory->id : null, [1]))
                                                <td class="bg-admin">{{ $subscription->name ?? '-' }}</td>
                                            @endif
                                            @if($selectedCategory && in_array($selectedCategory->id, [1]))
                                                <td>
                                                    @if($subscription->influencer)
                                                        <a href="{{ route('web.user.show', $subscription->influencer) }}">{{ $subscription->influencer->getFullName() }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $subscription->title }}</td>
                                            @if($selectedCategory && in_array($selectedCategory->id, [2, 3, 4]))
                                                <td>{{ $subscription->params['index'] ?? '-' }}</td>
                                            @endif
                                            @if($selectedCategory && in_array($selectedCategory->id, [5]))
                                                <td>{{ $subscription->params['ticker'] ?? '-' }}</td>
                                            @endif
                                            <td>{{ Str::limit($subscription->description, 50) }}</td>
                                            @if($selectedCategory && in_array($selectedCategory->id, [2, 3, 4, 5]))
                                                <td>{{ $subscription->percent ?? '-' }}</td>
                                            @endif
                                            <td>
                                                @if($subscription->is_active)
                                                    <i class="fas fa-check text-success"></i> Yes
                                                @else
                                                    <i class="fas fa-times text-danger"></i> No
                                                @endif
                                            </td>
                                            @if($selectedCategory && in_array($selectedCategory->id, [2, 5]))
                                                <td>
                                                    @if($subscription->is_custom)
                                                        <i class="fas fa-check text-success"></i> Yes
                                                    @else
                                                        <i class="fas fa-times text-danger"></i> No
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if($subscription->show_reactions)
                                                    <i class="fas fa-check text-success"></i> Yes
                                                @else
                                                    <i class="fas fa-times text-danger"></i> No
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->show_comments)
                                                    <i class="fas fa-check text-success"></i> Yes
                                                @else
                                                    <i class="fas fa-times text-danger"></i> No
                                                @endif
                                            </td>
                                            @if($selectedCategory && in_array($selectedCategory->id, [2, 3, 4, 5]))
                                                <td>{{ $subscription->sended_at ? $subscription->sended_at->format('Y-m-d') : '-' }}</td>
                                            @endif
                                            <td>{{ $userStatistics[$subscription->id] ?? 0 }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('web.subscription.edit', $subscription) }}" class="btn btn-xs btn-success" title="Update" target="_blank">
                                                        <i class="fas fa-pen-fancy fa-fw"></i>
                                                    </a>
                                                    @if(auth()->user()->isSuper())
                                                        <a href="{{ route('web.subscription.super-update', $subscription) }}" class="btn btn-xs btn-admin JS__load_in_modal">
                                                            <i class="fas fa-pen-fancy fa-fw"></i>
                                                        </a>
                                                    @endif
                                                    @if($subscription->is_active)
                                                        <form method="POST" action="{{ route('web.subscription.deactivate', $subscription) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to deactivate this news?')">
                                                                Deactivate
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('web.subscription.activate', $subscription) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-xs btn-warning" onclick="return confirm('Are you sure you want to activate this news?')">
                                                                Activate
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center">No subscriptions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $subscriptions->appends(request()->query())->links() }}
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="">Followers* are the number of users with an active plan (test users are not counted)</div>
                    </div>
                </div>
            @endif
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
.panel-footer {
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    padding: 10px 15px;
    font-size: 0.875rem;
    color: #6c757d;
}
.grid__id {
    width: 60px;
}
.action-column-4 {
    width: 200px;
}
.bg-admin {
    background-color: #f8f9fa;
}
.list-group {
    padding-left: 0;
    margin-bottom: 20px;
}
.list-group-item {
    position: relative;
    display: block;
    padding: 10px 15px;
    margin-bottom: -1px;
    background-color: #fff;
    border: 1px solid #ddd;
}
.list-group-item-success {
    color: #3c763d;
    background-color: #dff0d8;
}
.text-success {
    color: #28a745;
}
.text-danger {
    color: #dc3545;
}
.table-responsive {
    overflow-x: auto;
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
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.btn-group {
    display: inline-flex;
    vertical-align: middle;
}
.btn-group .btn {
    position: relative;
    flex: 1 1 auto;
}
.btn-group .btn + .btn {
    margin-left: -1px;
}
.btn-group .btn:first-child {
    margin-left: 0;
}
.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-admin {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-warning {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-inline .form-group {
    display: flex;
    flex: 0 0 auto;
    flex-flow: row wrap;
    align-items: center;
    margin-bottom: 0;
}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
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
