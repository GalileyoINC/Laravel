<h4>Membership Information</h4>

<table class="table table-striped table-bordered detail-view">
    <tbody>
        <tr>
            <th style="width: 200px;">Member Since</th>
            <td>{{ $model->created_at->format('M d, Y') }}</td>
        </tr>
        <tr>
            <th>Account Age</th>
            <td>{{ $model->created_at->diffForHumans() }}</td>
        </tr>
        <tr>
            <th>Last Login</th>
            <td>{{ $model->last_login_at ? \Carbon\Carbon::parse($model->last_login_at)->format('M d, Y h:i a') : 'Never' }}</td>
        </tr>
        <tr>
            <th>Total Logins</th>
            <td>{{ $model->login_count ?? 0 }}</td>
        </tr>
        <tr>
            <th>Email Verified</th>
            <td>
                @if($model->is_valid_email)
                    <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i> No</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Receive Subscribe Emails</th>
            <td>
                @if($model->is_receive_subscribe)
                    <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i> No</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Receive List Emails</th>
            <td>
                @if($model->is_receive_list)
                    <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                @else
                    <span class="text-danger"><i class="fas fa-times"></i> No</span>
                @endif
            </td>
        </tr>
    </tbody>
</table>
