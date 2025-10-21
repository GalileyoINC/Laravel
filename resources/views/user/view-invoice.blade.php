<h4>Invoices</h4>

@if($model->invoices && $model->invoices->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>${{ number_format($invoice->total ?? 0, 2) }}</td>
                    <td>
                        @if($invoice->paid_status == 1)
                            <span class="badge bg-success">Paid</span>
                        @elseif($invoice->paid_status == 2)
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-danger">Failed</span>
                        @endif
                    </td>
                    <td>{{ $invoice->payment_method ?? 'N/A' }}</td>
                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-xs btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No invoices found.</p>
@endif
