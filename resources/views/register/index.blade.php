@extends('layouts.app')

@section('title', '{{ $isUnfinishedSignup == 1 ? "Unfinished Signups" : "Subscribed for newsletters" }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $isUnfinishedSignup == 1 ? 'Unfinished Signups' : 'Subscribed for newsletters' }}</h1>
        <div>
            <a href="{{ route('register.index-unique', ['signup' => $isUnfinishedSignup]) }}" class="btn btn-default">
                Show Unique
            </a>
            <a href="{{ route('register.to-csv', array_merge(['signup' => $isUnfinishedSignup], request()->query())) }}" class="btn btn-default">
                to .CSV
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @php
            use App\Helpers\TableFilterHelper;
            @endphp
            <x-table-filter 
                :title="$isUnfinishedSignup == 1 ? 'Unfinished Signups' : 'Subscribed for newsletters'" 
                :data="$registers"
                :columns="[
                    TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                    TableFilterHelper::textColumn('Email'),
                    TableFilterHelper::textColumn('First Name'),
                    TableFilterHelper::textColumn('Last Name'),
                    TableFilterHelper::textColumn('Created At'),
                    TableFilterHelper::clearButtonColumn('Actions', 'action-column-1'),
                ]"
            >
                @forelse($registers as $register)
                    <tr class="data-row">
                        <td @dataColumn(0)>{{ $register->id }}</td>
                        <td @dataColumn(1)>{{ $register->email }}</td>
                        <td @dataColumn(2)>{{ $register->first_name }}</td>
                        <td @dataColumn(3)>{{ $register->last_name }}</td>
                        <td @dataColumn(4)>{{ $register->created_at->format('M d, Y') }}</td>
                        <td @dataColumn(5)>
                            <div class="btn-group">
                                <!-- No actions for register records -->
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No registers found.</td>
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
.action-column-1 {
    width: 100px;
}
</style>
@endsection
