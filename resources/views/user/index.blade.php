@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Accounts</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('user.to-csv', request()->query()) }}" class="btn btn-default">
                <i class="fas fa-download"></i> to .CSV
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <div class="summary" style="margin: 0 0 10px 0;">
            @if($users->total() > 0)
                Showing <b>{{ $users->firstItem() }}-{{ $users->lastItem() }}</b> of <b>{{ $users->total() }}</b> items.
            @else
                Showing <b>0-0</b> of <b>0</b> items.
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phones</th>
                        <th>Status</th>
                        <th>Influencer</th>
                        <th>Test</th>
                        <th>Refer</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="action-column-2">Actions</th>
                    </tr>
                    <tr class="filters">
                        <td><input type="text" class="form-control" name="id" value="{{ request('id') }}" placeholder="ID"></td>
                        <td><input type="text" class="form-control" name="first_name" value="{{ request('first_name') }}" placeholder="First Name"></td>
                        <td><input type="text" class="form-control" name="last_name" value="{{ request('last_name') }}" placeholder="Last Name"></td>
                        <td><input type="text" class="form-control" name="email" value="{{ request('email') }}" placeholder="Email"></td>
                        <td></td>
                        <td>
                            <select class="form-control" name="status">
                                <option value="">All</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="is_influencer">
                                <option value="">All</option>
                                <option value="1" {{ request('is_influencer') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('is_influencer') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="is_test">
                                <option value="">All</option>
                                <option value="1" {{ request('is_test') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('is_test') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="refer_type">
                                <option value="">All</option>
                                <option value="1" {{ request('refer_type') == '1' ? 'selected' : '' }}>Direct</option>
                                <option value="2" {{ request('refer_type') == '2' ? 'selected' : '' }}>Referral</option>
                            </select>
                        </td>
                        <td><input type="date" class="form-control" name="created_at" value="{{ request('created_at') }}"></td>
                        <td><input type="date" class="form-control" name="updated_at" value="{{ request('updated_at') }}"></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->phoneNumbers as $phone)
                                    <span class="{{ $phone->is_valid ? '' : 'text-danger' }}">
                                        {{ $phone->number }}
                                    </span>
                                    @if(!$loop->last)<br>@endif
                                @endforeach
                            </td>
                            <td>
                                @if($user->status == 1)
                                    <span class="label label-success">Active</span>
                                @else
                                    <span class="label label-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_influencer)
                                    <span class="text-success"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_test)
                                    <span class="text-success"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td>{{ $user->refer_type ?? 'N/A' }}</td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>{{ $user->updated_at->format('M d, Y') }}</td>
                            <td>
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
                </tbody>
            </table>
        </div>

        @if(method_exists($users, 'links'))
            <div class="text-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const filters = document.querySelectorAll('.filters input, .filters select');
    filters.forEach(filter => {
        filter.addEventListener('change', function() {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route('user.index') }}';
            
            filters.forEach(f => {
                if (f.value) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = f.name;
                    input.value = f.value;
                    form.appendChild(input);
                }
            });
            
            document.body.appendChild(form);
            form.submit();
        });
    });
});
</script>
@endsection
