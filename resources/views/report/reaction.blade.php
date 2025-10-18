@extends('layouts.app')

@section('title', 'Reaction Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Reaction Report</h3>
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
                                    <th>Type</th>
                                    <th>Count</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($data ?? []) as $row)
                                    <tr>
                                        <td>{{ $row['name'] ?? '-' }}</td>
                                        <td>{{ $row['type'] ?? '-' }}</td>
                                        <td>{{ $row['count'] ?? 0 }}</td>
                                        <td>{{ isset($row['date']) && $row['date'] ? \Carbon\Carbon::parse($row['date'])->format('M d, Y') : '-' }}</td>
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
