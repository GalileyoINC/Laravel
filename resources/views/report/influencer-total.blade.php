@extends('layouts.app')

@section('title', 'Influencer Total Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Influencer Total Report</h3>
                </div>
                <div class="panel-body">
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @php($total = is_array($data ?? null) ? count($data) : 0)
                        Showing <b>{{ $total }}</b> records.
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Influencer</th>
                                    <th>Total Posts (Month)</th>
                                    <th>Total Referrals</th>
                                    <th>Total Compensation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($data ?? []) as $row)
                                    <tr>
                                        <td>{{ $row['name'] ?? '-' }}</td>
                                        <td>{{ $row['posts_month'] ?? 0 }}</td>
                                        <td>{{ $row['referrals_total'] ?? 0 }}</td>
                                        <td>{{ isset($row['comp_total']) ? number_format($row['comp_total'], 2) : '0.00' }} {{ config('app.currency', '$') }}</td>
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
