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
                            <td>{{ is_array($bundle) ? ($bundle['id'] ?? '') : ($bundle->id ?? '') }}</td>
                            <td>{{ is_array($bundle) ? ($bundle['title'] ?? '') : ($bundle->title ?? '') }}</td>
                            <td>{{ is_array($bundle) ? ($bundle['type_name'] ?? 'N/A') : ($bundle->type_name ?? 'N/A') }}</td>
                            <td>{{ is_array($bundle) ? ($bundle['pay_interval_name'] ?? 'N/A') : ($bundle->pay_interval_name ?? 'N/A') }}</td>
                            <td>
                                @php $total = is_array($bundle) ? ($bundle['total'] ?? 0) : ($bundle->total ?? 0); @endphp
                                ${{ number_format((float) $total, 2) }}
                            </td>
                            <td>
                                @php $active = is_array($bundle) ? ($bundle['is_active'] ?? false) : ($bundle->is_active ?? false); @endphp
                                @if($active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $createdAt = is_array($bundle) ? ($bundle['created_at'] ?? null) : ($bundle->created_at ?? null);
                                @endphp
                                @if($createdAt instanceof \Illuminate\Support\Carbon)
                                    {{ $createdAt->format('M d, Y') }}
                                @elseif(!empty($createdAt))
                                    {{ \Illuminate\Support\Carbon::parse($createdAt)->format('M d, Y') }}
                                @else
                                    
                                @endif
                            </td>
                            <td>
                                @php $bundleId = is_array($bundle) ? ($bundle['id'] ?? null) : ($bundle->id ?? null); @endphp
                                <div class="btn-group">
                                    @if($bundleId)
                                        <a href="{{ route('bundle.show', ['bundle' => $bundleId]) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('bundle.edit', ['bundle' => $bundleId]) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('bundle.destroy', ['bundle' => $bundleId]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
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
