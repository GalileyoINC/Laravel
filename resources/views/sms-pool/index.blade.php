@extends('layouts.app')

@section('title', 'Messages Pool - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Messages Pool</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Summary -->
            <div class="summary" style="margin-bottom:10px;">
                @if($smsPools->total() > 0)
                    Showing <b>{{ $smsPools->firstItem() }}-{{ $smsPools->lastItem() }}</b> of <b>{{ $smsPools->total() }}</b> items.
                @else
                    Showing <b>0-0</b> of <b>0</b> items.
                @endif
            </div>

            <div class="table-responsive">
                <form method="GET" id="filters-form"></form>
                <table class="table table-striped table-bordered" width="100%" cellspacing="0">
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
                        <tr class="filters">
                            <td>
                                <input type="text" class="form-control" name="search" form="filters-form" value="{{ $filters['search'] ?? '' }}" placeholder="Search...">
                            </td>
                            <td>
                                <select name="purpose" class="form-control" form="filters-form">
                                    <option value=""></option>
                                    @foreach(\App\Models\Communication\SmsPool::getPurposes() as $key => $purpose)
                                        <option value="{{ $key }}" {{ ($filters['purpose'] ?? '') == $key ? 'selected' : '' }}>
                                            {{ $purpose }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="id_subscription" class="form-control" form="filters-form">
                                    <option value=""></option>
                                    @foreach($subscriptions as $subscription)
                                        <option value="{{ $subscription['id'] }}" {{ ($filters['id_subscription'] ?? '') == $subscription['id'] ? 'selected' : '' }}>
                                            {{ $subscription['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <input type="date" name="created_at" class="form-control" form="filters-form" value="{{ $filters['created_at'] ?? '' }}">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                <a href="{{ route('sms-pool.index') }}" class="btn btn-default ml-2">Clear</a>
                            </td>
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
                            <td>{{ $smsPool->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('sms-pool.show', $smsPool) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('sms-pool.recipient', $smsPool) }}" class="btn btn-sm btn-info" title="Recipients">
                                        <i class="fas fa-mail-bulk"></i>
                                    </a>
                                    <form action="{{ route('sms-pool.destroy', $smsPool) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this SMS pool?');">
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
