@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Sat Devices</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('phone-number.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create Phone Number
            </a>
            <a href="{{ route('phone-number.export') }}" class="btn btn-default">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Number</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Valid</th>
                        <th>Primary</th>
                        <th>Active</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($phoneNumbers as $phone)
                        <tr>
                            <td>{{ $phone->id }}</td>
                            <td>{{ $phone->number }}</td>
                            <td>
                                @if($phone->user)
                                    <a href="{{ route('user.show', $phone->user) }}">
                                        {{ $phone->user->first_name }} {{ $phone->user->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $phone->type_name ?? 'N/A' }}</td>
                            <td>
                                @if($phone->is_valid)
                                    <span class="text-success"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td>
                                @if($phone->is_primary)
                                    <span class="text-success"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td>
                                @if($phone->is_active)
                                    <span class="label label-success">Active</span>
                                @else
                                    <span class="label label-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $phone->created_at->format('M d, Y') }}</td>
                            <td>
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
                </tbody>
            </table>
        </div>

        @if(method_exists($phoneNumbers, 'links'))
            <div class="text-center">
                {{ $phoneNumbers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
