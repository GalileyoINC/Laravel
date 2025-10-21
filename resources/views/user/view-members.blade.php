<h4>Sub-accounts / Members</h4>

@if($model->members && $model->members->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->members as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>
                        @if($member->status == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('user.show', $member) }}" class="btn btn-xs btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No sub-accounts found.</p>
@endif
