@extends('layouts.app')

@section('title', 'Alerts - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Alerts</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Alert Filters</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('product.alert') }}" method="GET" class="mb-4 alerts-filters">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <input type="text" name="search" class="form-control" placeholder="Search by name or description" value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <select name="is_active" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="1" {{ ($filters['is_active'] ?? '') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ ($filters['is_active'] ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                <a href="{{ route('product.alert') }}" class="btn btn-secondary">Reset Filters</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div style="clear: both;"></div>

            <div class="row">
                <div class="col-12">
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if(method_exists($alerts, 'total') && $alerts->total() > 0)
                            Showing <b>{{ $alerts->firstItem() }}-{{ $alerts->lastItem() }}</b> of <b>{{ $alerts->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Active</th>
                                    <th class="action-column-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts as $alert)
                                    <tr>
                                        <td>{{ $alert->id }}</td>
                                        <td>{{ $alert->name }}</td>
                                        <td>{{ Str::limit($alert->description, 50) }}</td>
                                        <td>{{ number_format($alert->price, 2) }}</td>
                                        <td>
                                            @if($alert->is_active)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('product.edit-alert', $alert) }}" class="btn btn-sm btn-success JS__load_in_modal" title="Update" target="_blank">
                                                    <i class="fas fa-pen-fancy fa-fw"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No alerts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $alerts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table-responsive { float: none; width: 100%; }
table.table { width: 100%; }
.alerts-filters { display: block; width: 100%; }
</style>
@endpush
