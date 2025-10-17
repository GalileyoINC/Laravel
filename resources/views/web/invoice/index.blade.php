@extends('web.layouts.app')

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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Invoice Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('web.invoice.index') }}" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by ID, name, or email" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="paid_status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="0" {{ ($filters['paid_status'] ?? '') == '0' ? 'selected' : '' }}>Unpaid</option>
                            <option value="1" {{ ($filters['paid_status'] ?? '') == '1' ? 'selected' : '' }}>Paid</option>
                            <option value="2" {{ ($filters['paid_status'] ?? '') == '2' ? 'selected' : '' }}>Refunded</option>
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
                        <a href="{{ route('web.invoice.index') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="grid__id">ID</th>
                        <th>User First Name</th>
                        <th>User Last Name</th>
                        @if(Auth::user()->isSuper())
                            <th class="bg-admin">Paid Status</th>
                        @endif
                        <th>Total</th>
                        <th>Created At</th>
                        <th class="action-column-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>
                                <a href="{{ route('web.user.show', $invoice->user->id) }}">
                                    {{ $invoice->user->first_name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('web.user.show', $invoice->user->id) }}">
                                    {{ $invoice->user->last_name }}
                                </a>
                            </td>
                            @if(Auth::user()->isSuper())
                                <td class="bg-admin">
                                    @if($invoice->paid_status === 0)
                                        <span class="badge badge-warning">Unpaid</span>
                                    @elseif($invoice->paid_status === 1)
                                        <span class="badge badge-success">Paid</span>
                                    @elseif($invoice->paid_status === 2)
                                        <span class="badge badge-danger">Refunded</span>
                                    @endif
                                </td>
                            @endif
                            <td>{{ number_format($invoice->total, 2) }} {{ config('app.currency', '$') }}</td>
                            <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('web.invoice.show', $invoice) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    @if($invoice->total > 0 && $invoice->moneyTransactions->where('is_success', true)->count() === 1)
                                        @php
                                            $successTransaction = $invoice->moneyTransactions->where('is_success', true)->first();
                                        @endphp
                                        @if($successTransaction && $successTransaction->canBeRefund())
                                            <a href="{{ route('web.money-transaction.refund', $successTransaction->id) }}" class="btn btn-sm btn-danger JS__load_in_modal">
                                                Refund
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isSuper() ? '7' : '6' }}" class="text-center">No invoices found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $invoices->links() }}
            </div>
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
