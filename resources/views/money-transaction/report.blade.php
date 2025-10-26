@extends('layouts.app')

@section('title', 'Money Transaction Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Money Transaction Report</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="{{ route('money-transaction.report') }}" class="form-inline">
                <div class="form-group mr-3">
                    <label for="year" class="mr-2">Select Year:</label>
                    <select name="year" id="year" class="form-control">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">View Report</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Total Amount by Month ({{ $year }})</h6>
                </div>
                <div class="card-body">
                    <canvas id="totalChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaction Count by Month ({{ $year }})</h6>
                </div>
                <div class="card-body">
                    <canvas id="countChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Success vs Failed Transactions ({{ $year }})</h6>
                </div>
                <div class="card-body">
                    <canvas id="successChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('money-transaction.index') }}" class="btn btn-default">
                <i class="fas fa-arrow-left"></i> Back to Transactions
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data from backend
const stats = @json($stats);
const months = stats.map(s => s.month);
const totals = stats.map(s => parseFloat(s.total));
const counts = stats.map(s => s.count);
const successCounts = stats.map(s => s.success_count);
const failCounts = stats.map(s => s.fail_count);

// Total Amount Chart
const totalCtx = document.getElementById('totalChart').getContext('2d');
new Chart(totalCtx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Total Amount ($)',
            data: totals,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toFixed(2);
                    }
                }
            }
        }
    }
});

// Count Chart
const countCtx = document.getElementById('countChart').getContext('2d');
new Chart(countCtx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Transaction Count',
            data: counts,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Success vs Failed Chart
const successCtx = document.getElementById('successChart').getContext('2d');
new Chart(successCtx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Successful',
                data: successCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Failed',
                data: failCounts,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true,
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
