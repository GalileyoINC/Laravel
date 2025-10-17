@extends('web.layouts.app')

@section('title', 'Money Transactions - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Money Transactions</h1>
        <a href="{{ route('web.money-transaction.to-csv', request()->query()) }}" class="btn btn-default">
            to .CSV
        </a>
    </div>

    <div class="panel-body">
        <h3>Total: {{ number_format($totalSum, 2) }} {{ config('app.currency', '$') }}</h3>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transaction Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('web.money-transaction.index') }}" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by ID, transaction ID, or user" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="transaction_type" class="form-control">
                            <option value="">Select Type</option>
                            <option value="1" {{ ($filters['transaction_type'] ?? '') == '1' ? 'selected' : '' }}>Payment</option>
                            <option value="2" {{ ($filters['transaction_type'] ?? '') == '2' ? 'selected' : '' }}>Refund</option>
                            <option value="3" {{ ($filters['transaction_type'] ?? '') == '3' ? 'selected' : '' }}>Void</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="is_success" class="form-control">
                            <option value="">Select Success</option>
                            <option value="1" {{ ($filters['is_success'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ ($filters['is_success'] ?? '') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="is_void" class="form-control">
                            <option value="">Select Void</option>
                            <option value="1" {{ ($filters['is_void'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ ($filters['is_void'] ?? '') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="is_test" class="form-control">
                            <option value="">Select Test</option>
                            <option value="1" {{ ($filters['is_test'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ ($filters['is_test'] ?? '') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="text" name="createTimeRange" class="form-control" placeholder="Date Range" value="{{ $filters['createTimeRange'] ?? '' }}" id="dateRangePicker">
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <input type="number" name="total_min" class="form-control" placeholder="Min Total" value="{{ $filters['total_min'] ?? '' }}" step="0.01">
                            </div>
                            <div class="col-6">
                                <input type="number" name="total_max" class="form-control" placeholder="Max Total" value="{{ $filters['total_max'] ?? '' }}" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('web.money-transaction.index') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Invoice</th>
                        <th>Credit Card</th>
                        <th>Transaction Type</th>
                        <th>Transaction ID</th>
                        <th>Success</th>
                        <th>Void</th>
                        <th>Test</th>
                        <th>Total</th>
                        <th>Created At</th>
                        <th class="action-column-5">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>
                                <a href="{{ route('web.user.show', $transaction->user->id) }}">
                                    {{ $transaction->user->first_name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('web.user.show', $transaction->user->id) }}">
                                    {{ $transaction->user->last_name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('web.invoice.show', $transaction->id_invoice) }}" class="JS__load_in_modal">
                                    {{ $transaction->id_invoice }}
                                </a>
                            </td>
                            <td>
                                @if($transaction->creditCard)
                                    <a href="{{ route('web.credit-card.show', $transaction->id_credit_card) }}" class="JS__load_in_modal">
                                        {{ $transaction->creditCard->type ? $transaction->creditCard->type . ' ' : '' }}
                                        {{ $transaction->creditCard->num }}
                                        ({{ $transaction->creditCard->expiration_year }}/{{ $transaction->creditCard->expiration_month }})
                                    </a>
                                @endif
                            </td>
                            <td>
                                @switch($transaction->transaction_type)
                                    @case(1)
                                        Payment
                                        @break
                                    @case(2)
                                        Refund
                                        @break
                                    @case(3)
                                        Void
                                        @break
                                    @default
                                        Unknown
                                @endswitch
                            </td>
                            <td>{{ $transaction->transaction_id }}</td>
                            <td>
                                @if($transaction->is_success)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->is_void)
                                    <span class="badge badge-warning">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->is_test)
                                    <span class="badge badge-info">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>{{ number_format($transaction->total, 2) }} {{ config('app.currency', '$') }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('web.money-transaction.show', $transaction) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    @if($transaction->canBeVoided())
                                        <form action="{{ route('web.money-transaction.void', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to void this payment?');">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Void
                                            </button>
                                        </form>
                                    @endif
                                    @if($transaction->canBeRefund())
                                        <a href="{{ route('web.money-transaction.refund', $transaction) }}" class="btn btn-sm btn-danger JS__load_in_modal">
                                            Refund
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">No transactions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.grid__id {
    width: 60px;
}
.action-column-5 {
    width: 200px;
}
.panel-body {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-danger {
    background-color: #e74a3b;
}
.badge-warning {
    background-color: #f6c23e;
}
.badge-info {
    background-color: #36b9cc;
}
.badge-secondary {
    background-color: #858796;
}
</style>

<script>
// Date range picker functionality
document.addEventListener('DOMContentLoaded', function() {
    const dateRangePicker = document.getElementById('dateRangePicker');
    if (dateRangePicker) {
        // You can integrate a proper date range picker library here
        dateRangePicker.addEventListener('focus', function() {
            this.type = 'date';
        });
    }
});
</script>
@endsection
