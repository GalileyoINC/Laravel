<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Invoice ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($invoiceLines as $line)
            <tr>
                <td>{{ $line->invoice->id ?? 'N/A' }}</td>
                <td>${{ number_format($line->price ?? 0, 2) }}</td>
                <td>
                    @if($line->invoice && $line->invoice->paid_status == 1)
                        <span class="badge bg-success">Paid</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </td>
                <td>{{ $line->created_at->format('M d, Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No invoices found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
