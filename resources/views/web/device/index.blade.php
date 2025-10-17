@extends('web.layouts.app')

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
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" name="push_token" class="form-control" placeholder="Push Token" value="{{ request('push_token') }}" style="width: 200px;">
                        </div>
                        <div class="form-group mr-2">
                            <select name="push_token_fill" class="form-control">
                                <option value="">All Tokens</option>
                                <option value="0" {{ request('push_token_fill') == '0' ? 'selected' : '' }}>Empty</option>
                                <option value="1" {{ request('push_token_fill') == '1' ? 'selected' : '' }}>Fill</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="push_turn_on" class="form-control">
                                <option value="">All Push Status</option>
                                <option value="1" {{ request('push_turn_on') == '1' ? 'selected' : '' }}>Turn On</option>
                                <option value="0" {{ request('push_turn_on') == '0' ? 'selected' : '' }}>Turn Off</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="os" class="form-control">
                                <option value="">All OS</option>
                                <option value="ios" {{ request('os') == 'ios' ? 'selected' : '' }}>iOS</option>
                                <option value="android" {{ request('os') == 'android' ? 'selected' : '' }}>Android</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="updated_at_from" class="form-control" value="{{ request('updated_at_from') }}">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="updated_at_to" class="form-control" value="{{ request('updated_at_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('web.device.index') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('web.device.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                    <tr>
                                        <td>{{ $device->id }}</td>
                                        <td>
                                            @if($device->user)
                                                <a href="{{ route('web.user.show', $device->user) }}" class="JS__load_in_modal">
                                                    {{ $device->user->email }}
                                                </a>
                                                @if(auth()->user()->isSuper())
                                                    <span class="text-admin"> (ID {{ $device->user->id }})</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($device->push_turn_on)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td>{{ $device->uuid }}</td>
                                        <td>{{ ucfirst($device->os) }}</td>
                                        <td>
                                            @if($device->push_token)
                                                <span>{{ strlen($device->push_token) > 28 ? substr($device->push_token, 0, 12) . '...' . substr($device->push_token, -10) : $device->push_token }}</span>
                                                <button class="btn btn-success btn-sm align-baseline JS__copy_title_to_clipboard" title="{{ $device->push_token }}">
                                                    <i class="fas fa-paste"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($device->access_token)
                                                <span>{{ strlen($device->access_token) > 28 ? substr($device->access_token, 0, 12) . '...' . substr($device->access_token, -10) : $device->access_token }}</span>
                                                <button class="btn btn-success btn-sm align-baseline JS__copy_title_to_clipboard" title="{{ $device->access_token }}">
                                                    <i class="fas fa-paste"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $device->updated_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('web.device.show', $device) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye fa-fw"></i>
                                                </a>
                                                <form method="POST" action="{{ route('web.device.destroy', $device) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this device?')">
                                                        <i class="fas fa-trash fa-fw"></i>
                                                    </button>
                                                </form>
                                                @if($device->push_token)
                                                    <a href="{{ route('web.device.push', $device) }}" class="btn btn-xs btn-admin">
                                                        <i class="far fa-paper-plane fa-fw"></i>
                                                    </a>
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
                    <div class="d-flex justify-content-center">
                        {{ $devices->appends(request()->query())->links() }}
                    </div>
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
