@extends('layouts.app')

@section('title', 'Devices Plans Report - Admin')

@section('content')
@php
use App\Helpers\TableFilterHelper;
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Devices Plans Report - {{ $date->format('M d, Y') }}</h3>
                    <div>
                        <a href="{{ route('report.devices-plans', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}" class="btn btn-sm btn-default">
                            <i class="fas fa-arrow-left"></i> Previous Day
                        </a>
                        <a href="{{ route('report.devices-plans', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}" class="btn btn-sm btn-default">
                            Next Day <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <x-simple-table-filter :title="''">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>User First Name</th>
                                    <th>User Last Name</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Created At</th>
                                </tr>
                                <tr class="filters">
                                    <td><input type="text" class="form-control filter-input" data-column="0" placeholder="Invoice ID"></td>
                                    <td><input type="text" class="form-control filter-input" data-column="1" placeholder="First Name"></td>
                                    <td><input type="text" class="form-control filter-input" data-column="2" placeholder="Last Name"></td>
                                    <td><input type="text" class="form-control filter-input" data-column="3" placeholder="Product"></td>
                                    <td><input type="text" class="form-control filter-input" data-column="4" placeholder="Price"></td>
                                    <td><input type="text" class="form-control filter-input" data-column="5" placeholder="Created At"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                    <tr class="data-row">
                                        <td data-column="0">{{ $device->id_invoice ?? '-' }}</td>
                                        <td data-column="1">{{ $device->first_name ?? '-' }}</td>
                                        <td data-column="2">{{ $device->last_name ?? '-' }}</td>
                                        <td data-column="3">{{ $device->product_name ?? '-' }}</td>
                                        <td data-column="4">{{ isset($device->price) ? number_format($device->price, 2) : '-' }}</td>
                                        <td data-column="5">{{ isset($device->created_at) ? \Carbon\Carbon::parse($device->created_at)->format('M d, Y H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No devices found for this date.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </x-simple-table-filter>

                    @if(method_exists($devices, 'links'))
                        <div class="d-flex justify-content-center mt-3">
                            {{ $devices->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 15px;
    border-radius: 4px 4px 0 0;
}
.panel-body {
    padding: 15px;
}
.panel-title {
    font-size: 18px;
    font-weight: 600;
}
</style>
@endsection
