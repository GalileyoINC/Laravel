@extends('layouts.app')

@section('title', 'Sold Devices - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Sold Devices</h3>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search by user name..." value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('report.sold-devices') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if(method_exists($devices, 'total') && $devices->total() > 0)
                            Showing <b>{{ $devices->firstItem() }}-{{ $devices->lastItem() }}</b> of <b>{{ $devices->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>User First Name</th>
                                    <th>User Last Name</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                    <tr>
                                        <td>{{ $device->id_invoice ?? '-' }}</td>
                                        <td>{{ $device->first_name ?? '-' }}</td>
                                        <td>{{ $device->last_name ?? '-' }}</td>
                                        <td>{{ isset($device->created_at) ? \Carbon\Carbon::parse($device->created_at)->format('M d, Y H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No devices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $devices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
