@extends('layouts.app')

@section('title', 'Staff - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Staff</h1>
        <a href="{{ route('staff.create') }}" class="btn btn-success JS__load_in_modal">
            <i class="fas fa-plus"></i> Create Staff
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            @php
            use App\Helpers\TableFilterHelper;
            @endphp
            <x-table-filter 
                :title="'Staff'" 
                :data="$staff"
                :columns="[
                    TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                    TableFilterHelper::textColumn('Username'),
                    TableFilterHelper::textColumn('Email'),
                    TableFilterHelper::selectColumn('Role', ['super' => 'Super Admin', 'admin' => 'Admin']),
                    TableFilterHelper::selectColumn('Status', ['1' => 'Active', '0' => 'Inactive']),
                    TableFilterHelper::textColumn('Created At'),
                    TableFilterHelper::clearButtonColumn('Actions', (Auth::user()->isSuper() ? 'action-column-4' : 'action-column-2')),
                ]"
            >
                @forelse($staff as $member)
                    <tr class="data-row">
                        <td @dataColumn(0) class="{{ Auth::user()->isSuper() ? 'bg-admin' : '' }}">{{ $member->id }}</td>
                        <td @dataColumn(1)>{{ $member->username }}</td>
                        <td @dataColumn(2)>{{ $member->email }}</td>
                        <td @dataColumn(3) @dataValue($member->role === 1 ? 'super' : 'admin')>
                            @if($member->role === 1)
                                <span class="badge badge-danger">Super Admin</span>
                            @else
                                <span class="badge badge-info">Admin</span>
                            @endif
                        </td>
                        <td @dataColumn(4) @dataValue($member->status === 1 ? '1' : '0')>
                            @if($member->status === 1)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-warning">Inactive</span>
                            @endif
                        </td>
                        <td @dataColumn(5)>{{ $member->created_at->format('M d, Y') }}</td>
                        <td @dataColumn(6)>
                            <div class="btn-group">
                                <a href="{{ route('staff.show', $member) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye fa-fw"></i>
                                </a>
                                <a href="{{ route('staff.edit', $member) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit fa-fw"></i>
                                </a>
                                @if(Auth::user()->isSuper())
                                    <form action="{{ route('staff.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-admin">
                                            <i class="fas fa-trash fa-fw"></i>
                                        </button>
                                    </form>
                                    @if(!$member->isSuper())
                                        <a href="{{ route('staff.login-as', $member) }}" class="btn btn-sm btn-admin" title="Login as {{ $member->username }}">
                                            <i class="fas fa-sign-in-alt fa-fw"></i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No staff found.</td>
                    </tr>
                @endforelse
            </x-table-filter>
        </div>
    </div>
</div>

<style>
.bg-admin {
    background-color: #f8d7da !important;
}
.action-column-2 {
    width: 120px;
}
.action-column-4 {
    width: 200px;
}
.btn-admin {
    background-color: #d73925;
    border-color: #d73925;
    color: white;
}
.btn-admin:hover {
    background-color: #c23321;
    border-color: #c23321;
    color: white;
}
.grid__id {
    width: 60px;
}
.badge-danger {
    background-color: #e74a3b;
}
.badge-info {
    background-color: #36b9cc;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-warning {
    background-color: #f6c23e;
}
</style>
@endsection
