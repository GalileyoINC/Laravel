<h4>Private Feeds / Subscriptions</h4>

@if($model->subscriptions && $model->subscriptions->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Hidden</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->id }}</td>
                    <td>{{ $subscription->title ?? 'N/A' }}</td>
                    <td>{{ $subscription->type_name ?? 'N/A' }}</td>
                    <td>
                        <input type="checkbox" 
                               class="js-feed-visibility" 
                               data-id="{{ $subscription->id }}" 
                               {{ $subscription->is_hidden ? 'checked' : '' }}>
                    </td>
                    <td>{{ $subscription->created_at->format('M d, Y') }}</td>
                    <td>
                        <button class="btn btn-xs btn-info">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-feed-visibility').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                fetch('{{ route("user.set-feed-visibility") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: this.dataset.id,
                        checked: this.checked
                    })
                });
            });
        });
    });
    </script>
@else
    <p class="text-muted">No subscriptions found.</p>
@endif
