@extends('layouts.app')

@section('content')
@php
use App\Helpers\TableFilterHelper;
@endphp

<x-table-filter 
    :title="'Phone Numbers'" 
    :data="$phoneNumbers"
    :export-route="route('phone-number.export')"
    :columns="[
        TableFilterHelper::textColumn('ID', 'ID'),
        TableFilterHelper::textColumn('Number'),
        TableFilterHelper::textColumn('User'),
        TableFilterHelper::textColumn('Type'),
        TableFilterHelper::selectColumn('Valid', TableFilterHelper::booleanOptions()),
        TableFilterHelper::selectColumn('Primary', TableFilterHelper::booleanOptions()),
        TableFilterHelper::selectColumn('Active', ['1' => 'Active', '0' => 'Inactive']),
        TableFilterHelper::textColumn('Created At'),
        TableFilterHelper::clearButtonColumn(),
    ]"
>
    <x-slot name="headerActions">
        <a href="{{ route('phone-number.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Create Phone Number
        </a>
    </x-slot>

    @forelse($phoneNumbers as $phone)
        <tr class="data-row">
            <td @dataColumn(0)>{{ $phone->id }}</td>
            <td @dataColumn(1)>{{ $phone->number }}</td>
            <td @dataColumn(2)>
                @if($phone->user)
                    <a href="{{ route('user.show', $phone->user) }}">
                        {{ $phone->user->first_name }} {{ $phone->user->last_name }}
                    </a>
                @else
                    N/A
                @endif
            </td>
            <td @dataColumn(3)>{{ $phone->type_name ?? 'N/A' }}</td>
            <td @dataColumn(4) @dataValue($phone->is_valid ? 'yes' : 'no')>
                @if($phone->is_valid)
                    <span class="text-success"><i class="fas fa-check"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i></span>
                @endif
            </td>
            <td @dataColumn(5) @dataValue($phone->is_primary ? 'yes' : 'no')>
                @if($phone->is_primary)
                    <span class="text-success"><i class="fas fa-check"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i></span>
                @endif
            </td>
            <td @dataColumn(6) @dataValue($phone->is_active ? '1' : '0')>
                @if($phone->is_active)
                    <span class="text-success"><i class="fas fa-check"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i></span>
                @endif
            </td>
            <td @dataColumn(7)>{{ $phone->created_at->format('M d, Y') }}</td>
            <td @dataColumn(8)>
                <div class="btn-group">
                    <a href="{{ route('phone-number.show', $phone) }}" class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('phone-number.edit', $phone) }}" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="{{ route('phone-number.send-sms', $phone) }}" class="btn btn-sm btn-warning" title="Send SMS">
                        <i class="fas fa-sms"></i>
                    </a>
                    <form action="{{ route('phone-number.destroy', $phone) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">No phone numbers found.</td>
        </tr>
    @endforelse
</x-table-filter>
@endsection
