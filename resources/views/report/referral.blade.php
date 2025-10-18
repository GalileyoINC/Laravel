@extends('layouts.app')

@section('title', 'Referral Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex justify-content-between align-items-center">
                    <h3 class="panel-title mb-0">Referral Report</h3>
                </div>
                <div class="panel-body">
                    <div class="mb-3">
                        <label for="reportMonth"><strong>Month:</strong></label>
                        <select id="reportMonth" class="form-control" style="max-width: 320px;" onchange="if (this.value) window.location='{{ route('report.referral') }}?month='+this.value;">
                            @foreach(($reportDates ?? []) as $key => $label)
                                <option value="{{ $key }}" {{ ($currDate ?? '') === $label ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Influencer</th>
                                    @foreach(($services ?? []) as $service)
                                        <th># Referrals ({{ $service['name'] ?? 'Service' }})</th>
                                        <th>Compensation ({{ $service['name'] ?? 'Service' }})</th>
                                    @endforeach
                                    <th>Total # of referrals</th>
                                    <th>Total compensation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($data = $report['data'] ?? [])
                                @forelse($data as $row)
                                    @php($totalRef = 0)
                                    @php($totalComp = 0)
                                    <tr>
                                        <td>{{ $row['name'] ?? '-' }}</td>
                                        @foreach(($services ?? []) as $service)
                                            @php($sid = $service['id'] ?? null)
                                            @php($refs = $sid && isset($row[$sid][1]) ? (int)$row[$sid][1] : 0)
                                            @php($comp = $sid && isset($row[$sid][0]) ? (float)$row[$sid][0] : 0)
                                            @php($totalRef += $refs)
                                            @php($totalComp += $comp)
                                            <td>{{ $refs }}</td>
                                            <td>{{ number_format($comp, 2) }} {{ config('app.currency', '$') }}</td>
                                        @endforeach
                                        <td>{{ $totalRef }}</td>
                                        <td>{{ number_format($totalComp, 2) }} {{ config('app.currency', '$') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 2 * count($services ?? []) + 3 }}" class="text-center">No data available.</td>
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
