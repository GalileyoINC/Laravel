@extends('layouts.app')

@section('title', 'Messages Pool - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Messages Pool</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @php
            use App\Helpers\TableFilterHelper;
            $purposeOptions = \App\Models\Communication\SmsPool::getPurposes();
            @endphp

            <x-table-filter 
                :title="'Messages Pool'" 
                :data="$smsPools"
                :columns="[
                    TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                    TableFilterHelper::selectColumn('Purpose', $purposeOptions),
                    TableFilterHelper::textColumn('Subscription'),
                    TableFilterHelper::textColumn('Sender'),
                    TableFilterHelper::textColumn('Body'),
                    TableFilterHelper::textColumn('Created At'),
                    TableFilterHelper::clearButtonColumn('Actions', 'action-column-3'),
                ]"
            >
                @forelse($smsPools as $smsPool)
                    <tr class="data-row">
                        <td @dataColumn(0)>{{ $smsPool->id }}</td>
                        <td @dataColumn(1) @dataValue((string) $smsPool->purpose)>
                            {{ \App\Models\Communication\SmsPool::getPurposes()[$smsPool->purpose] ?? 'Unknown' }}
                        </td>
                        <td @dataColumn(2)>{{ $smsPool->subscription ? $smsPool->subscription->name : '-' }}</td>
                        <td @dataColumn(3)>
                            @if($smsPool->id_user)
                                User: {{ $smsPool->user->first_name }} {{ $smsPool->user->last_name }}
                            @elseif($smsPool->id_staff)
                                Staff: {{ $smsPool->staff->username }}
                            @else
                                -
                            @endif
                        </td>
                        <td @dataColumn(4)>{{ Str::limit($smsPool->body, 50) }}</td>
                        <td @dataColumn(5)>{{ $smsPool->created_at->format('M d, Y') }}</td>
                        <td @dataColumn(6)>
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
            </x-table-filter>
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
