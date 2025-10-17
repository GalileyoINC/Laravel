@extends('web.layouts.app')

@section('title', 'Active Users and Plans - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="table-responsive">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-heading">
                            <span>{{ $title ?? 'Active Users and Plans' }}</span>
                            <div class="pull-right">
                                <select name="date" class="form-control" style="width:200px; display:inline-block">
                                    @foreach($months as $key => $month)
                                        <option value="{{ $key }}" {{ $date == $key ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Annual</th>
                            <th>Monthly</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Active users</td>
                            <td class="text-right">{{ $activeUserCount }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td>Active plans</td>
                            <td class="text-right">{{ $activePlanCountAll }}</td>
                            <td class="text-right">{{ $activePlanCountAnnual }}</td>
                            <td class="text-right">{{ $activePlanCountMonth }}</td>
                        </tr>
                        <tr>
                            <td>SPS users</td>
                            <td class="text-right">{{ $spsUsers }}</td>
                            <td colspan="2"></td>
                        </tr>
                        @php
                            $spsTotal = $sps1 = $sps12 = 0;
                            foreach ($sps as $value) {
                                $spsTotal += $value['cnt'];
                                switch ($value['pay_interval']) {
                                    case '1':
                                        $sps1 += $value['cnt'];
                                        break;
                                    case '12':
                                        $sps12 += $value['cnt'];
                                        break;
                                }
                            }
                        @endphp
                        <tr>
                            <td>SPS plans</td>
                            <td class="text-right">{{ $spsTotal }}</td>
                            <td class="text-right">{{ $sps12 }}</td>
                            <td class="text-right">{{ $sps1 }}</td>
                        </tr>
                        <tr>
                            <td>Bivy Sticks</td>
                            <td class="text-right">{{ $bivyCount }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td>Pivotel phones</td>
                            <td class="text-right">{{ $pivotelCount }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td>Satellite phones</td>
                            <td class="text-right">{{ $satelliteCount }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>

                @php
                    $tbl2 = [];
                    foreach ($sps as $row) {
                        $tbl2[$row['id_service']][$row['pay_interval']] = $row['cnt'];
                    }
                @endphp

                <table class="table table-bordered">
                    <tr>
                        <th width="30%">SPS</th>
                        <th class="text-right">Annual</th>
                        <th class="text-right">Monthly</th>
                    </tr>
                    @php
                        $annual = $monthly = 0;
                        $services = \App\Models\System\Service::all()->keyBy('id')->toArray();
                    @endphp
                    @foreach($tbl2 as $idService => $value)
                        <tr>
                            <td>{{ $services[$idService]['name'] ?? 'Unknown' }}</td>
                            <td class="text-right">{{ $value[12] ?? 0 }}</td>
                            <td class="text-right">{{ $value[1] ?? 0 }}</td>
                        </tr>
                        @php
                            $annual += $value[12] ?? 0;
                            $monthly += $value[1] ?? 0;
                        @endphp
                    @endforeach
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ $annual ?? 0 }}</strong></td>
                        <td class="text-right"><strong>{{ $monthly ?? 0 }}</strong></td>
                    </tr>
                </table>

                @php
                    $tbl2 = $tbl3 = [];
                    foreach ($newPlans as $row) {
                        $tbl2[$row['id_service']][$row['pay_interval']] = $row['cnt'];
                    }
                    foreach ($newPlansSps as $row) {
                        $tbl3[$row['id_service']][$row['pay_interval']] = $row['cnt'];
                    }
                @endphp

                <table class="table table-bordered">
                    <tr>
                        <th width="30%">New Plans (period)</th>
                        <th class="text-right">Annual</th>
                        <th class="text-right">Annual SPS</th>
                        <th class="text-right">Monthly</th>
                        <th class="text-right">Monthly SPS</th>
                    </tr>
                    @php
                        $annual = $monthly = $annualSps = $monthlySps = 0;
                    @endphp
                    @foreach($tbl2 as $idService => $value)
                        <tr>
                            <td>{{ $services[$idService]['name'] ?? 'Unknown' }}</td>
                            <td class="text-right">{{ $value[12] ?? 0 }}</td>
                            <td class="text-right">{{ $tbl3[$idService][12] ?? 0 }}</td>
                            <td class="text-right">{{ $value[1] ?? 0 }}</td>
                            <td class="text-right">{{ $tbl3[$idService][1] ?? 0 }}</td>
                        </tr>
                        @php
                            $annual += $value[12] ?? 0;
                            $monthly += $value[1] ?? 0;
                            $annualSps += $tbl3[$idService][12] ?? 0;
                            $monthlySps += $tbl3[$idService][1] ?? 0;
                        @endphp
                    @endforeach
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ $annual ?? 0 }}</strong></td>
                        <td class="text-right"><strong>{{ $annualSps ?? 0 }}</strong></td>
                        <td class="text-right"><strong>{{ $monthly ?? 0 }}</strong></td>
                        <td class="text-right"><strong>{{ $monthlySps ?? 0 }}</strong></td>
                    </tr>
                </table>

                @if($yesterdayPlans !== null)
                    @php
                        $tbl2 = [];
                        foreach ($yesterdayPlans as $row) {
                            $tbl2[$row['id_service']][$row['pay_interval']] = $row['cnt'];
                        }
                    @endphp

                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">New Plans (yesterday)</th>
                            <th class="text-right">Annual</th>
                            <th class="text-right">Monthly</th>
                        </tr>
                        @php
                            $annual = $monthly = 0;
                        @endphp
                        @foreach($tbl2 as $idService => $value)
                            <tr>
                                <td>{{ $services[$idService]['name'] ?? 'Unknown' }}</td>
                                <td class="text-right">{{ $value[12] ?? 0 }}</td>
                                <td class="text-right">{{ $value[1] ?? 0 }}</td>
                            </tr>
                            @php
                                $annual += $value[12] ?? 0;
                                $monthly += $value[1] ?? 0;
                            @endphp
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td class="text-right"><strong>{{ $annual ?? 0 }}</strong></td>
                            <td class="text-right"><strong>{{ $monthly ?? 0 }}</strong></td>
                        </tr>
                    </table>
                @else
                    @php
                        $tbl2 = [];
                        foreach ($newPlans as $row) {
                            $tbl2[$row['id_service']][$row['pay_interval']] = $row['cnt'];
                        }
                    @endphp

                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">New Plans (average)</th>
                            <th class="text-right">Annual</th>
                            <th class="text-right">Monthly</th>
                        </tr>
                        @php
                            $annual = $monthly = 0;
                        @endphp
                        @foreach($tbl2 as $idService => $value)
                            <tr>
                                <td>{{ $services[$idService]['name'] ?? 'Unknown' }}</td>
                                <td class="text-right">{{ number_format(($value[12] ?? 0) / $inMonth, 1) }}</td>
                                <td class="text-right">{{ number_format(($value[1] ?? 0) / $inMonth, 1) }}</td>
                            </tr>
                            @php
                                $annual += $value[12] ?? 0;
                                $monthly += $value[1] ?? 0;
                            @endphp
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td class="text-right"><strong>{{ number_format(($annual ?? 0) / $inMonth, 1) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format(($monthly ?? 0) / $inMonth, 1) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @php
                    $tbl2 = [];
                    foreach ($currentPlans as $row) {
                        $tbl2[$row['id_service']][$row['pay_interval']] = $row['cnt'];
                    }
                @endphp

                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Active Plans</th>
                        <th class="text-right">Annual</th>
                        <th class="text-right">Monthly</th>
                    </tr>
                    @php
                        $annual = $monthly = 0;
                    @endphp
                    @foreach($tbl2 as $idService => $value)
                        <tr>
                            <td>{{ $services[$idService]['name'] ?? 'Unknown' }}</td>
                            <td class="text-right">{{ $value[12] ?? 0 }}</td>
                            <td class="text-right">{{ $value[1] ?? 0 }}</td>
                        </tr>
                        @php
                            $annual += $value[12] ?? 0;
                            $monthly += $value[1] ?? 0;
                        @endphp
                    @endforeach
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ $annual ?? 0 }}</strong></td>
                        <td class="text-right"><strong>{{ $monthly ?? 0 }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $("select[name=date]").change(function () {
        location.href = '{{ route("web.report.statistic") }}' + '?date=' + $(this).val();
    })
})
</script>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
.pull-right {
    float: right;
}
.text-right {
    text-align: right;
}
</style>
@endsection
