<table class="table table-striped table-bordered detail-view">
    <tbody>
        <tr>
            <th style="width: 150px;">ID</th>
            <td>{{ $model->id }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $model->email }}</td>
        </tr>
        <tr>
            <th>First Name</th>
            <td>{{ $model->first_name }}</td>
        </tr>
        <tr>
            <th>Last Name</th>
            <td>{{ $model->last_name }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($model->status == 1)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Cancelled</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Country</th>
            <td>{{ $model->country ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>State</th>
            <td>{{ $model->state ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>City</th>
            <td>{{ $model->city ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>ZIP</th>
            <td>{{ $model->zip ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Timezone</th>
            <td>{{ $model->timezone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Is Influencer</th>
            <td>
                @if($model->is_influencer)
                    <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i> No</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Is Test</th>
            <td>
                @if($model->is_test)
                    <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i> No</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Bonus Points</th>
            <td>{{ $model->bonus_point ?? 0 }}</td>
        </tr>
        <tr>
            <th>Refer Type</th>
            <td>{{ $model->refer_type ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $model->created_at->format('M d, Y h:i a') }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $model->updated_at->format('M d, Y h:i a') }}</td>
        </tr>
    </tbody>
</table>
