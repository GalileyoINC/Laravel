@extends('layouts.app')

@section('title', 'User Point Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">User Point Report</h3>
                </div>
                <div class="panel-body">
                    <div class="summary" style="margin-bottom:10px;">
                        @php($total = is_array($data ?? null) ? count($data) : 0)
                        Showing <b>{{ $total }}</b> records.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Points</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($data ?? []) as $row)
                                    <tr>
                                        <td>{{ $row['name'] ?? '-' }}</td>
                                        <td>{{ $row['email'] ?? '-' }}</td>
                                        <td>{{ $row['points'] ?? 0 }}</td>
                                        <td>{{ isset($row['updated_at']) && $row['updated_at'] ? \Carbon\Carbon::parse($row['updated_at'])->format('M d, Y H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
