@extends('layouts.app')

@section('title', 'Devices - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Devices</h3>
                </div>
                <div class="panel-body">
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($devices instanceof \Illuminate\Contracts\Pagination\Paginator && $devices->total() > 0)
                            Showing <b>{{ $devices->firstItem() }}-{{ $devices->lastItem() }}</b> of <b>{{ $devices->total() }}</b> items.
                        @elseif(is_array($devices) && count($devices) > 0)
                            Showing <b>1-{{ count($devices) }}</b> of <b>{{ count($devices) }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('device.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <form method="GET" id="filters-form"></form>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>User Email</th>
                                    <th>Push Turn On</th>
                                    <th>UUID</th>
                                    <th>OS</th>
                                    <th>Push Token</th>
                                    <th>Access Token</th>
                                    <th>Updated At</th>
                                    <th class="action-column-3">Actions</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ request('search') }}">
                                    </td>
                                    <td></td>
                                    <td>
                                        <select name="push_turn_on" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            <option value="1" {{ request('push_turn_on') == '1' ? 'selected' : '' }}>Turn On</option>
                                            <option value="0" {{ request('push_turn_on') == '0' ? 'selected' : '' }}>Turn Off</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td>
                                        <select name="os" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            <option value="ios" {{ request('os') == 'ios' ? 'selected' : '' }}>iOS</option>
                                            <option value="android" {{ request('os') == 'android' ? 'selected' : '' }}>Android</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex" style="gap:6px;">
                                            <input type="text" name="push_token" class="form-control" form="filters-form" placeholder="Push Token" value="{{ request('push_token') }}" style="max-width:200px;">
                                            <select name="push_token_fill" class="form-control" form="filters-form" style="max-width:140px;">
                                                <option value=""></option>
                                                <option value="0" {{ request('push_token_fill') == '0' ? 'selected' : '' }}>Empty</option>
                                                <option value="1" {{ request('push_token_fill') == '1' ? 'selected' : '' }}>Fill</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <input type="date" name="updated_at_from" class="form-control" form="filters-form" value="{{ request('updated_at_from') }}">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                        <a href="{{ route('device.index') }}" class="btn btn-default ml-2">Clear</a>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                    <tr>
                                        <td>{{ is_array($device) ? ($device['id'] ?? '') : ($device->id ?? '') }}</td>
                                        <td>
                                            @php $user = is_array($device) ? ($device['user'] ?? null) : ($device->user ?? null); @endphp
                                            @if(is_object($user))
                                                <a href="{{ route('user.show', $user) }}" class="JS__load_in_modal">
                                                    {{ $user->email }}
                                                </a>
                                                @if(auth()->user()->isSuper())
                                                    <span class="text-admin"> (ID {{ $user->id }})</span>
                                                @endif
                                            @elseif(is_array($user))
                                                {{ $user['email'] ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php $pushOn = is_array($device) ? ($device['push_turn_on'] ?? false) : ($device->push_turn_on ?? false); @endphp
                                            @if($pushOn)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td>{{ is_array($device) ? ($device['uuid'] ?? '') : ($device->uuid ?? '') }}</td>
                                        <td>
                                            @php $os = is_array($device) ? ($device['os'] ?? '') : ($device->os ?? ''); @endphp
                                            {{ ucfirst($os) }}
                                        </td>
                                        <td>
                                            @php $pushToken = is_array($device) ? ($device['push_token'] ?? '') : ($device->push_token ?? ''); @endphp
                                            @if($pushToken)
                                                <span>{{ strlen($pushToken) > 28 ? substr($pushToken, 0, 12) . '...' . substr($pushToken, -10) : $pushToken }}</span>
                                                <button class="btn btn-success btn-sm align-baseline JS__copy_title_to_clipboard" title="{{ $device->push_token }}">
                                                    <i class="fas fa-paste"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php $accessToken = is_array($device) ? ($device['access_token'] ?? '') : ($device->access_token ?? ''); @endphp
                                            @if($accessToken)
                                                <span>{{ strlen($accessToken) > 28 ? substr($accessToken, 0, 12) . '...' . substr($accessToken, -10) : $accessToken }}</span>
                                                <button class="btn btn-success btn-sm align-baseline JS__copy_title_to_clipboard" title="{{ $device->access_token }}">
                                                    <i class="fas fa-paste"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php $uAt = is_array($device) ? ($device['updated_at'] ?? null) : ($device->updated_at ?? null); @endphp
                                            @if($uAt instanceof \Illuminate\Support\Carbon)
                                                {{ $uAt->format('M d, Y H:i') }}
                                            @elseif(!empty($uAt))
                                                {{ \Illuminate\Support\Carbon::parse($uAt)->format('M d, Y H:i') }}
                                            @else
                                                
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @php $deviceId = is_array($device) ? ($device['id'] ?? null) : ($device->id ?? null); @endphp
                                                @if($deviceId)
                                                <a href="{{ route('device.show', ['device' => $deviceId]) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye fa-fw"></i>
                                                </a>
                                                <form method="POST" action="{{ route('device.destroy', ['device' => $deviceId]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this device?')">
                                                        <i class="fas fa-trash fa-fw"></i>
                                                    </button>
                                                </form>
                                                @if($pushToken)
                                                    <a href="{{ route('device.push', ['device' => $deviceId]) }}" class="btn btn-xs btn-admin">
                                                        <i class="far fa-paper-plane fa-fw"></i>
                                                    </a>
                                                @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No devices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($devices instanceof \Illuminate\Contracts\Pagination\Paginator)
                        <div class="d-flex justify-content-center">
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
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
.panel-body {
    padding: 15px;
}
.grid__id {
    width: 60px;
}
.action-column-3 {
    width: 200px;
}
.text-admin {
    color: #6c757d;
    font-size: 0.875rem;
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #f8f9fa;
    font-weight: 600;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.btn-group {
    display: inline-flex;
    vertical-align: middle;
}
.btn-group .btn {
    position: relative;
    flex: 1 1 auto;
}
.btn-group .btn + .btn {
    margin-left: -1px;
}
.btn-group .btn:first-child {
    margin-left: 0;
}
.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-admin {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-inline .form-group {
    display: flex;
    flex: 0 0 auto;
    flex-flow: row wrap;
    align-items: center;
    margin-bottom: 0;
}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
.text-center {
    text-align: center;
}
.text-success {
    color: #28a745;
}
.text-danger {
    color: #dc3545;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard functionality
    document.querySelectorAll('.JS__copy_title_to_clipboard').forEach(function(button) {
        button.addEventListener('click', function() {
            const text = this.getAttribute('title');
            navigator.clipboard.writeText(text).then(function() {
                // Show success feedback
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(function() {
                    button.innerHTML = originalText;
                }, 1000);
            });
        });
    });
});
</script>
@endsection
