<h4>Shipping Address</h4>

@if($model->shippingAddress)
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th style="width: 150px;">Full Name</th>
                <td>{{ $model->shippingAddress->full_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Address Line 1</th>
                <td>{{ $model->shippingAddress->address_line_1 ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Address Line 2</th>
                <td>{{ $model->shippingAddress->address_line_2 ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>City</th>
                <td>{{ $model->shippingAddress->city ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>State</th>
                <td>{{ $model->shippingAddress->state ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>ZIP Code</th>
                <td>{{ $model->shippingAddress->zip ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Country</th>
                <td>{{ $model->shippingAddress->country ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $model->shippingAddress->phone ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>
@else
    <p class="text-muted">No shipping address on file.</p>
@endif
