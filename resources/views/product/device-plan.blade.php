@extends('layouts.app')

@section('title', 'Device Plans - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Device Plans</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Plan Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('product.plan') }}" method="GET" class="mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <select name="is_active" class="form-select form-select-sm">
                            <option value="">Status</option>
                            <option value="1" {{ ($filters['is_active'] ?? '') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ ($filters['is_active'] ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-sm me-1">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('product.plan') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- Summary -->
            <div class="mb-2 text-muted small">
                @if(method_exists($plans, 'total') && $plans->total() > 0)
                    Showing <strong>{{ $plans->firstItem() }}-{{ $plans->lastItem() }}</strong> of <strong>{{ $plans->total() }}</strong> items.
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
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>{{ $plan->id }}</td>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ Str::limit($plan->description ?? '', 50) }}</td>
                                        <td>{{ number_format($plan->price, 2) }}</td>
                                        <td>
                                            @if($plan->is_active)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('product.edit-plan', $plan) }}" class="btn btn-sm btn-success" title="Update">
                                                    <i class="fas fa-pen-fancy fa-fw"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No device plans found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $plans->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

