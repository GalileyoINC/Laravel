@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Services</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('service.create', ['type' => 1]) }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create Service
            </a>
            <a href="{{ route('service.settings') }}" class="btn btn-info">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Bonus Point</th>
                        <th>Type</th>
                        <th>Active</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td>${{ number_format($service->price, 2) }}</td>
                            <td>{{ $service->bonus_point ?? 0 }}</td>
                            <td>{{ $service->type_name ?? 'N/A' }}</td>
                            <td>
                                @if($service->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $service->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('service.show', $service) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('service.edit', $service) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No services found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($services, 'links'))
            <div class="text-center">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
