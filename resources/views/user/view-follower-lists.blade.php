<h4>Follower Lists</h4>

@if($model->followerLists && $model->followerLists->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>List Name</th>
                <th>Followers</th>
                <th>Active</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->followerLists as $list)
                <tr>
                    <td>{{ $list->name }}</td>
                    <td>{{ $list->followers_count ?? 0 }}</td>
                    <td>
                        @if($list->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $list->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No follower lists found.</p>
@endif
