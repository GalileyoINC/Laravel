@extends('layouts.app')

@section('title', 'SMS Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">SMS Report</h3>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search by user name or body..." value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('report.sms') }}" class="btn btn-default ml-2">Clear</a>
                    </form>

                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if(method_exists($sms, 'total') && $sms->total() > 0)
                            Showing <b>{{ $sms->firstItem() }}-{{ $sms->lastItem() }}</b> of <b>{{ $sms->total() }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>User First Name</th>
                                    <th>User Last Name</th>
                                    <th>Body</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sms as $row)
                                    <tr>
                                        <td>{{ $row->id ?? '-' }}</td>
                                        <td>{{ $row->first_name ?? '-' }}</td>
                                        <td>{{ $row->last_name ?? '-' }}</td>
                                        <td>{{ $row->body ?? '-' }}</td>
                                        <td>{{ isset($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('M d, Y H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No SMS found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $sms->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
