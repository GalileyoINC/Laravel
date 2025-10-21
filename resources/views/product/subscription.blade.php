@extends('layouts.app')

@section('title', 'Subscriptions - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Subscriptions</h1>
        <div class="btn-group">
            <a href="{{ route('product.settings') }}" class="btn btn-success JS__load_in_modal">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Subscription Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('product.subscription') }}" method="GET" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <select name="is_active" class="form-select form-select-sm">
                            <option value="">Status</option>
                            <option value="1" {{ ($filters['is_active'] ?? '') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ ($filters['is_active'] ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="price_min" class="form-control form-control-sm" placeholder="Min Price" value="{{ $filters['price_min'] ?? '' }}" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Max Price" value="{{ $filters['price_max'] ?? '' }}" step="0.01">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm me-1">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('product.subscription') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Feeds</th>
                        <th>Satellite Communicators</th>
                        <th>Active</th>
                        <th class="action-column-1">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->id }}</td>
                            <td>{{ $subscription->name }}</td>
                            <td>{{ Str::limit($subscription->description, 50) }}</td>
                            <td>
                                @if($subscription->is_special_price)
                                    {{ number_format($subscription->special_price, 2) }}
                                    <s>{{ number_format($subscription->price, 2) }}</s>
                                @elseif($subscription->isCustom())
                                    {{ number_format($customParams['phone_price'], 2) }} / {{ number_format($customParams['feed_price'], 2) }}
                                @else
                                    {{ number_format($subscription->price, 2) }}
                                @endif
                            </td>
                            <td>
                                @if($subscription->isCustom())
                                    {{ $customParams['feed_min'] }} - {{ $customParams['feed_max'] }}
                                @else
                                    {{ $subscription->showFeedCnt() ?? '' }}
                                @endif
                            </td>
                            <td>
                                @if($subscription->id == \App\Models\Finance\Service::ID_CUSTOM_WITH_SATELLITE)
                                    Customizable (1-10)
                                @elseif($subscription->id == \App\Models\Finance\Service::ID_CUSTOM_WITHOUT_SATELLITE)
                                    None
                                @elseif($subscription->isCustom())
                                    {{ $customParams['phone_min'] }} - {{ $customParams['phone_max'] }}
                                @else
                                    {{ $subscription->settings['max_phone_cnt'] ?? '' }}
                                @endif
                            </td>
                            <td>
                                @if($subscription->is_active)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($subscription->isNewSubscription())
                                        <a href="{{ route('product.edit-subscription', $subscription) }}" 
                                           class="btn btn-sm btn-success JS__load_in_modal" 
                                           title="Update" 
                                           target="_blank">
                                            <i class="fas fa-pen-fancy fa-fw"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No subscriptions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $subscriptions->links() }}
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
.badge-danger {
    background-color: #e74a3b;
}
</style>
@endsection
