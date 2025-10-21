@extends('layouts.app')

@section('title', 'Followers - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Followers</h3>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('follower.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Followers'" 
                        :data="$followers"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::textColumn('List'),
                            TableFilterHelper::textColumn('Leader'),
                            TableFilterHelper::textColumn('Follower'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::textColumn('Updated At'),
                        ]"
                    >
                        @forelse($followers as $follower)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $follower->id }}</td>
                                <td @dataColumn(1)>{{ $follower->followerList ? $follower->followerList->name : '-' }}</td>
                                <td @dataColumn(2)>
                                    @if($follower->userLeader)
                                        <a href="{{ route('user.show', $follower->userLeader) }}">
                                            {{ $follower->userLeader->first_name }} {{ $follower->userLeader->last_name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td @dataColumn(3)>
                                    @if($follower->userFollower)
                                        <a href="{{ route('user.show', $follower->userFollower) }}">
                                            {{ $follower->userFollower->first_name }} {{ $follower->userFollower->last_name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td @dataColumn(4)>{{ $follower->created_at->format('M d, Y') }}</td>
                                <td @dataColumn(5)>{{ $follower->updated_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No followers found.</td>
                            </tr>
                        @endforelse
                    </x-table-filter>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
.panel-body {
    padding: 15px;
}
.grid__id {
    width: 60px;
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #f8f9fa;
    font-weight: 600;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-inline .form-group {
    display: flex;
    flex: 0 0 auto;
    flex-flow: row wrap;
    align-items: center;
    margin-bottom: 0;
}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
.text-center {
    text-align: center;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
@endsection
