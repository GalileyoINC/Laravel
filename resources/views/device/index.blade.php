@extends('layouts.app')

@section('title', 'Devices - Admin')

@section('content')
@php
use App\Helpers\TableFilterHelper;
@endphp

<x-table-filter 
    :title="'Devices'" 
    :data="$devices"
    :export-route="route('device.export', request()->query())"
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
                    <a href="{{ route('device.show', ['device' => $deviceId]) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye fa-fw"></i>
                    </a>
                    <form method="POST" action="{{ route('device.destroy', ['device' => $deviceId]) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this device?')">
                            <i class="fas fa-trash fa-fw"></i>
                        </button>
                    </form>
                    @if($pushToken)
                        <a href="{{ route('device.push', ['device' => $deviceId]) }}" class="btn btn-sm btn-admin">
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

<!-- Pagination -->
@if(method_exists($devices, 'links'))
    <div class="d-flex justify-content-center">
        {{ $devices->appends(request()->query())->links() }}
    </div>
@endif

<style>
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
