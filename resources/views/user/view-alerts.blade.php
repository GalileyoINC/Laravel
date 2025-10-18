<h4>Alert Subscriptions</h4>

@if($model->alertSubscriptions && $model->alertSubscriptions->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Alert Type</th>
                <th>Ticker/Symbol</th>
                <th>Active</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->alertSubscriptions as $alert)
                <tr>
                    <td>{{ $alert->type_name ?? 'N/A' }}</td>
                    <td><strong>{{ $alert->ticker ?? 'N/A' }}</strong></td>
                    <td>
                        @if($alert->is_active)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $alert->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No alert subscriptions found.</p>
@endif
