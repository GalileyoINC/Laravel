@extends('layouts.app')

@section('title', 'Ended Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Ended Report</h3>
                </div>
                <div class="panel-body">
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @php($total = is_array($data ?? null) ? count($data) : 0)
                        Showing <b>{{ $total }}</b> records.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Service</th>
                                    <th>Ended At</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($data ?? []) as $row)
                                    <tr>
                                        <td>{{ $row['name'] ?? '-' }}</td>
                                        <td>{{ $row['service'] ?? '-' }}</td>
                                        <td>{{ isset($row['ended_at']) && $row['ended_at'] ? \Carbon\Carbon::parse($row['ended_at'])->format('M d, Y') : '-' }}</td>
                                        <td>{{ $row['reason'] ?? '-' }}</td>
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
