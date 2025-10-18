@extends('layouts.app')

@section('title', 'Customer Source Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Customer Source Report</h3>
                </div>
                <div class="panel-body">
                    <!-- By Month -->
                    <h4>By Month</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Source</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($byMonth = $reportByMonth ?? [])
                                @forelse($byMonth as $row)
                                    <tr>
                                        <td>{{ $row['month'] ?? '-' }}</td>
                                        <td>{{ $row['source'] ?? '-' }}</td>
                                        <td>{{ $row['count'] ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No monthly data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- By Years -->
                    <h4>By Years</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Source</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($byYears = $reportByYears ?? [])
                                @forelse($byYears as $row)
                                    <tr>
                                        <td>{{ $row['year'] ?? '-' }}</td>
                                        <td>{{ $row['source'] ?? '-' }}</td>
                                        <td>{{ $row['count'] ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No yearly data available.</td>
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
