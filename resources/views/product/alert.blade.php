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
            <form action="{{ route('product.alert') }}" method="GET" class="mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <select name="is_active" class="form-select form-select-sm">
                            <option value="">Status</option>
                            <option value="1" {{ ($filters['is_active'] ?? '') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ ($filters['is_active'] ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary btn-sm me-1">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('product.alert') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- Summary -->
            <div class="mb-2 text-muted small">
                @if(method_exists($alerts, 'total') && $alerts->total() > 0)
                    Showing <strong>{{ $alerts->firstItem() }}-{{ $alerts->lastItem() }}</strong> of <strong>{{ $alerts->total() }}</strong> items.
                @else
                    Showing <strong>0-0</strong> of <strong>0</strong> items.
                @endif
            </div>

            <div class="row">
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
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
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
