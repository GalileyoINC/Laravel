@extends('layouts.app')

@section('title', 'Invoice #{{ $invoice->id }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Invoice #{{ $invoice->id }}</h1>
        <a href="{{ route('invoice.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Invoices
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Invoice Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $invoice->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>User:</strong></td>
                            <td>
                                <a href="{{ route('user.show', $invoice->user->id) }}">
                                    {{ $invoice->user->getFullName() }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Paid Status:</strong></td>
                            <td>
                                @if($invoice->paid_status === 0)
                                    <span class="badge badge-warning">Unpaid</span>
                                @elseif($invoice->paid_status === 1)
                                    <span class="badge badge-success">Paid</span>
                                @elseif($invoice->paid_status === 2)
                                    <span class="badge badge-danger">Refunded</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td>{{ number_format($invoice->total, 2) }} {{ config('app.currency', '$') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description:</strong></td>
                            <td>{{ $invoice->description }}</td>
                        </tr>
                        <tr>
                            <td><strong>Created At:</strong></td>
                            <td>{{ $invoice->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Lines -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Invoice Lines</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Bundle</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->invoiceLines as $invoiceLine)
                        <tr>
                            <td>
                                @switch($invoiceLine->type)
                                    @case(\App\Models\Finance\InvoiceLine::TYPE_SUBSCRIBE)
                                        {{ $invoiceLine->settings['title'] ?? 'Subscription' }}
                                        @break
                                    @case(\App\Models\Finance\InvoiceLine::TYPE_DEVICE)
                                        {{ ($invoiceLine->settings['title'] ?? 'Device') . ' (' . $invoiceLine->quantity . ')' }}
                                        @break
                                    @case(\App\Models\Finance\InvoiceLine::TYPE_DEVICE_SUBSCRIBE)
                                        {{ ($invoiceLine->settings['title'] ?? 'Device Subscription') . ' (' . $invoiceLine->quantity . ')' }}
                                        @break
                                    @case(\App\Models\Finance\InvoiceLine::TYPE_SHIPPING)
                                        Shipping
                                        @break
                                    @case(\App\Models\Finance\InvoiceLine::TYPE_PROMOCODE)
                                        Promocode
                                        @break
                                    @default
                                        {{ $invoiceLine->settings['title'] ?? 'Unknown' }}
                                @endswitch
                            </td>
                            <td>{{ $invoiceLine->bundle ? $invoiceLine->bundle->title : '-' }}</td>
                            <td>{{ number_format($invoiceLine->total, 2) }} {{ config('app.currency', '$') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Money Transactions -->
    @if($invoice->moneyTransactions->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Credit Card</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoice->moneyTransactions as $moneyTransaction)
                            <tr>
                                <td>{{ $moneyTransaction->transaction_id }}</td>
                                <td>
                                    @if($moneyTransaction->creditCard)
                                        {{ $moneyTransaction->creditCard->type }}
                                        {{ $moneyTransaction->creditCard->num }}
                                        {{ $moneyTransaction->creditCard->expiration_year }}/{{ $moneyTransaction->creditCard->expiration_month }}
                                    @endif
                                </td>
                                <td>
                                    @if($moneyTransaction->is_void)
                                        <b class="text-danger">VOIDED</b>
                                    @endif
                                    @if(!$moneyTransaction->is_success)
                                        <b class="text-danger">Unsuccess</b>
                                    @endif
                                </td>
                                <td>
                                    <b class="{{ $moneyTransaction->total < 0 ? 'text-danger' : '' }}">
                                        {{ number_format($moneyTransaction->total, 2) }} {{ config('app.currency', '$') }}
                                    </b>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.badge-warning {
    background-color: #f6c23e;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-danger {
    background-color: #e74a3b;
}
.text-danger {
    color: #e74a3b !important;
}
</style>
@endsection
