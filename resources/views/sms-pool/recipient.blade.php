@extends('layouts.app')

@section('title', 'SMS Pool Recipients #{{ $smsPool->id }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">SMS Pool Recipients #{{ $smsPool->id }}</h1>
        <a href="{{ route('sms-pool.show', $smsPool) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to SMS Pool
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recipient Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('sms-pool.recipient', $smsPool) }}" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by phone number" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('sms-pool.recipient', $smsPool) }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>User</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recipients as $recipient)
                        <tr>
                            <td>{{ $recipient->id }}</td>
                            <td>
                                @if($recipient->user)
                                    <a href="{{ route('user.show', $recipient->user->id) }}">
                                        {{ $recipient->user->first_name }} {{ $recipient->user->last_name }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $recipient->phoneNumber->number ?? '-' }}</td>
                            <td>
                                @if($recipient->status === 1)
                                    <span class="badge badge-success">Sent</span>
                                @elseif($recipient->status === 0)
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Failed</span>
                                @endif
                            </td>
                            <td>{{ $recipient->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No recipients found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $recipients->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.grid__id {
    width: 60px;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-warning {
    background-color: #f6c23e;
}
.badge-danger {
    background-color: #e74a3b;
}
</style>
@endsection
