@extends('layouts.app')

@section('title', 'Invoices - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Invoices</h1>
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
                :title="'Invoices'" 
                :data="$invoices"
                :columns="[
                    TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                    TableFilterHelper::textColumn('User First Name'),
                    TableFilterHelper::textColumn('User Last Name'),
                    TableFilterHelper::selectColumn('Paid Status', ['0' => 'Unpaid', '1' => 'Paid', '2' => 'Refunded']),
                    TableFilterHelper::textColumn('Total'),
                    TableFilterHelper::textColumn('Created At'),
                    TableFilterHelper::clearButtonColumn('Actions', 'action-column-3'),
                ]"
            >
                @forelse($invoices as $invoice)
                    <tr class="data-row">
                        <td @dataColumn(0)>{{ $invoice->id }}</td>
                        <td @dataColumn(1)>
                            <a href="{{ route('user.show', $invoice->user->id) }}">
                                {{ $invoice->user->first_name }}
                            </a>
                        </td>
                        <td @dataColumn(2)>
                            <a href="{{ route('user.show', $invoice->user->id) }}">
                                {{ $invoice->user->last_name }}
                            </a>
                        </td>
                        <td @dataColumn(3) @dataValue((string) $invoice->paid_status) class="{{ Auth::user()->isSuper() ? 'bg-admin' : '' }}">
                            @if($invoice->paid_status === 0)
                                <span class="badge badge-warning">Unpaid</span>
                            @elseif($invoice->paid_status === 1)
                                <span class="badge badge-success">Paid</span>
                            @elseif($invoice->paid_status === 2)
                                <span class="badge badge-danger">Refunded</span>
                            @endif
                        </td>
                        <td @dataColumn(4)>{{ number_format($invoice->total, 2) }} {{ config('app.currency', '$') }}</td>
                        <td @dataColumn(5)>{{ $invoice->created_at->format('M d, Y') }}</td>
                        <td @dataColumn(6)>
                            <div class="btn-group">
                                <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye fa-fw"></i>
                                </a>
                                @if($invoice->total > 0 && $invoice->moneyTransactions->where('is_success', true)->count() === 1)
                                    @php
                                        $successTransaction = $invoice->moneyTransactions->where('is_success', true)->first();
                                    @endphp
                                    @if($successTransaction && $successTransaction->canBeRefund())
                                        <a href="{{ route('money-transaction.refund', $successTransaction->id) }}" class="btn btn-sm btn-danger JS__load_in_modal">
                                            Refund
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No invoices found.</td>
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
.action-column-3 {
    width: 150px;
}
.bg-admin {
    background-color: #f8d7da !important;
}
.badge-warning {
    background-color: #f6c23e;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-danger {
    background-color: #e74a3b;
}
.panel-body {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
</style>

<script>
// Date range picker functionality
document.addEventListener('DOMContentLoaded', function() {
    // Simple date range picker implementation
    const dateRangePicker = document.getElementById('dateRangePicker');
    if (dateRangePicker) {
        // You can integrate a proper date range picker library here
        // For now, we'll use a simple input with placeholder
        dateRangePicker.addEventListener('focus', function() {
            this.type = 'date';
        });
    }
});
</script>
@endsection
