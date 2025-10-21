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
            <td data-column="0">{{ $user->id }}</td>
            <td data-column="1">{{ $user->first_name }}</td>
            <td data-column="2">{{ $user->last_name }}</td>
            <td data-column="3">{{ $user->email }}</td>
            <td data-column="4">
                @foreach($user->phoneNumbers as $phone)
                    <span class="{{ $phone->is_valid ? '' : 'text-danger' }}">
                        {{ $phone->number }}
                    </span>
                    @if(!$loop->last)<br>@endif
                @endforeach
            </td>
            <td data-column="5" data-status="{{ $user->status == 1 ? 'active' : 'cancelled' }}">
                @if($user->status == 1)
                    <span class="label label-success">Active</span>
                @else
                    <span class="label label-danger">Cancelled</span>
                @endif
            </td>
            <td data-column="6" data-value="{{ $user->is_influencer ? 'yes' : 'no' }}">
                @if($user->is_influencer)
                    <span class="text-success"><i class="fas fa-check"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i></span>
                @endif
            </td>
            <td data-column="7" data-value="{{ $user->is_test ? 'yes' : 'no' }}">
                @if($user->is_test)
                    <span class="text-success"><i class="fas fa-check"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i></span>
                @endif
            </td>
            <td data-column="8">{{ $user->refer_type ?? 'N/A' }}</td>
            <td data-column="9">{{ $user->created_at->format('M d, Y') }}</td>
            <td data-column="10">{{ $user->updated_at->format('M d, Y') }}</td>
            <td data-column="11">
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
