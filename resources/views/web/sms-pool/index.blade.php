@extends('web.layouts.app')

@section('title', 'Messages Pool - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Messages Pool</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">SMS Pool Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('web.sms-pool.index') }}" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by ID or body" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="purpose" class="form-control">
                            <option value="">Select Purpose</option>
                            @foreach(\App\Models\Communication\SmsPool::getPurposes() as $key => $purpose)
                                <option value="{{ $key }}" {{ ($filters['purpose'] ?? '') == $key ? 'selected' : '' }}>
                                    {{ $purpose }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="id_subscription" class="form-control">
                            <option value="">Select Subscription</option>
                            @foreach($subscriptions as $subscription)
                                <option value="{{ $subscription['id'] }}" {{ ($filters['id_subscription'] ?? '') == $subscription['id'] ? 'selected' : '' }}>
                                    {{ $subscription['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="date" name="created_at" class="form-control" value="{{ $filters['created_at'] ?? '' }}">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('web.sms-pool.index') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>Purpose</th>
                        <th>Subscription</th>
                        <th>Sender</th>
                        <th>Body</th>
                        <th>Created At</th>
                        <th class="action-column-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($smsPools as $smsPool)
                        <tr>
                            <td>{{ $smsPool->id }}</td>
                            <td>
                                {{ \App\Models\Communication\SmsPool::getPurposes()[$smsPool->purpose] ?? 'Unknown' }}
                            </td>
                            <td>
                                {{ $smsPool->subscription ? $smsPool->subscription->name : '-' }}
                            </td>
                            <td>
                                @if($smsPool->id_user)
                                    User: {{ $smsPool->user->first_name }} {{ $smsPool->user->last_name }}
                                @elseif($smsPool->id_staff)
                                    Staff: {{ $smsPool->staff->username }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ Str::limit($smsPool->body, 50) }}</td>
                            <td>{{ $smsPool->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('web.sms-pool.show', $smsPool) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('web.sms-pool.recipient', $smsPool) }}" class="btn btn-sm btn-info" title="Recipients">
                                        <i class="fas fa-mail-bulk"></i>
                                    </a>
                                    <form action="{{ route('web.sms-pool.destroy', $smsPool) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this SMS pool?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash fa-fw"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No SMS pools found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $smsPools->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.grid__id {
    width: 60px;
}
.action-column-3 {
    width: 150px;
}
</style>
@endsection
