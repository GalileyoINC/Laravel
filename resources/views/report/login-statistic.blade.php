@extends('layouts.app')

@section('title', 'Login Statistic - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Login Statistic</h3>
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
                                    <th>Last Login</th>
                                    <th>Count (month)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($data ?? []) as $row)
                                    <tr>
                                        <td>{{ $row['name'] ?? '-' }}</td>
                                        <td>{{ $row['email'] ?? '-' }}</td>
                                        <td>{{ isset($row['last_login']) && $row['last_login'] ? \Carbon\Carbon::parse($row['last_login'])->format('M d, Y H:i') : '-' }}</td>
                                        <td>{{ $row['count_month'] ?? 0 }}</td>
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
