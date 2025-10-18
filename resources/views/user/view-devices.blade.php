<h4>Purchased Devices</h4>

@if(isset($devices) && count($devices) > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Device</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devices as $device)
                <tr>
                    <td>{{ $device['id'] ?? 'N/A' }}</td>
                    <td>{{ $device['product']['name'] ?? 'N/A' }}</td>
                    <td>${{ number_format($device['price'] ?? 0, 2) }}</td>
                    <td>{{ $device['quantity'] ?? 1 }}</td>
                    <td>${{ number_format(($device['price'] ?? 0) * ($device['quantity'] ?? 1), 2) }}</td>
                    <td>{{ isset($device['created_at']) ? \Carbon\Carbon::parse($device['created_at'])->format('M d, Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No devices purchased.</p>
@endif
