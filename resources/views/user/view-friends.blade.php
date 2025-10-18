<h4>Friends</h4>

@if($model->friendships && $model->friendships->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Friend</th>
                <th>Status</th>
                <th>Since</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->friendships as $friendship)
                <tr>
                    <td>
                        @if($friendship->friend)
                            <a href="{{ route('user.show', $friendship->friend) }}">
                                {{ $friendship->friend->first_name }} {{ $friendship->friend->last_name }}
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($friendship->status == 1)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-default">Pending</span>
                        @endif
                    </td>
                    <td>{{ $friendship->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No friends found.</p>
@endif
