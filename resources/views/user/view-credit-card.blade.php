<h4>Credit Cards</h4>

@if($model->creditCards && $model->creditCards->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Last 4</th>
                <th>Type</th>
                <th>Expires</th>
                <th>Primary</th>
                <th>Active</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->creditCards as $card)
                <tr>
                    <td>****{{ $card->last_four ?? 'N/A' }}</td>
                    <td>{{ $card->card_type ?? 'N/A' }}</td>
                    <td>{{ $card->expiration_date ?? 'N/A' }}</td>
                    <td>
                        @if($card->is_primary)
                            <span class="text-success"><i class="fas fa-check"></i></span>
                        @else
                            <span class="text-danger"><i class="fas fa-times"></i></span>
                        @endif
                    </td>
                    <td>
                        @if($card->is_active)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $card->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alert alert-info">
        <strong>Gateway Profile ID:</strong> {{ $model->anet_customer_profile_id ?? 'N/A' }}
    </div>
@else
    <p class="text-muted">No credit cards found.</p>
@endif
