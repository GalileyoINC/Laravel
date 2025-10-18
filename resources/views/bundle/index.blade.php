@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Bundles</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('bundle.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create Bundle
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Pay Interval</th>
                        <th>Total</th>
                        <th>Active</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bundles as $bundle)
                        <tr>
                            <td>{{ $bundle->id }}</td>
                            <td>{{ $bundle->title }}</td>
                            <td>{{ $bundle->type_name ?? 'N/A' }}</td>
                            <td>{{ $bundle->pay_interval_name ?? 'N/A' }}</td>
                            <td>${{ number_format($bundle->total, 2) }}</td>
                            <td>
                                @if($bundle->is_active)
                                    <span class="label label-success">Active</span>
                                @else
                                    <span class="label label-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $bundle->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('bundle.show', $bundle) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bundle.edit', $bundle) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('bundle.destroy', $bundle) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No bundles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($bundles, 'links'))
            <div class="text-center">
                {{ $bundles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
