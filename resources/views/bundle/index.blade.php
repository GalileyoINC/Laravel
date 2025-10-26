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
        <!-- Summary -->
        <div class="summary" style="margin-bottom:10px;">
            @if($bundles instanceof \Illuminate\Contracts\Pagination\Paginator && $bundles->total() > 0)
                Showing <b>{{ $bundles->firstItem() }}-{{ $bundles->lastItem() }}</b> of <b>{{ $bundles->total() }}</b> items.
            @elseif(is_array($bundles) && count($bundles) > 0)
                Showing <b>1-{{ count($bundles) }}</b> of <b>{{ count($bundles) }}</b> items.
            @else
                Showing <b>0-0</b> of <b>0</b> items.
            @endif
        </div>

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
                            <td>{{ $bundle->type ?? 'N/A' }}</td>
                            <td>{{ $bundle->pay_interval ?? 'N/A' }}</td>
                            <td>${{ number_format($bundle->total, 2) }}</td>
                            <td>
                                @if($bundle->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $bundle->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('bundle.show', ['bundle' => $bundle->id]) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bundle.edit', ['bundle' => $bundle->id]) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('bundle.destroy', ['bundle' => $bundle->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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

        @if($bundles instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="text-center">
                {{ $bundles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
