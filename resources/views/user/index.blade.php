@extends('layouts.app')

@section('content')
@php
use App\Helpers\TableFilterHelper;
@endphp

<x-table-filter 
    :title="'Accounts'" 
    :data="$users"
    :export-route="route('user.to-csv', request()->query())"
    :columns="TableFilterHelper::userTableColumns()"
>
    @forelse($users as $user)
        <tr class="data-row">
            <td @dataColumn(0)>{{ $user->id }}</td>
            <td @dataColumn(1)>{{ $user->first_name }}</td>
            <td @dataColumn(2)>{{ $user->last_name }}</td>
            <td @dataColumn(3)>{{ $user->email }}</td>
            <td @dataColumn(4)>
                @foreach($user->phoneNumbers as $phone)
                    <span class="{{ $phone->is_valid ? '' : 'text-danger' }}">
                        {{ $phone->number }}
                    </span>
                    @if(!$loop->last)<br>@endif
                @endforeach
            </td>
            <td @dataColumn(5) @dataStatus($user->status == 1 ? 'active' : 'cancelled')>
                @if($user->status == 1)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Cancelled</span>
                @endif
            </td>
            <td @dataColumn(6) @dataValue($user->is_influencer ? 'yes' : 'no')>
                @if($user->is_influencer)
                    <span class="text-success"><i class="fas fa-check"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i></span>
                @endif
            </td>
            <td @dataColumn(7) @dataValue($user->is_test ? 'yes' : 'no')>
                @if($user->is_test)
                    <span class="text-success"><i class="fas fa-check"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i></span>
                @endif
            </td>
            <td @dataColumn(8)>{{ $user->refer_type ?? 'N/A' }}</td>
            <td @dataColumn(9)>{{ $user->created_at->format('M d, Y') }}</td>
            <td @dataColumn(10)>{{ $user->updated_at->format('M d, Y') }}</td>
            <td @dataColumn(11)>
                <div class="btn-group">
                    <a href="{{ route('user.show', $user) }}" class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-primary" title="Update">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="12" class="text-center">No users found.</td>
        </tr>
    @endforelse
</x-table-filter>
@endsection
