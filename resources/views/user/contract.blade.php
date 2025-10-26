<h4>Contract Lines</h4>

@if($model->contractLines && $model->contractLines->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Price</th>
                <th>Pay Interval</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->contractLines as $line)
                <tr>
                    <td>{{ $line->id }}</td>
                    <td>{{ $line->title ?? 'N/A' }}</td>
                    <td>${{ number_format($line->price ?? 0, 2) }}</td>
                    <td>{{ $line->pay_interval_name ?? 'N/A' }}</td>
                    <td>
                        @if($line->terminated_at)
                            <span class="badge bg-danger">Terminated</span>
                        @elseif($line->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-default">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $line->start_date ? \Carbon\Carbon::parse($line->start_date)->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $line->end_date ? \Carbon\Carbon::parse($line->end_date)->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('user.invoice-line', $line) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-file-invoice"></i> Invoices
                        </a>
                        @if(!$line->terminated_at)
                            <a href="{{ route('user.terminate', $line) }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-ban"></i> Terminate
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No contract lines found.</p>
@endif
