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
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('device.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Devices'" 
                        :data="$devices"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::textColumn('User Email'),
                            TableFilterHelper::selectColumn('Push Turn On', ['1' => 'Turn On', '0' => 'Turn Off']),
                            TableFilterHelper::textColumn('UUID'),
                            TableFilterHelper::selectColumn('OS', ['ios' => 'iOS', 'android' => 'Android']),
                            TableFilterHelper::selectColumn('Push Token Fill', ['fill' => 'Fill', 'empty' => 'Empty']),
                            TableFilterHelper::textColumn('Access Token'),
                            TableFilterHelper::textColumn('Updated At'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-3'),
                        ]"
                    >
                        @forelse($devices as $device)
                            @php
                                $user = is_array($device) ? ($device['user'] ?? null) : ($device->user ?? null);
                                $pushOn = is_array($device) ? ($device['push_turn_on'] ?? false) : ($device->push_turn_on ?? false);
                                $os = is_array($device) ? ($device['os'] ?? '') : ($device->os ?? '');
                                $pushToken = is_array($device) ? ($device['push_token'] ?? '') : ($device->push_token ?? '');
                                $accessToken = is_array($device) ? ($device['access_token'] ?? '') : ($device->access_token ?? '');
                                $deviceId = is_array($device) ? ($device['id'] ?? null) : ($device->id ?? null);
                            @endphp
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ is_array($device) ? ($device['id'] ?? '') : ($device->id ?? '') }}</td>
                                <td @dataColumn(1)>
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
                                <td @dataColumn(2) @dataValue($pushOn ? '1' : '0')>
                                    @if($pushOn)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td @dataColumn(3)>{{ is_array($device) ? ($device['uuid'] ?? '') : ($device->uuid ?? '') }}</td>
                                <td @dataColumn(4) @dataValue(strtolower($os))>{{ ucfirst($os) }}</td>
                                <td @dataColumn(5) @dataValue($pushToken ? 'fill' : 'empty')>
                                    @if($pushToken)
                                        <span>{{ strlen($pushToken) > 28 ? substr($pushToken, 0, 12) . '...' . substr($pushToken, -10) : $pushToken }}</span>
                                        <button class="btn btn-success btn-sm align-baseline JS__copy_title_to_clipboard" title="{{ $device->push_token }}">
                                            <i class="fas fa-paste"></i>
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td @dataColumn(6)>
                                    @if($accessToken)
                                        <span>{{ strlen($accessToken) > 28 ? substr($accessToken, 0, 12) . '...' . substr($accessToken, -10) : $accessToken }}</span>
                                        <button class="btn btn-success btn-sm align-baseline JS__copy_title_to_clipboard" title="{{ $device->access_token }}">
                                            <i class="fas fa-paste"></i>
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td @dataColumn(7)>
                                    @php $uAt = is_array($device) ? ($device['updated_at'] ?? null) : ($device->updated_at ?? null); @endphp
                                    @if($uAt instanceof \Illuminate\Support\Carbon)
                                        {{ $uAt->format('M d, Y H:i') }}
                                    @elseif(!empty($uAt))
                                        {{ \Illuminate\Support\Carbon::parse($uAt)->format('M d, Y H:i') }}
                                    @else
                                        
                                    @endif
                                </td>
                                <td @dataColumn(8)>
                                    <div class="btn-group">
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
                    </x-table-filter>
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
