@extends('layouts.app')

@section('title', 'Money Transactions - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Money Transactions</h1>
        <a href="{{ route('money-transaction.to-csv', request()->query()) }}" class="btn btn-default">
            to .CSV
        </a>
    </div>

    <div class="panel-body">
        <h3>Total: {{ number_format($totalSum, 2) }} {{ config('app.currency', '$') }}</h3>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Summary -->
            <div class="summary" style="margin-bottom:10px;">
                @if($transactions->total() > 0)
                    Showing <b>{{ $transactions->firstItem() }}-{{ $transactions->lastItem() }}</b> of <b>{{ $transactions->total() }}</b> items.
                @else
                    Showing <b>0-0</b> of <b>0</b> items.
                @endif
            </div>

            <div class="table-responsive">
                <form action="{{ route('money-transaction.index') }}" method="GET" id="filters-form"></form>
                <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                    <tr class="filters">
                        <td>
                            <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <select name="transaction_type" class="form-control" form="filters-form">
                                <option value=""></option>
                                <option value="1" {{ ($filters['transaction_type'] ?? '') == '1' ? 'selected' : '' }}>Payment</option>
                                <option value="2" {{ ($filters['transaction_type'] ?? '') == '2' ? 'selected' : '' }}>Refund</option>
                                <option value="3" {{ ($filters['transaction_type'] ?? '') == '3' ? 'selected' : '' }}>Void</option>
                            </select>
                        </td>
                        <td></td>
                        <td>
                            <select name="is_success" class="form-control" form="filters-form">
                                <option value=""></option>
                                <option value="1" {{ ($filters['is_success'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($filters['is_success'] ?? '') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select name="is_void" class="form-control" form="filters-form">
                                <option value=""></option>
                                <option value="1" {{ ($filters['is_void'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($filters['is_void'] ?? '') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select name="is_test" class="form-control" form="filters-form">
                                <option value=""></option>
                                <option value="1" {{ ($filters['is_test'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($filters['is_test'] ?? '') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <div class="row" style="gap:6px;">
                                <input type="number" name="total_min" class="form-control" placeholder="Min" value="{{ $filters['total_min'] ?? '' }}" step="0.01" style="max-width:120px;">
                                <input type="number" name="total_max" class="form-control" placeholder="Max" value="{{ $filters['total_max'] ?? '' }}" step="0.01" style="max-width:120px;">
                            </div>
                        </td>
                        <td>
                            <input type="date" name="created_at" class="form-control" form="filters-form" value="{{ $filters['created_at'] ?? '' }}">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                            <a href="{{ route('money-transaction.index') }}" class="btn btn-default ml-2">Clear</a>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>
                                <a href="{{ route('user.show', $transaction->user->id) }}">
                                    {{ $transaction->user->first_name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('user.show', $transaction->user->id) }}">
                                    {{ $transaction->user->last_name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('invoice.show', $transaction->id_invoice) }}" class="JS__load_in_modal">
                                    {{ $transaction->id_invoice }}
                                </a>
                            </td>
                            <td>
                                @if($transaction->creditCard)
                                    <a href="{{ route('credit-card.show', $transaction->id_credit_card) }}" class="JS__load_in_modal">
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
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('money-transaction.show', $transaction) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    @if($transaction->canBeVoided())
                                        <form action="{{ route('money-transaction.void', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to void this payment?');">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Void
                                            </button>
                                        </form>
                                    @endif
                                    @if($transaction->canBeRefund())
                                        <a href="{{ route('money-transaction.refund', $transaction) }}" class="btn btn-sm btn-danger JS__load_in_modal">
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
