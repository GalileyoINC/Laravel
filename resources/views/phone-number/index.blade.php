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
        <div class="summary" style="margin: 0 0 10px 0;">
            @if($phoneNumbers->total() > 0)
                Showing <b>{{ $phoneNumbers->firstItem() }}-{{ $phoneNumbers->lastItem() }}</b> of <b>{{ $phoneNumbers->total() }}</b> items.
            @else
                Showing <b>0-0</b> of <b>0</b> items.
            @endif
        </div>
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
                    <tr class="filters">
                        <td><input type="text" class="form-control" name="id" value="{{ request('id') }}" placeholder="ID"></td>
                        <td><input type="text" class="form-control" name="number" value="{{ request('number') }}" placeholder="Number"></td>
                        <td><input type="text" class="form-control" name="userName" value="{{ request('userName') }}" placeholder="User"></td>
                        <td><input type="text" class="form-control" name="type" value="{{ request('type') }}" placeholder="Type"></td>
                        <td>
                            <select class="form-control" name="is_valid">
                                <option value=""></option>
                                <option value="1" {{ request('is_valid') === '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('is_valid') === '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="is_primary">
                                <option value=""></option>
                                <option value="1" {{ request('is_primary') === '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('is_primary') === '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="is_active">
                                <option value=""></option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        <td><input type="date" class="form-control" name="created_at" value="{{ request('created_at') }}"></td>
                        <td>
                            <button type="submit" class="btn btn-primary" onclick="applyFilters(this)">Filter</button>
                            <a href="{{ route('phone-number.index') }}" class="btn btn-default ml-2">Clear</a>
                        </td>
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
                            <td class="text-center">
                                @if($phone->is_active)
                                    <span class="text-success"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times"></i></span>
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
