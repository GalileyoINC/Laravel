<h4>Influencer Information</h4>

<table class="table table-striped table-bordered detail-view">
    <tbody>
        <tr>
            <th style="width: 200px;">Is Influencer</th>
            <td>
                @if($model->is_influencer)
                    <span class="badge bg-success">Yes</span>
                @else
                    <span class="badge bg-default">No</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Verified At</th>
            <td>{{ $model->influencer_verified_at ? \Carbon\Carbon::parse($model->influencer_verified_at)->format('M d, Y h:i a') : 'Not verified' }}</td>
        </tr>
        <tr>
            <th>Influencer Code</th>
            <td>{{ $model->influencer_code ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Name as Referral</th>
            <td>{{ $model->name_as_referral ?? 'N/A' }}</td>
        </tr>
    </tbody>
</table>

@if($model->is_influencer)
    <h5>Promocodes</h5>
    @if($model->promocodes && $model->promocodes->count() > 0)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Type</th>
                    <th>Active</th>
                    <th>Uses</th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->promocodes as $promo)
                    <tr>
                        <td><code>{{ $promo->text }}</code></td>
                        <td>
                            @if($promo->discount_type == 'percent')
                                {{ $promo->discount_value }}%
                            @else
                                ${{ number_format($promo->discount_value, 2) }}
                            @endif
                        </td>
                        <td>{{ $promo->type_name ?? 'N/A' }}</td>
                        <td>
                            @if($promo->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $promo->use_count ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No promocodes created yet.</p>
    @endif
@endif
