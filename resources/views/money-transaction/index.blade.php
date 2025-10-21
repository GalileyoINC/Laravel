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
            @php
            use App\Helpers\TableFilterHelper;
            @endphp
            <x-table-filter 
                :title="'Money Transactions'" 
                :data="$transactions"
                :columns="[
                    TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                    TableFilterHelper::textColumn('First Name'),
                    TableFilterHelper::textColumn('Last Name'),
                    TableFilterHelper::textColumn('Invoice'),
                    TableFilterHelper::textColumn('Credit Card'),
                    TableFilterHelper::selectColumn('Transaction Type', ['1' => 'Payment', '2' => 'Refund', '3' => 'Void']),
                    TableFilterHelper::textColumn('Transaction ID'),
                    TableFilterHelper::selectColumn('Success', ['1' => 'Yes', '0' => 'No']),
                    TableFilterHelper::selectColumn('Void', ['1' => 'Yes', '0' => 'No']),
                    TableFilterHelper::selectColumn('Test', ['1' => 'Yes', '0' => 'No']),
                    TableFilterHelper::textColumn('Total'),
                    TableFilterHelper::textColumn('Created At'),
                    TableFilterHelper::clearButtonColumn('Actions', 'action-column-5'),
                ]"
            >
                @forelse($transactions as $transaction)
                    <tr class="data-row">
                        <td @dataColumn(0)>{{ $transaction->id }}</td>
                        <td @dataColumn(1)>
                            <a href="{{ route('user.show', $transaction->user->id) }}">{{ $transaction->user->first_name }}</a>
                        </td>
                        <td @dataColumn(2)>
                            <a href="{{ route('user.show', $transaction->user->id) }}">{{ $transaction->user->last_name }}</a>
                        </td>
                        <td @dataColumn(3)>
                            <a href="{{ route('invoice.show', $transaction->id_invoice) }}" class="JS__load_in_modal">{{ $transaction->id_invoice }}</a>
                        </td>
                        <td @dataColumn(4)>
                            @if($transaction->creditCard)
                                <a href="{{ route('credit-card.show', $transaction->id_credit_card) }}" class="JS__load_in_modal">
                                    {{ $transaction->creditCard->type ? $transaction->creditCard->type . ' ' : '' }}
                                    {{ $transaction->creditCard->num }}
                                    ({{ $transaction->creditCard->expiration_year }}/{{ $transaction->creditCard->expiration_month }})
                                </a>
                            @endif
                        </td>
                        <td @dataColumn(5) @dataValue((string) $transaction->transaction_type)>
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
                        <td @dataColumn(6)>{{ $transaction->transaction_id }}</td>
                        <td @dataColumn(7) @dataValue($transaction->is_success ? '1' : '0')>
                            @if($transaction->is_success)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-danger">No</span>
                            @endif
                        </td>
                        <td @dataColumn(8) @dataValue($transaction->is_void ? '1' : '0')>
                            @if($transaction->is_void)
                                <span class="badge badge-warning">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td @dataColumn(9) @dataValue($transaction->is_test ? '1' : '0')>
                            @if($transaction->is_test)
                                <span class="badge badge-info">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td @dataColumn(10)>{{ number_format($transaction->total, 2) }} {{ config('app.currency', '$') }}</td>
                        <td @dataColumn(11)>{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td @dataColumn(12)>
                            <div class="btn-group">
                                <a href="{{ route('money-transaction.show', $transaction) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye fa-fw"></i>
                                </a>
                                @if($transaction->canBeVoided())
                                    <form action="{{ route('money-transaction.void', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to void this payment?');">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-danger">Void</button>
                                    </form>
                                @endif
                                @if($transaction->canBeRefund())
                                    <a href="{{ route('money-transaction.refund', $transaction) }}" class="btn btn-sm btn-danger JS__load_in_modal">Refund</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">No transactions found.</td>
                    </tr>
                @endforelse
            </x-table-filter>
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
