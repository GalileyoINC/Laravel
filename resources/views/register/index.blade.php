@extends('layouts.app')

@section('title', '{{ $isUnfinishedSignup == 1 ? "Unfinished Signups" : "Subscribed for newsletters" }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $isUnfinishedSignup == 1 ? 'Unfinished Signups' : 'Subscribed for newsletters' }}</h1>
        <div>
            <a href="{{ route('register.index-unique', ['signup' => $isUnfinishedSignup]) }}" class="btn btn-default">
                Show Unique
            </a>
            <a href="{{ route('register.to-csv', array_merge(['signup' => $isUnfinishedSignup], request()->query())) }}" class="btn btn-default">
                to .CSV
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Summary -->
            <div class="summary" style="margin-bottom:10px;">
                @if($registers->total() > 0)
                    Showing <b>{{ $registers->firstItem() }}-{{ $registers->lastItem() }}</b> of <b>{{ $registers->total() }}</b> items.
                @else
                    Showing <b>0-0</b> of <b>0</b> items.
                @endif
            </div>

            <div class="table-responsive">
                <form action="{{ route('register.index') }}" method="GET" id="filters-form">
                    <input type="hidden" name="is_unfinished_signup" value="{{ $isUnfinishedSignup }}">
                </form>
                <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Created At</th>
                        <th class="action-column-1">Actions</th>
                    </tr>
                    <tr class="filters">
                        <td>
                            <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="date" name="created_at" class="form-control" form="filters-form" value="{{ $filters['created_at'] ?? '' }}">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                            <a href="{{ route('register.index', ['is_unfinished_signup' => $isUnfinishedSignup]) }}" class="btn btn-default ml-2">Clear</a>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($registers as $register)
                        <tr>
                            <td>{{ $register->id }}</td>
                            <td>{{ $register->email }}</td>
                            <td>{{ $register->first_name }}</td>
                            <td>{{ $register->last_name }}</td>
                            <td>{{ $register->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <!-- No actions for register records -->
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No registers found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $registers->links() }}
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
</style>
@endsection
