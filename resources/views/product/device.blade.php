@extends('layouts.app')

@section('title', 'Devices - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Devices</h1>
        <a href="{{ route('product.create-device') }}" class="btn btn-success JS__load_in_modal">
            <i class="fas fa-plus"></i> Create
        </a>
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
            <h6 class="m-0 font-weight-bold text-primary">Device Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('product.device') }}" method="GET" class="mb-4">
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
                        <a href="{{ route('product.device') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="action-column-1">Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Active</th>
                        <th class="action-column-1">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($devices as $device)
                        <tr>
                            <td>
                                @if($device->mainPhoto)
                                    <img src="{{ $device->mainPhoto->getLesserWeb() }}" style="width: 128px;" alt="{{ $device->name }}">
                                @endif
                            </td>
                            <td>{{ $device->name }}</td>
                            <td>{{ Str::limit($device->description, 50) }}</td>
                            <td>
                                @if($device->is_special_price)
                                    {{ number_format($device->special_price, 2) }}
                                    <s>{{ number_format($device->price, 2) }}</s>
                                @else
                                    {{ number_format($device->price, 2) }}
                                @endif
                            </td>
                            <td>
                                @if($device->is_active)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('product.edit-device', $device) }}" 
                                       class="btn btn-sm btn-success" 
                                       title="Update" 
                                       target="_blank">
                                        <i class="fas fa-pen-fancy fa-fw"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No devices found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $devices->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.action-column-1 {
    width: 100px;
}
</style>
@endsection
