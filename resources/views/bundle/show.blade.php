@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Bundle #{{ $bundle->id }}</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('bundle.edit', $bundle) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i> Edit
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $bundle->id }}</td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td>{{ $bundle->title }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $bundle->type_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Pay Interval</th>
                    <td>{{ $bundle->pay_interval_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Total Price</th>
                    <td>${{ number_format($bundle->total, 2) }}</td>
                </tr>
                <tr>
                    <th>Is Active</th>
                    <td>
                        @if($bundle->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $bundle->created_at->format('M d, Y h:i a') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $bundle->updated_at->format('M d, Y h:i a') }}</td>
                </tr>
            </tbody>
        </table>

        @if($bundle->bundle_items && $bundle->bundle_items->count() > 0)
            <h4 class="mt-4">Bundle Items</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bundle->bundle_items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity ?? 1 }}</td>
                            <td>${{ number_format($item->price ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <div class="box-footer">
        <a href="{{ route('bundle.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection
