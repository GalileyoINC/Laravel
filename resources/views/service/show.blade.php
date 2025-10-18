@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Service #{{ $service->id }}</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('service.edit', $service) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i> Edit
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $service->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $service->name }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $service->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>${{ number_format($service->price, 2) }}</td>
                </tr>
                <tr>
                    <th>Bonus Point</th>
                    <td>{{ $service->bonus_point ?? 0 }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $service->type_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Is Active</th>
                    <td>
                        @if($service->is_active)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $service->created_at->format('M d, Y h:i a') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $service->updated_at->format('M d, Y h:i a') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="box-footer">
        <a href="{{ route('service.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection
